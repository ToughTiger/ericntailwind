<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Services\LinkedInService; // Ensure this is the correct namespace for LinkedInService
use App\Models\User; // Assuming you have a User model
use App\Filament\Resources\LinkedInConnectionResource;

class LinkedInController extends Controller
{

    public function redirect(User $user)
    {
        // allow self or admins
        abort_unless(Auth::id() === $user->id || Auth::user()->can('update', $user), 403);

        // use the exact URL you registered in LinkedIn portal
        $callback = env('LINKEDIN_REDIRECT_URI', url('/linkedin/callback'));

        // If you're using the classic Socialite LinkedIn driver:
        return Socialite::driver('linkedin')                   // <- keep this 'linkedin' if your config key is services.linkedin
            ->redirectUrl($callback)                           // <- forces exact redirect_uri
            ->scopes(app(LinkedInService::class)->getScopes()) // ['r_liteprofile','w_member_social', …]
            ->with(['state' => (string) $user->id])            // pass your user id for mapping later
            ->redirect();
    }
    public function handleCallback(Request $request, LinkedInService $linkedInService)
    {
        $request->validate([
            'code'  => ['required'],
            'state' => ['required','exists:users,id'],
        ]);

        $user = User::findOrFail($request->state);

        // Exchange code → tokens, fetch profile (OIDC userinfo), save on user
        $linkedInService->handleCallback($request->code, $user);

        // Redirect to the Filament resource index (panel-aware)
        return redirect(LinkedInConnectionResource::getUrl('index'))
            ->with('success', 'Successfully connected with LinkedIn!');
    }
}