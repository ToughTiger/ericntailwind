<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Services\LinkedInService; // Ensure this is the correct namespace for LinkedInService
use App\Models\User; // Assuming you have a User model

class LinkedInController extends Controller
{
    public function handleCallback(Request $request, LinkedInService $linkedInService)
    {
        $request->validate([
            'code' => 'required',
            'state' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->state);
        $linkedInService->handleCallback($request->code, $user);

        return redirect()->route('filament.resources.linkedin-connections.index')
            ->with('success', 'Successfully connected with LinkedIn!');
    }
}