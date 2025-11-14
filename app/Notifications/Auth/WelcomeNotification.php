<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Enums\NotificationEventEnum;
use App\Models\Profile;
use App\Notifications\BaseNotification;

class WelcomeNotification extends BaseNotification
{
    public function __construct(private readonly ?Profile $profile = null)
    {
        parent::__construct();
    }

    public function event(): NotificationEventEnum
    {
        return NotificationEventEnum::AUTH_WELCOME;
    }

    /** @return array<string, mixed> */
    protected function context(object $notifiable): array
    {
        return [
            'user_name' => $notifiable->name ?? $notifiable->full_name ?? null,
            'message' => 'به خانواده کیدز کالج خوش آمدید!',
            'action_url' => url('/dashboard'),
        ];
    }

    public function send(object $notifiable): void
    {
        $this->deliver($notifiable, $this->profile);
    }
}
