<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Profile;
use App\Notifications\BaseNotification;

class NotificationRequested
{
    /** @param array<string, mixed> $context */
    public function __construct(
        public readonly object $notifiable,
        public readonly BaseNotification $notification,
        public readonly ?Profile $profile = null,
        public readonly array $context = [],
    ) {}
}
