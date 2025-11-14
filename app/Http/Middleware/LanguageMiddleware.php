<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to handle locale prefixing in URLs and set application language context.
 *
 * - Redirects requests with an invalid or missing locale prefix to the correct locale.
 * - Sets the application, session, and URL locale context for supported locales.
 * - Excludes certain routes from locale prefix enforcement.
 */
class LanguageMiddleware
{
    /**
     * Handle an incoming request and ensure locale prefix and context.
     *
     * @param Request                      $request The incoming HTTP request.
     * @param Closure(Request): (Response) $next    The next middleware closure.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1);

        // Redirect if the first segment is not a supported locale and not a protected route
        if ($this->isInvalidLocaleSegment($locale)) {
            return $this->redirectToLocalePrefix($request);
        }

        // Set the locale context for the app, session, and URLs
        $this->setLocaleContext($locale);

        return $next($request);
    }

    /**
     * Determine if the first URL segment is an invalid (unsupported) locale.
     *
     * @param  string|null $segment The first URL segment.
     * @return bool        True if invalid, false otherwise.
     */
    private function isInvalidLocaleSegment(?string $segment): bool
    {
        return $segment !== null
            && ! in_array($segment, $this->supportedLocales(), true)
            && ! in_array($segment, $this->protectedRoutes(), true);
    }

    /**
     * Redirect to the same URL with the correct locale prefix.
     *
     * @param  Request  $request The incoming HTTP request.
     * @return Response Redirect response with the correct locale prefix.
     */
    private function redirectToLocalePrefix(Request $request): Response
    {
        $lang = session('locale', $this->fallbackLocale());
        $segments = $request->segments();
        array_shift($segments); // Remove the invalid first segment
        array_unshift($segments, $lang); // Prepend the correct locale
        $newPath = '/' . implode('/', $segments);
        $query = $request->getQueryString();

        return redirect($newPath . ($query ? '?' . $query : ''));
    }

    /**
     * Set the application, session, and URL locale context.
     *
     * @param string|null $locale The locale to set.
     */
    private function setLocaleContext(?string $locale): void
    {
        if (in_array($locale, $this->supportedLocales(), true)) {
            App::setLocale($locale);
            URL::defaults(['locale' => $locale]);
            session(['locale' => $locale]);
        } else {
            $fallback = session('locale', $this->fallbackLocale());
            App::setLocale($fallback);
            URL::defaults(['locale' => $fallback]);
            session(['locale' => $fallback]);
        }
    }

    /**
     * Get the list of supported locales from config.
     *
     * @return array Supported locale codes.
     */
    private function supportedLocales(): array
    {
        // Ensure the config returns an array
        $locales = config('app.supported_locales', ['en', 'fa']);

        return is_array($locales) ? $locales : (array) $locales;
    }

    /**
     * Get the fallback locale from config.
     *
     * @return string Fallback locale code.
     */
    private function fallbackLocale(): string
    {
        return config('app.fallback_locale', 'fa');
    }

    /**
     * Get the list of routes excluded from locale prefix enforcement.
     *
     * @return array List of protected route prefixes.
     */
    private function protectedRoutes(): array
    {
        return [
            'admin',
            'api',
            'livewire',
            'docs',
            'documents',
            'telescope',
            '_debugbar',
            'horizon',
            'storage',
            'media',
            'assets',
            'build',
            'font',
            'vendor',
            'change-locale',
            'sitemap',
            'sitemap.xml',
            'sitemap-article.xml',
            'sitemap-portfolio.xml',
        ];
    }
}
