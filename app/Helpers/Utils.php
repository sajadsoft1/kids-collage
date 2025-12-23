<?php

declare(strict_types=1);

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Utils
{
    public static function getEloquent(string $type): ?string
    {
        $reference = Str::studly($type);
        $model = 'App\\Models\\' . $reference;

        return match ($type) {
            default => $model,
        };
    }

    public static function getKeyFromEloquent($class): string
    {
        return Str::kebab(last(explode('\\', $class)));
    }

    /** Set the current branch ID for the request. */
    public static function setCurrentBranchId(int|string $branchId): void
    {
        $branchId = (int) $branchId;

        // Store in app instance for current request
        app()->instance('current_branch_id', $branchId);

        // Store in session for web requests
        if (request()->hasSession()) {
            session([config('branch.session_key') => $branchId]);
        }
    }

    /**
     * Get the current branch ID.
     *
     * Priority:
     * 1. App instance (current request)
     * 2. Session (for web requests)
     * 3. Header (for API requests)
     * 4. Authenticated user's default branch
     * 5. Config default
     */
    public static function getCurrentBranchId(): int
    {
        try {
            // 1. Check app instance (set by middleware or manually)
            if (app()->bound('current_branch_id')) {
                $branchId = app('current_branch_id');
                if ($branchId && $branchId > 0) {
                    return (int) $branchId;
                }
            }
        } catch (Exception $exception) {
            // Continue to next fallback
        }

        // 2. Check session (for web requests)
        if (request()->hasSession()) {
            $sessionKey = config('branch.session_key', 'current_branch_id');
            $branchId = session($sessionKey);
            if ($branchId && $branchId > 0) {
                return (int) $branchId;
            }
        }

        // 3. Check header (for API requests)
        $headerName = config('branch.header_name', 'X-Branch-Id');
        $branchId = request()->header($headerName);
        if ($branchId && $branchId > 0) {
            return (int) $branchId;
        }

        // 4. Check authenticated user's default branch
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && $user->branch_id) {
                return (int) $user->branch_id;
            }
        }

        // 5. Fallback to config default
        return (int) config('branch.default_branch_id', 1);
    }
}
