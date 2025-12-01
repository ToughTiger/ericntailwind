<?php

namespace App\Http\Controllers;

use App\Filament\Resources\LinkedInConnectionResource;
use App\Models\User;
use App\Services\LinkedInService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class LinkedInController extends Controller
{
    /**
     * Start OAuth — do a HARD redirect to LinkedIn (no Socialite, no XHR).
     * Route: GET /linkedin/auth/{user}
     */
    public function redirect(User $user, LinkedInService $svc)
    {
        // allow self or admins
        abort_unless(
            Auth::id() === $user->id || (Auth::check() && Auth::user()->can('update', $user)),
            403
        );
        $authUrl = $svc->getAuthUrl($user);
        // Build the proper LinkedIn URL (scopes + exact redirect) and leave the app.
        // return redirect()->away($svc->getAuthUrl($user));
        return response()->view('oauth/redirect-away', ['url' => $authUrl]);
    }

    /**
     * OAuth callback — exchange code for tokens, fetch profile, save to user.
     * Route: GET /linkedin/callback
     */
   
    public function handleCallback(Request $request, LinkedInService $svc)
    {
        \Log::info('HIT CALLBACK', $request->all());
        // LinkedIn will return ?code=...&state={your_user_id}
        $request->validate([
            'code'  => ['required', 'string'],
            'state' => ['required', 'integer', 'exists:users,id'],
        ]);

        $user = User::findOrFail($request->integer('state'));

        try {
            $svc->handleCallback($request->string('code')->toString(), $user);

            // Optional: Filament toast
            \Filament\Notifications\Notification::make()
                ->title('LinkedIn connected')
                ->success()
                ->send();

            return redirect(LinkedInConnectionResource::getUrl('index'));
        } catch (\Throwable $e) {
            Log::error('LinkedIn OAuth callback failed', [
                'user_id' => $user->id,
                'err'     => $e->getMessage(),
            ]);

            \Filament\Notifications\Notification::make()
                ->title('Failed to connect LinkedIn')
                ->body('Please try again. If it persists, recheck your app keys & redirect URI.')
                ->danger()
                ->send();

            return redirect(LinkedInConnectionResource::getUrl('index'))
                ->with('error', 'LinkedIn connection failed. Please try again.');
        }
    }
}
