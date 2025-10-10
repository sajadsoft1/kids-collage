<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteProvider;

use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteProvider
{
    /** Register services. */
    public function register(): void {}

    /** Bootstrap services. */
    public function boot(): void {
        parent::boot();

        Route::bind('tag', function ($value) {
            $locale = app()->getLocale();
            return \Spatie\Tags\Tag::where("slug->{$locale}", $value)->firstOrFail();
        });
    }
}
