<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\CourseSessionReminderDispatched;
use App\Events\NotificationRequested;
use App\Listeners\DispatchNotification;
use App\Listeners\SendCourseSessionReminder;
use App\Support\Notifications\Drivers\SmsChannelDriver;
use App\Support\Notifications\Resolvers\GlobalChannelOverridesResolver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Karnoweb\LaravelNotification\Contracts\GlobalChannelOverridesResolver as GlobalChannelOverridesResolverContract;
use Karnoweb\LaravelNotification\NotificationChannelEnum;

class NotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GlobalChannelOverridesResolverContract::class, GlobalChannelOverridesResolver::class);

        $this->app->singleton(NotificationChannelEnum::SMS->driverBinding(), SmsChannelDriver::class);
    }

    public function boot(): void
    {
        Event::listen(NotificationRequested::class, DispatchNotification::class);
        Event::listen(CourseSessionReminderDispatched::class, SendCourseSessionReminder::class);
    }
}
