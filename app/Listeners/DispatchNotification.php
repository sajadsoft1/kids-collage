<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\NotificationRequested;

class DispatchNotification
{
    public function handle(NotificationRequested $event): void
    {
        $event->notification->deliver($event->notifiable, $event->profile, $event->context);
    }
}
