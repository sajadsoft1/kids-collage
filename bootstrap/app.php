<?php

declare(strict_types=1);

use App\Http\Middleware\AdminLanguageMiddleware;
use App\Http\Middleware\AdminPanelMiddleware;
use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/admin.php',
        ],
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            ChinLeung\MultilingualRoutes\DetectRequestLocale::class,
        ]);

        $middleware->alias([
            'admin.panel'  => AdminPanelMiddleware::class,
            'locale'       => LanguageMiddleware::class,
            'locale.admin' => AdminLanguageMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->withSchedule(function (Schedule $schedule) {
        // Run content publishing every minute
        $schedule->command('content:publish-scheduled')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground()
            ->onSuccess(function () {
                Log::info('Content publishing schedule completed successfully');
            })
            ->onFailure(function () {
                Log::error('Content publishing schedule failed');
            });
    })
    ->create();
