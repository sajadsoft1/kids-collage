<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\SeoOption;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to handle 301 redirects for SEO purposes.
 *
 * Checks if the current request URL matches any old_url in seo_options table
 * and redirects to the corresponding redirect_to URL with a 301 status code.
 */
class SeoRedirectMiddleware
{
    /**
     * Handle an incoming request and check for SEO redirects.
     *
     * @param Request                      $request The incoming HTTP request.
     * @param Closure(Request): (Response) $next    The next middleware closure.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip redirect check for admin routes and API routes
        if ($request->is('admin/*') || $request->is('api/*')) {
            return $next($request);
        }

        $currentUrl = $request->fullUrl();
        $currentPath = $request->path();

        // Check cache first for performance
        $cacheKey = "seo_redirect_{$currentPath}";
        $redirectUrl = Cache::remember($cacheKey, 3600, function () use ($currentUrl, $currentPath) {
            // Find matching seo_option with old_url
            $seoOption = SeoOption::whereNotNull('old_url')
                ->whereNotNull('redirect_to')
                ->where(function ($query) use ($currentUrl, $currentPath) {
                    $query->where('old_url', $currentUrl)
                        ->orWhere('old_url', $currentPath)
                        ->orWhere('old_url', '/' . $currentPath);
                })
                ->first();

            return $seoOption?->redirect_to;
        });

        // If redirect URL found, perform 301 redirect
        if ($redirectUrl) {
            return redirect($redirectUrl, 301);
        }

        return $next($request);
    }
}
