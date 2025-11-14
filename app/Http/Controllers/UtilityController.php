<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class UtilityController extends Controller
{
    public function changeLocale($lang)
    {
        $supported = config('app.supported_locales', ['en', 'fa']);
        if ( ! in_array($lang, $supported, true)) {
            $lang = config('app.fallback_locale', 'en');
        }
        session(['locale' => $lang]);
        app()->setLocale($lang);

        // Redirect to the same page, but with the new locale in the URL.
        $previous = url()->previous();
        $parsed = parse_url($previous);
        $path = $parsed['path'] ?? '/';
        $segments = explode('/', ltrim($path, '/'));

        // Replace the first segment if it is a locale
        if (in_array($segments[0] ?? '', $supported, true)) {
            $segments[0] = $lang;
        } else {
            array_unshift($segments, $lang);
        }
        if ($segments[0] === config('app.fallback_locale')) {
            unset($segments[0]);
        }
        $newPath = '/' . implode('/', $segments);

        return redirect($newPath);
    }
}
