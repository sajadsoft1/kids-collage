<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Enums\NotificationEventEnum;
use App\Models\Profile;
use App\Notifications\BaseNotification;

class RegisterNotification extends BaseNotification
{
    public function __construct(
        private readonly ?Profile $profile = null,
        private readonly ?string $actionUrl = null,
    ) {
        parent::__construct();
    }

    public function event(): NotificationEventEnum
    {
        return NotificationEventEnum::AUTH_REGISTER;
    }

    /** @return array<string, mixed> */
    protected function context(object $notifiable): array
    {
        return [
            'user_name' => $notifiable->name ?? $notifiable->full_name ?? null,
            'message' => 'ثبت‌نام شما با موفقیت انجام شد.',
            'action_url' => $this->actionUrl ?? url('/login'),
        ];
    }

    public function send(object $notifiable): void
    {
        $this->deliver($notifiable, $this->profile);
    }
}
