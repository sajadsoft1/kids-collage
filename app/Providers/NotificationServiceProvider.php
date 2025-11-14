<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\CourseSessionReminderDispatched;
use App\Events\NotificationRequested;
use App\Listeners\DispatchNotification;
use App\Listeners\SendCourseSessionReminder;
use App\Support\Notifications\Drivers\DatabaseChannelDriver;
use App\Support\Notifications\Drivers\EmailChannelDriver;
use App\Support\Notifications\Drivers\SmsChannelDriver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('notifications.channels.sms', fn (): SmsChannelDriver => new SmsChannelDriver);
        $this->app->singleton('notifications.channels.email', fn (): EmailChannelDriver => new EmailChannelDriver);
        $this->app->singleton('notifications.channels.database', fn (): DatabaseChannelDriver => new DatabaseChannelDriver);
        $this->app->singleton('notifications.channels.firebase', fn (): DatabaseChannelDriver => new DatabaseChannelDriver);
        $this->app->singleton('notifications.channels.telegram', fn (): DatabaseChannelDriver => new DatabaseChannelDriver);
        $this->app->singleton('notifications.channels.whatsapp', fn (): DatabaseChannelDriver => new DatabaseChannelDriver);
    }

    public function boot(): void
    {
        Event::listen(NotificationRequested::class, DispatchNotification::class);
        Event::listen(CourseSessionReminderDispatched::class, SendCourseSessionReminder::class);
    }
}
