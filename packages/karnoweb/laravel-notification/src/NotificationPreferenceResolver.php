<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification;

use Illuminate\Support\Collection;
use Karnoweb\LaravelNotification\Contracts\GlobalChannelOverridesResolver;
use Karnoweb\LaravelNotification\Contracts\NotificationChannel;
use Karnoweb\LaravelNotification\Contracts\UserChannelOverridesResolver;

class NotificationPreferenceResolver
{
    public function __construct(
        private readonly NotificationChannelRegistry $registry,
        private readonly ?GlobalChannelOverridesResolver $globalOverrides = null,
        private readonly ?UserChannelOverridesResolver $userOverrides = null,
    ) {}

    /** @return Collection<int, NotificationChannelEnum> */
    public function enabledChannels(?object $profile, string $event): Collection
    {
        $channelStates = $this->initialStates($event);
        $globalOverrides = $this->globalOverrides($event);

        foreach ($globalOverrides as $channel => $state) {
            if ($channelStates->has($channel)) {
                $channelStates->put($channel, (bool) $state);
            }
        }

        if ($profile !== null && $this->userOverrides !== null) {
            $userOverrides = $this->userOverrides->get($profile, $event);

            foreach ($userOverrides as $channel => $state) {
                $channelEnum = NotificationChannelEnum::tryFrom($channel);

                if ( ! $channelEnum) {
                    continue;
                }

                if ( ! $this->registry->isUserToggleable($event, $channelEnum)) {
                    continue;
                }

                $channelStates->put($channel, (bool) $state);
            }
        }

        return $channelStates
            ->filter(fn (bool $enabled): bool => $enabled)
            ->keys()
            ->map(fn (string $channel): NotificationChannelEnum => NotificationChannelEnum::from($channel))
            ->values();
    }

    public function shouldSend(?object $profile, string $event, NotificationChannel|NotificationChannelEnum $channel): bool
    {
        $channelValue = $channel instanceof NotificationChannelEnum ? $channel->value : $channel->value();

        return $this->enabledChannels($profile, $event)
            ->contains(fn (NotificationChannelEnum $enabledChannel): bool => $enabledChannel->value === $channelValue);
    }

    /** @return Collection<string, bool> */
    private function initialStates(string $event): Collection
    {
        return collect($this->registry->channelConfig($event))
            ->map(fn (array $config): bool => (bool) ($config['enabled'] ?? false));
    }

    /** @return Collection<string, bool> */
    private function globalOverrides(string $event): Collection
    {
        if ($this->globalOverrides === null) {
            return collect([]);
        }

        return collect($this->globalOverrides->get($event));
    }
}
