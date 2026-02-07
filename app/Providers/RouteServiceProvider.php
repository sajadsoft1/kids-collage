<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteProvider;
use Illuminate\Support\Facades\Route;
use Karnoweb\TicketChat\Models\Department;

class RouteServiceProvider extends BaseRouteProvider
{
    /** Register services. */
    public function register(): void {}

    /** Bootstrap services. */
    public function boot(): void
    {
        parent::boot();

        Route::bind('ticket_department', fn ($value) => Department::findOrFail($value));
    }
}
