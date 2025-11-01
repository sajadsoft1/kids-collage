<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\BooleanEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminPanelMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ( ! Auth::check()) {
            return redirect(route('admin.auth.login'));
        }

        $user = Auth::user();

        if ($user === null) {
            return redirect(route('admin.auth.login'));
        }

        // Check if user is blocked by admin
        if ($user->status === BooleanEnum::DISABLE) {
            abort(403, 'حساب کاربری شما توسط مدیر مسدود شده است');
        }

        // Check if user has admin roles
        if (count($user->getRoleNames()) > 0) {
            return $next($request);
        }

        abort(403, 'دسترسی غیرمجاز');
    }
}
