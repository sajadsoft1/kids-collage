<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Drivers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Karnoweb\LaravelNotification\Contracts\NotificationChannelDriver;
use Karnoweb\LaravelNotification\NotificationChannelEnum;

class DatabaseChannelDriver implements NotificationChannelDriver
{
    public function channel(): NotificationChannelEnum
    {
        return NotificationChannelEnum::DATABASE;
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    public function send(object $notifiable, string $event, array $payload, array $context = []): array
    {
        $data = array_merge(
            [
                'event' => $event,
                'channel' => $this->channel()->value,
            ],
            $payload
        );

        $notification = DatabaseNotification::query()->create([
            'id' => (string) Str::uuid(),
            'type' => $context['notification_class'] ?? 'custom.notification',
            'notifiable_type' => $this->resolveNotifiableType($notifiable),
            'notifiable_id' => $this->resolveNotifiableKey($notifiable),
            'data' => $data,
            'read_at' => null,
        ]);

        return Arr::only($notification->toArray(), ['id', 'type', 'created_at']);
    }

    private function resolveNotifiableType(object $notifiable): string
    {
        if (method_exists($notifiable, 'getMorphClass')) {
            return $notifiable->getMorphClass();
        }

        return $notifiable::class;
    }

    private function resolveNotifiableKey(object $notifiable): int|string|null
    {
        if (method_exists($notifiable, 'getKey')) {
            return $notifiable->getKey();
        }

        return $notifiable->id ?? null;
    }
}
