<?php

declare(strict_types=1);

namespace App\Support\Notifications;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Enums\SettingEnum;
use App\Models\Profile;
use App\Services\Setting\SettingService;
use Illuminate\Support\Collection;

class NotificationPreferenceResolver
{
    public function __construct(
        private readonly NotificationChannelRegistry $registry,
    ) {}

    /** @return Collection<int, NotificationChannelEnum> */
    public function enabledChannels(?Profile $profile, NotificationEventEnum $event): Collection
    {
        $channelStates = $this->initialStates($event);
        $globalOverrides = $this->globalOverrides($event);

        foreach ($globalOverrides as $channel => $state) {
            if ($channelStates->has($channel)) {
                $channelStates->put($channel, (bool) $state);
            }
        }

        if ($profile) {
            $userOverrides = $this->userOverrides($profile, $event);

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

    public function shouldSend(?Profile $profile, NotificationEventEnum $event, NotificationChannelEnum $channel): bool
    {
        return $this->enabledChannels($profile, $event)
            ->contains(fn (NotificationChannelEnum $enabledChannel): bool => $enabledChannel === $channel);
    }

    /** @return Collection<string, bool> */
    private function initialStates(NotificationEventEnum $event): Collection
    {
        return collect($this->registry->channelConfig($event))
            ->map(fn (array $config): bool => (bool) ($config['enabled'] ?? false));
    }

    /** @return Collection<string, bool> */
    private function globalOverrides(NotificationEventEnum $event): Collection
    {
        $settings = SettingService::get(SettingEnum::NOTIFICATION, $event->value, []);

        return collect(is_array($settings) ? $settings : []);
    }

    /** @return Collection<string, bool> */
    private function userOverrides(Profile $profile, NotificationEventEnum $event): Collection
    {
        $settings = $profile->getNotificationSettings();
        $eventSettings = $settings[$event->value] ?? [];

        return collect(is_array($eventSettings) ? $eventSettings : []);
    }
}
