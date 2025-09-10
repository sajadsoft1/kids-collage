<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Club;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLicenceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // بررسی وجود کلاب و لایسنس فعال
        $club = Club::first();
        if ( ! $club || ! $club->isLicenseActive()) {
            // اگر مسیر active-splash نباشد، دسترسی را محدود می‌کنیم
            if ($request->path() !== 'api/splash') {
                return response()->json([
                    'message'    => 'Club license is not active',
                    'error_code' => 'LICENSE_INACTIVE',
                    'status'     => 'error',
                ], 403);
            }
        }

        return $next($request);
    }
}
