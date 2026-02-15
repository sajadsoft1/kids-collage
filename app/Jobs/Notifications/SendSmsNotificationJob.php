<?php

declare(strict_types=1);

namespace App\Jobs\Notifications;

use App\Enums\NotificationChannelEnum;
use Karnoweb\LaravelNotification\Jobs\SendChannelNotificationJob;

class SendSmsNotificationJob extends SendChannelNotificationJob
{
    protected function channel(): NotificationChannelEnum
    {
        return NotificationChannelEnum::SMS;
    }
}
