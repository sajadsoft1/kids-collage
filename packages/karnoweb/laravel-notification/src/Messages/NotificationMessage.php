<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Messages;

use Karnoweb\LaravelNotification\Contracts\NotificationChannel;

class NotificationMessage
{
    /**
     * @param array<string, array<string, mixed>> $channels
     * @param array<string, mixed>                $context
     */
    public function __construct(
        public readonly string $event,
        private array $channels = [],
        private array $context = [],
    ) {}

    public static function make(string $event): self
    {
        return new self($event);
    }

    public function withContext(array $context): self
    {
        $clone = clone $this;
        $clone->context = $context;

        return $clone;
    }

    /** @param array<string, mixed> $payload */
    public function withChannel(NotificationChannel $channel, array $payload): self
    {
        $clone = clone $this;
        $clone->channels[$channel->value()] = $payload;

        return $clone;
    }

    /** @return array<string, array<string, mixed>> */
    public function channels(): array
    {
        return $this->channels;
    }

    /** @return array<string, mixed> */
    public function channelPayload(NotificationChannel $channel): array
    {
        return $this->channels[$channel->value()] ?? [];
    }

    /** @return array<string, mixed> */
    public function context(): array
    {
        return $this->context;
    }
}
