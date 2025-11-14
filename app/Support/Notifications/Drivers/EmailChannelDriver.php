<?php

declare(strict_types=1);

namespace App\Support\Notifications\Drivers;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Support\Notifications\Contracts\NotificationChannelDriver;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            Log::warning('Email recipient could not be resolved for notification.', [
                'event' => $event->value,
                'notifiable' => $notifiable::class,
            ]);

            return [
                'status' => 'skipped',
                'reason' => 'recipient_missing',
            ];
        }

        $endpoint = config('services.notifications.email.endpoint');

        if ( ! $endpoint) {
            Log::warning('Email notification endpoint is not configured.');

            return [
                'status' => 'skipped',
                'reason' => 'endpoint_missing',
            ];
        }

        $requestPayload = [
            'to' => $recipient,
            'subject' => $payload['subject'] ?? null,
            'body' => $payload['body'] ?? $payload['title'] ?? '',
            'meta' => [
                'event' => $event->value,
                'channel' => $this->channel()->value,
            ],
        ];

        $response = Http::withHeaders($this->headers('email'))
            ->timeout((int) config('services.notifications.email.timeout', 10))
            ->post($endpoint, $requestPayload);

        return [
            'status' => $response->successful() ? 'queued' : 'failed',
            'http_code' => $response->status(),
            'body' => $response->json(),
        ];
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

    /** @return array<string, string> */
    private function headers(string $channel): array
    {
        $token = config("services.notifications.{$channel}.token");

        return array_filter([
            'Authorization' => $token ? 'Bearer ' . $token : null,
            'Accept' => 'application/json',
        ]);
    }
}
