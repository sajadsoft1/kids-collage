<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Enums\SmsTemplateEnum;
use App\Models\Profile;
use App\Notifications\BaseNotification;

class ConfirmNotification extends BaseNotification
{
    public function __construct(
        private readonly ?string $code = null,
        private readonly ?Profile $profile = null,
        private readonly ?string $actionUrl = null,
    ) {
        parent::__construct();
    }

    public function event(): NotificationEventEnum
    {
        return NotificationEventEnum::AUTH_CONFIRM;
    }

    /** @return array<string, mixed> */
    protected function context(object $notifiable): array
    {
        $message = $this->code
            ? sprintf('کد تایید شما %s است.', $this->code)
            : 'کد تایید برای ورود شما ارسال شد.';

        return [
            'user_name' => $notifiable->name ?? $notifiable->full_name ?? null,
            'verification_code' => $this->code,
            'message' => $message,
            'action_url' => $this->actionUrl ?? url('/verify-account'),
        ];
    }

    public function send(object $notifiable): void
    {
        $this->deliver($notifiable, $this->profile);
    }

    /**
     * @param  array<string, mixed> $context
     * @return array<string, mixed>
     */
    protected function channelMeta(NotificationChannelEnum $channel, array $context): array
    {
        if ($channel === NotificationChannelEnum::SMS && $this->code) {
            return [
                'sms_template_enum' => SmsTemplateEnum::VERIFY_PHONE_OTP->value,
                'sms_inputs' => [
                    'code' => $context['verification_code'] ?? $this->code,
                ],
            ];
        }

        return [];
    }
}
