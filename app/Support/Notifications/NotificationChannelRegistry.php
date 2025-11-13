<?php

declare(strict_types=1);

namespace App\Support\Notifications;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Support\Notifications\Contracts\NotificationChannelDriver;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class NotificationChannelRegistry
{
    private array $config;

    public function __construct(
        private readonly Container $container,
        ?array $config = null,
    ) {
        $this->config = $config ?? config('notification_channels', []);
    }

    /** @return array<string, array<string, mixed>> */
    public function channelConfig(NotificationEventEnum $event): array
    {
        $defaults = Arr::get($this->config, 'defaults.channels', []);
        $overrides = Arr::get($this->config, "events.{$event->value}.channels", []);

        return array_replace($defaults, $overrides);
    }

    /** @return Collection<int, NotificationChannelEnum> */
    public function enabledChannels(NotificationEventEnum $event): Collection
    {
        return collect($this->channelConfig($event))
            ->filter(fn (array $options): bool => (bool) ($options['enabled'] ?? false))
            ->keys()
            ->map(fn (string $channel): NotificationChannelEnum => NotificationChannelEnum::from($channel))
            ->values();
    }

    public function isUserToggleable(NotificationEventEnum $event, NotificationChannelEnum $channel): bool
    {
        $configuration = $this->channelConfig($event);

        if ( ! array_key_exists($channel->value, $configuration)) {
            return false;
        }

        return (bool) ($configuration[$channel->value]['togglable'] ?? false);
    }

    public function defaultState(NotificationEventEnum $event, NotificationChannelEnum $channel): bool
    {
        $configuration = $this->channelConfig($event);

        if ( ! array_key_exists($channel->value, $configuration)) {
            return false;
        }

        return (bool) ($configuration[$channel->value]['enabled'] ?? false);
    }

    public function resolveDriver(NotificationChannelEnum $channel): NotificationChannelDriver
    {
        return $this->container->make($channel->driverBinding());
    }
}
