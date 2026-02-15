<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification;

use Illuminate\Support\ServiceProvider;
use Karnoweb\LaravelNotification\Contracts\UserChannelOverridesResolver;
use Karnoweb\LaravelNotification\Drivers\DatabaseChannelDriver;
use Karnoweb\LaravelNotification\Drivers\EmailChannelDriver;
use Karnoweb\LaravelNotification\Resolvers\DatabaseUserChannelOverridesResolver;

class LaravelNotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/karnoweb-notification.php',
            'karnoweb-notification'
        );

        if ( ! $this->app->bound(UserChannelOverridesResolver::class)) {
            $this->app->singleton(UserChannelOverridesResolver::class, DatabaseUserChannelOverridesResolver::class);
        }

        $this->registerDrivers();
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/karnoweb-notification.php' => config_path('karnoweb-notification.php'),
            ], 'karnoweb-notification-config');
        }
    }

    private function registerDrivers(): void
    {
        $this->app->singleton(NotificationChannelRegistry::class, function ($app) {
            return new NotificationChannelRegistry($app, config('karnoweb-notification.channels'));
        });

        $this->app->singleton(NotificationPreferenceResolver::class, function ($app) {
            $registry = $app->make(NotificationChannelRegistry::class);
            $global = $app->bound(Contracts\GlobalChannelOverridesResolver::class)
                ? $app->make(Contracts\GlobalChannelOverridesResolver::class)
                : null;
            $user = $app->bound(UserChannelOverridesResolver::class)
                ? $app->make(UserChannelOverridesResolver::class)
                : null;

            return new NotificationPreferenceResolver($registry, $global, $user);
        });

        $this->app->singleton(NotificationDispatcher::class);

        $this->app->singleton(NotificationChannelEnum::DATABASE->driverBinding(), DatabaseChannelDriver::class);

        $mailable = config('karnoweb-notification.mailable');
        if ($mailable && is_string($mailable)) {
            $this->app->singleton(NotificationChannelEnum::EMAIL->driverBinding(), function () use ($mailable) {
                return new EmailChannelDriver($mailable);
            });
        }

        $this->app->singleton(NotificationService::class);
        $this->app->alias(NotificationService::class, 'karnoweb.notification.service');

        $this->app->alias(NotificationChannelRegistry::class, 'karnoweb.notification.registry');
        $this->app->alias(NotificationPreferenceResolver::class, 'karnoweb.notification.preference_resolver');
        $this->app->alias(NotificationDispatcher::class, 'karnoweb.notification.dispatcher');
    }
}
