<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Drivers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Karnoweb\LaravelNotification\Contracts\NotificationChannelDriver;
use Karnoweb\LaravelNotification\NotificationChannelEnum;
use Throwable;

class EmailChannelDriver implements NotificationChannelDriver
{
    public function __construct(
        private readonly string $mailableClass,
    ) {}

    public function channel(): NotificationChannelEnum
    {
        return NotificationChannelEnum::EMAIL;
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    public function send(object $notifiable, string $event, array $payload, array $context = []): array
    {
        $recipient = $this->resolveRecipient($notifiable, $context);

        if ( ! $recipient) {
            return [
                'status' => 'skipped',
                'reason' => 'recipient_missing',
            ];
        }

        try {
            $mailable = new $this->mailableClass($payload);
            Mail::to($recipient)->send($mailable);

            return [
                'status' => 'sent',
                'recipient' => $recipient,
            ];
        } catch (Throwable $throwable) {
            Log::error('Failed to send email notification', [
                'event' => $event,
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

    /** @param array<string, mixed> $context */
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
