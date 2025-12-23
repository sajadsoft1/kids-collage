<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Helpers\Utils;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * SetBranchMiddleware
 *
 * Detects and sets the current branch ID for the request.
 * For web requests: reads from session, falls back to user's default branch.
 * For API requests: reads from X-Branch-Id header, falls back to user's default branch.
 */
class SetBranchMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $branchId = null;

        // For web requests, check session first
        if ($request->hasSession()) {
            $sessionKey = config('branch.session_key', 'current_branch_id');
            $branchId = session($sessionKey);
        }

        // For API requests, check header
        if ( ! $branchId && $request->expectsJson()) {
            $headerName = config('branch.header_name', 'X-Branch-Id');
            $branchId = $request->header($headerName);
        }

        // Fallback to authenticated user's default branch
        if ( ! $branchId && Auth::check()) {
            $user = Auth::user();
            if ($user && $user->branch_id) {
                $branchId = $user->branch_id;
            }
        }

        // Set the branch ID if found
        if ($branchId) {
            Utils::setCurrentBranchId($branchId);
        }

        return $next($request);
    }
}
