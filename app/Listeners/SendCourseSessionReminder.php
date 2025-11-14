<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\CourseSessionReminderDispatched;
use App\Events\NotificationRequested;
use App\Notifications\Courses\CourseSessionReminderNotification;

class SendCourseSessionReminder
{
    public function handle(CourseSessionReminderDispatched $event): void
    {
        $notification = new CourseSessionReminderNotification($event->session);
        $profile = $event->profile ?? $event->user->profile;

        event(new NotificationRequested($event->user, $notification, $profile));
    }
}
