<?php

declare(strict_types=1);

namespace App\Support\Notifications\Contracts;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;

interface NotificationChannelDriver
{
    public function channel(): NotificationChannelEnum;

    /** @return array<string, mixed> */
    public function send(object $notifiable, NotificationEventEnum $event, array $payload, array $context = []): array;
}
