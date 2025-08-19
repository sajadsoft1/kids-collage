<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /** Redirect the user to the Google OAuth provider */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /** Handle the callback from Google OAuth provider */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if ( ! $user) {
                // Create new user
                $user = User::create([
                    'name'              => $googleUser->getName() ?: explode(' ', $googleUser->getName())[0] ?? 'User',
                    'family'            => $googleUser->getName() ? (explode(' ', $googleUser->getName())[1] ?? '') : '',
                    'email'             => $googleUser->getEmail(),
                    'password'          => Hash::make(uniqid()), // Generate random password
                    'status'            => true,
                    'email_verified_at' => now(), // Google users are pre-verified
                ]);
            }

            // Login the user
            Auth::login($user);

            return redirect()->route('home-page')->with('success', trans('auth.login_successful'));
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }
    }
}
