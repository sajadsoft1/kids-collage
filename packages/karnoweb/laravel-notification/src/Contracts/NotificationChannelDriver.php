<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Contracts;

interface NotificationChannelDriver
{
    public function channel(): NotificationChannel;

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    public function send(object $notifiable, string $event, array $payload, array $context = []): array;
}
