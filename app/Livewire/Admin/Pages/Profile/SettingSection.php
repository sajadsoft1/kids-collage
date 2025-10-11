<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Profile;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Models\User;
use Illuminate\Contracts\View\View;
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

    /** Mount the component */
    public function mount(?User $user = null): void
    {
        if ($user?->id) {
            $this->user = $user;
        } else {
            $authUser = auth()->user();
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

        $savedSettings = $this->user->profile->getNotificationSettings();

        // Initialize all events and channels if not set
        foreach (NotificationEventEnum::cases() as $event) {
            foreach (NotificationChannelEnum::cases() as $channel) {
                $this->notificationSettings[$event->value][$channel->value] =
                    $savedSettings[$event->value][$channel->value] ?? true;
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

            $this->user->profile->updateNotificationSettings($this->notificationSettings);

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
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /** Enable all notifications */
    public function enableAll(): void
    {
        foreach (NotificationEventEnum::cases() as $event) {
            foreach (NotificationChannelEnum::cases() as $channel) {
                $this->notificationSettings[$event->value][$channel->value] = true;
            }
        }
    }

    /** Disable all notifications */
    public function disableAll(): void
    {
        foreach (NotificationEventEnum::cases() as $event) {
            foreach (NotificationChannelEnum::cases() as $channel) {
                $this->notificationSettings[$event->value][$channel->value] = false;
            }
        }
    }

    /** Toggle all channels for a specific event */
    public function toggleEvent(string $eventValue, bool $enabled): void
    {
        foreach (NotificationChannelEnum::cases() as $channel) {
            $this->notificationSettings[$eventValue][$channel->value] = $enabled;
        }
    }

    /** Enable only specific channel for all events */
    public function enableOnlyChannel(string $channelValue): void
    {
        foreach (NotificationEventEnum::cases() as $event) {
            foreach (NotificationChannelEnum::cases() as $channel) {
                $this->notificationSettings[$event->value][$channel->value] = ($channel->value === $channelValue);
            }
        }
    }

    /** Toggle all events for a specific channel */
    public function toggleChannel(string $channelValue, bool $enabled): void
    {
        foreach (NotificationEventEnum::cases() as $event) {
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
        return NotificationChannelEnum::cases();
    }

    /** Render the component */
    public function render(): View
    {
        return view('livewire.admin.pages.profile.setting-section');
    }
}
