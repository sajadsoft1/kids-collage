<?php

declare(strict_types=1);

namespace App\Support\Notifications\Drivers;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Enums\SmsTemplateEnum;
use App\Services\Sms\SmsManager;
use App\Support\Notifications\Contracts\NotificationChannelDriver;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

class SmsChannelDriver implements NotificationChannelDriver
{
    public function channel(): NotificationChannelEnum
    {
        return NotificationChannelEnum::SMS;
    }

    public function send(object $notifiable, NotificationEventEnum $event, array $payload, array $context = []): array
    {
        $recipient = $this->resolveRecipient($notifiable, $context);

        if ( ! $recipient) {
            Log::warning('SMS recipient could not be resolved for notification.', [
                'event' => $event->value,
                'notifiable' => $notifiable::class,
            ]);

            return [
                'status' => 'skipped',
                'reason' => 'recipient_missing',
            ];
        }

        $meta = Arr::wrap($payload['meta'] ?? []);
        $inputs = Arr::wrap($meta['sms_inputs'] ?? []);

        try {
            $builder = SmsManager::instance();
            $templateId = $meta['sms_template_id'] ?? null;
            $templateEnum = $meta['sms_template_enum'] ?? null;

            if ($templateId) {
                $builder->templateId((int) $templateId);
            } elseif ($templateEnum && ($enum = SmsTemplateEnum::tryFrom((string) $templateEnum))) {
                $builder->otp($enum);
            } else {
                $message = $payload['body'] ?? '';
                if ($message === '') {
                    return [
                        'status' => 'skipped',
                        'reason' => 'empty_message',
                    ];
                }
                $builder->message((string) $message);
            }

            if ( ! empty($inputs)) {
                $builder->inputs(array_map('strval', $inputs));
            }

            $builder->number($recipient)->send();

            return [
                'status' => 'sent',
                'driver' => 'sms_manager',
            ];
        } catch (Throwable $exception) {
            Log::error('SMS dispatch failed', [
                'event' => $event->value,
                'recipient' => $recipient,
                'exception' => $exception,
            ]);

            return [
                'status' => 'failed',
                'reason' => $exception->getMessage(),
            ];
        }
    }

    private function resolveRecipient(object $notifiable, array $context): ?string
    {
        if (isset($context['mobile']) && $context['mobile'] !== null) {
            return $context['mobile'];
        }

        if (method_exists($notifiable, 'routeNotificationFor')) {
            $route = $notifiable->routeNotificationFor('sms');

            if (is_string($route)) {
                return $route;
            }
        }

        return $notifiable->mobile ?? $notifiable->phone ?? null;
    }
}
