<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Token-based login for dashboard after Nuxt authentication
 * This allows users to login once in Nuxt and automatically be logged in to the dashboard
 */
class TokenLoginAction
{
    use AsAction;

    /**
     * Authenticate user using Sanctum token and create dashboard session
     *
     * @param  string    $token The plain text token from Nuxt login
     * @return User|null The authenticated user or null if token is invalid
     */
    public function handle(string $token): ?User
    {
        // Find the token in database (tokens are hashed)
        $accessToken = PersonalAccessToken::findToken($token);

        if ( ! $accessToken) {
            return null;
        }

        // Check if token is expired
        if ($accessToken->expires_at && $accessToken->expires_at->isPast()) {
            return null;
        }

        // Get the user associated with this token
        $user = $accessToken->tokenable;

        if ( ! $user) {
            return null;
        }

        // Create session for dashboard
        Auth::guard('web')->login($user, true);
        request()->session()->regenerate();

        // Optional: Delete the token after use for extra security (one-time use)
        // Uncomment if you want single-use tokens
        // $accessToken->delete();

        return $user;
    }
}
