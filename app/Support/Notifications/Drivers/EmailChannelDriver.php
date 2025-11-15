<?php

declare(strict_types=1);

namespace App\Support\Notifications\Drivers;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Mail\NotificationMail;
use App\Support\Notifications\Contracts\NotificationChannelDriver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailChannelDriver implements NotificationChannelDriver
{
    public function channel(): NotificationChannelEnum
    {
        return NotificationChannelEnum::EMAIL;
    }

    public function send(object $notifiable, NotificationEventEnum $event, array $payload, array $context = []): array
    {
        $recipient = $this->resolveRecipient($notifiable, $context);

        if ( ! $recipient) {
            return [
                'status' => 'skipped',
                'reason' => 'recipient_missing',
            ];
        }

        try {
            $mailable = new NotificationMail($payload);
            Mail::to($recipient)->send($mailable);

            return [
                'status' => 'sent',
                'recipient' => $recipient,
            ];
        } catch (Throwable $throwable) {
            Log::error('Failed to send email notification', [
                'event' => $event->value,
                'recipient' => $recipient,
                'error' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString(),
            ]);

            return [
                'status' => 'failed',
                'error' => $throwable->getMessage(),
            ];
        }
    }

    private function resolveRecipient(object $notifiable, array $context): ?string
    {
        if (isset($context['email']) && $context['email'] !== null) {
            return $context['email'];
        }

        if (method_exists($notifiable, 'routeNotificationFor')) {
            $route = $notifiable->routeNotificationFor('mail');

            if (is_array($route)) {
                return array_key_first($route);
            }

            if (is_string($route)) {
                return $route;
            }
        }

        return $notifiable->email ?? null;
    }
}
