<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification;

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
        $this->config = $config ?? config('karnoweb-notification.channels', []);
    }

    /** @return array<string, array<string, mixed>> */
    public function channelConfig(string $event): array
    {
        $defaults = Arr::get($this->config, 'defaults.channels', []);
        $overrides = Arr::get($this->config, "events.{$event}.channels", []);

        return array_replace($defaults, $overrides);
    }

    /** @return Collection<int, NotificationChannelEnum> */
    public function enabledChannels(string $event): Collection
    {
        return collect($this->channelConfig($event))
            ->filter(fn (array $options): bool => (bool) ($options['enabled'] ?? false))
            ->keys()
            ->map(fn (string $channel): NotificationChannelEnum => NotificationChannelEnum::from($channel))
            ->values();
    }

    public function isUserToggleable(string $event, NotificationChannelEnum|Contracts\NotificationChannel $channel): bool
    {
        $configuration = $this->channelConfig($event);
        $channelValue = $channel instanceof NotificationChannelEnum ? $channel->value : $channel->value();

        if ( ! array_key_exists($channelValue, $configuration)) {
            return false;
        }

        return (bool) ($configuration[$channelValue]['togglable'] ?? false);
    }

    public function defaultState(string $event, NotificationChannelEnum|Contracts\NotificationChannel $channel): bool
    {
        $configuration = $this->channelConfig($event);
        $channelValue = $channel instanceof NotificationChannelEnum ? $channel->value : $channel->value();

        if ( ! array_key_exists($channelValue, $configuration)) {
            return false;
        }

        return (bool) ($configuration[$channelValue]['enabled'] ?? false);
    }

    public function resolveDriver(NotificationChannelEnum|Contracts\NotificationChannel $channel): Contracts\NotificationChannelDriver
    {
        $binding = $channel instanceof NotificationChannelEnum ? $channel->driverBinding() : $channel->driverBinding();

        return $this->container->make($binding);
    }
}
