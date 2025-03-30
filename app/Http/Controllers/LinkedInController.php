<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Assuming you have a User model

class LinkedInController extends Controller
{
    // Redirect to LinkedIn for authorization
    public function redirectToLinkedIn()
    {
        return Socialite::driver('linkedin')->redirect(null, ['w_member_social']);
    }

    // Handle LinkedIn callback
    public function handleLinkedInCallback()
    {
        $linkedInUser  = Socialite::driver('linkedin')->user();
        $user = Auth::user(); // Get logged-in Laravel user

         // If no authenticated user, handle as needed (e.g., create a new user)
         if(!$user){
            $user = User::where('email', $linkedInUser->email)->first();
            if(!$user){
                $user = new User();
                $user->name = $linkedInUser->name;
                $user->email = $linkedInUser->email;
                $user->linkedin_user_id = $linkedInUser->id;
                $user->save();
            }
         }

        // Store the access token in the database
        // Save LinkedIn tokens to the user model
    $user->update([
        'linkedin_access_token' => $linkedInUser->token,
        'linkedin_refresh_token' => $linkedInUser->refreshToken ?? null,
        'linkedin_token_expires_at' => now()->addSeconds($linkedInUser->expiresIn),
        'linkedin_user_id' => $linkedInUser->id, // Store LinkedIn ID
    ]);

    Auth::login($user); // Log in the user (if not already)


        return redirect('/dashboard')->with('success', 'Successfully authenticated with LinkedIn!');
    }

    // Post to LinkedIn
    public function postToLinkedIn(Request $request)
    {
        $user = Auth::user();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user->linkedin_access_token,
        ])->get('https://api.linkedin.com/v2/me');

        if ($response->successful()) {
            $linkedInUser = $response->json();
        } else {
            return redirect('/linkedin/auth')->with('error', 'Failed to fetch LinkedIn user details.');
        }
        if(!$user){
            $user = User::where('email', $linkedInUser->email)->first();
            if(!$user){
                $user = new User();
                $user->name = $linkedInUser->name;
                $user->email = $linkedInUser->email;
                $user->linkedin_user_id = $linkedInUser->id;
                $user->save();
            }
         }
        // Check if the user has a LinkedIn access token
        if (!$user->linkedin_access_token) {
            return redirect('/linkedin/auth')->with('error', 'Please authenticate with LinkedIn first.');
        }

        // Refresh the token if it's expired (optional, requires refresh token)
        if ($user->linkedin_token_expires_at && $user->linkedin_token_expires_at->isPast()) {
            $newToken = $this->refreshLinkedInToken($user);
            if (!$newToken) {
                return redirect('/linkedin/auth')->with('error', 'LinkedIn token expired. Please re-authenticate.');
            }
            $user->linkedin_access_token = $newToken;
            $user->save();
        }

        // Make the API request to post on LinkedIn
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user->linkedin_access_token,
            'Content-Type' => 'application/json',
            'X-Restli-Protocol-Version' => '2.0.0',
        ])->post('https://api.linkedin.com/v2/ugcPosts', [
            'author' => 'urn:li:person:' . $user->linkedin_user_id, // Replace with the user's LinkedIn ID
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $request->input('content'),
                    ],
                    'shareMediaCategory' => 'NONE',
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ],
        ]);

        if ($response->successful()) {
            return redirect('/dashboard')->with('success', 'Post published successfully!');
        } else {
            return redirect('/dashboard')->with('error', 'Failed to publish post: ' . $response->body());
        }
    }

    // Refresh LinkedIn token (optional)
    private function refreshLinkedInToken($user)
    {
        $response = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $user->linkedin_refresh_token,
            'client_id' => env('LINKEDIN_CLIENT_ID'),
            'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['access_token'];
        }

        return null;
    }
}