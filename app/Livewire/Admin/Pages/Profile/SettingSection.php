<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Profile;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Models\User;
use App\Support\Notifications\NotificationChannelRegistry;
use App\Support\Notifications\NotificationPreferenceResolver;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

/**
 * Component for managing user notification settings
 */
class SettingSection extends Component
{
    use Toast;

    public User $user;

    public array $notificationSettings = [];
    public array $channelMetadata = [];

    /** Mount the component */
    public function mount(?User $user = null): void
    {
        if ($user?->id) {
            $this->user = $user;
        } else {
            $authUser = Auth::user();
            if ( ! $authUser instanceof User) {
                abort(401, 'Unauthorized');
            }
            $this->user = $authUser;
        }

        $this->loadNotificationSettings();
    }

    /** Load notification settings from profile */
    public function loadNotificationSettings(): void
    {
        if ( ! $this->user->profile) {
            $this->user->profile()->create([]);
        }

        $registry = app(NotificationChannelRegistry::class);
        $preferenceResolver = app(NotificationPreferenceResolver::class);

        foreach ($this->availableEvents() as $event) {
            foreach ($this->channels as $channel) {
                $this->channelMetadata[$event->value][$channel->value] = [
                    'togglable' => $registry->isUserToggleable($event, $channel),
                    'default' => $registry->defaultState($event, $channel),
                ];

                $this->notificationSettings[$event->value][$channel->value] = $preferenceResolver
                    ->shouldSend($this->user->profile, $event, $channel);
            }
        }
    }

    /** Save notification settings */
    public function save(): void
    {
        try {
            if ( ! $this->user->profile) {
                $this->user->profile()->create([]);
            }

            $payload = [];

            foreach ($this->availableEvents() as $event) {
                foreach ($this->channels as $channel) {
                    $togglable = $this->channelMetadata[$event->value][$channel->value]['togglable'] ?? false;

                    if ( ! $togglable) {
                        continue;
                    }

                    $payload[$event->value][$channel->value] = (bool) ($this->notificationSettings[$event->value][$channel->value] ?? false);
                }
            }

            $this->user->profile->updateNotificationSettings($payload);

            $this->success(
                title: 'تنظیمات با موفقیت ذخیره شد',
                description: 'تنظیمات اطلاع‌رسانی شما به‌روزرسانی شد',
                timeout: 3000
            );
        } catch (Throwable $e) {
            $this->error(
                title: 'خطا در ذخیره تنظیمات',
                description: $e->getMessage(),
                timeout: 5000
            );

            logger()->error('Failed to save notification settings', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /** Enable all notifications */
    public function enableAll(): void
    {
        $this->applyToTogglableChannels(fn () => true);
    }

    /** Disable all notifications */
    public function disableAll(): void
    {
        $this->applyToTogglableChannels(fn () => false);
    }

    /** Toggle all channels for a specific event */
    public function toggleEvent(string $eventValue, bool $enabled): void
    {
        foreach ($this->channels as $channel) {
            if ( ! ($this->channelMetadata[$eventValue][$channel->value]['togglable'] ?? false)) {
                continue;
            }

            $this->notificationSettings[$eventValue][$channel->value] = $enabled;
        }
    }

    /** Enable only specific channel for all events */
    public function enableOnlyChannel(string $channelValue): void
    {
        foreach ($this->availableEvents() as $event) {
            foreach ($this->channels as $channel) {
                if ( ! ($this->channelMetadata[$event->value][$channel->value]['togglable'] ?? false)) {
                    continue;
                }

                $this->notificationSettings[$event->value][$channel->value] = ($channel->value === $channelValue);
            }
        }
    }

    /** Toggle all events for a specific channel */
    public function toggleChannel(string $channelValue, bool $enabled): void
    {
        foreach ($this->availableEvents() as $event) {
            if ( ! ($this->channelMetadata[$event->value][$channelValue]['togglable'] ?? false)) {
                continue;
            }

            $this->notificationSettings[$event->value][$channelValue] = $enabled;
        }
    }

    /** Get grouped events by category */
    public function getGroupedEventsProperty(): array
    {
        return NotificationEventEnum::groupedByCategory();
    }

    /** Get all channels */
    public function getChannelsProperty(): array
    {
        return array_values(array_filter(
            NotificationChannelEnum::cases(),
            static fn (NotificationChannelEnum $channel) => ! $channel->isFutureChannel()
        ));
    }

    /** Render the component */
    public function render(): View
    {
        return view('livewire.admin.pages.profile.setting-section');
    }

    private function applyToTogglableChannels(callable $callback): void
    {
        foreach ($this->availableEvents() as $event) {
            foreach ($this->channels as $channel) {
                if ( ! ($this->channelMetadata[$event->value][$channel->value]['togglable'] ?? false)) {
                    continue;
                }

                $this->notificationSettings[$event->value][$channel->value] = (bool) $callback($event, $channel);
            }
        }
    }

    /** @return array<int, NotificationEventEnum> */
    private function availableEvents(): array
    {
        return NotificationEventEnum::cases();
    }
}
