<?php

declare(strict_types=1);

namespace App\Support\Notifications\Messages;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;

class NotificationMessage
{
    /**
     * @param array<string, array<string, mixed>> $channels
     * @param array<string, mixed>                $context
     */
    public function __construct(
        public readonly NotificationEventEnum $event,
        private array $channels = [],
        private array $context = [],
    ) {}

    public static function make(NotificationEventEnum $event): self
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
    public function withChannel(NotificationChannelEnum $channel, array $payload): self
    {
        $clone = clone $this;
        $clone->channels[$channel->value] = $payload;

        return $clone;
    }

    /** @return array<string, array<string, mixed>> */
    public function channels(): array
    {
        return $this->channels;
    }

    /** @return array<string, mixed> */
    public function channelPayload(NotificationChannelEnum $channel): array
    {
        return $this->channels[$channel->value] ?? [];
    }

    /** @return array<string, mixed> */
    public function context(): array
    {
        return $this->context;
    }
}
