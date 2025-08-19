<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPanelMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && count(auth()->user()->getRoleNames()) > 0) {
            return $next($request);
        }

        if (auth()->check()) {
            abort(403, 'دسترسی غیرمجاز');
        } else {
            return redirect(route('admin.auth.login'));
        }
    }
}
