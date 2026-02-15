<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GenderEnum;
use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Enums\ReligionEnum;
use Karnoweb\LaravelNotification\Models\NotificationPreference;
use Karnoweb\LaravelNotification\NotificationChannelRegistry;
use Karnoweb\LaravelNotification\NotificationPreferenceResolver;
use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class Profile extends Model
{
    use HasUser;

    protected $fillable = [
        'user_id',
        'id_number',
        'national_code',
        'birth_date',
        'gender',
        'address',
        'phone',
        'father_name',
        'father_phone',
        'mother_name',
        'mother_phone',
        'religion',
        'salary',
        'benefit',
        'cooperation_start_date',
        'cooperation_end_date',
        'extra_attributes',
    ];

    protected $casts = [
        'gender' => GenderEnum::class,
        'religion' => ReligionEnum::class,
        'id_number' => 'integer',
        'birth_date' => 'date',
        'cooperation_start_date' => 'date',
        'cooperation_end_date' => 'date',
        'extra_attributes' => SchemalessAttributes::class,
    ];

    /** Check if user should receive notification for a specific event and channel */
    public function shouldReceiveNotification(NotificationEventEnum $event, NotificationChannelEnum $channel): bool
    {
        return app(NotificationPreferenceResolver::class)->shouldSend($this, $event->value, $channel);
    }

    /** Get all notification settings (from package table) */
    public function getNotificationSettings(): array
    {
        return NotificationPreference::getAllForNotifiable($this);
    }

    /** Update notification settings (saved to package table) */
    public function updateNotificationSettings(array $settings): void
    {
        $registry = app(NotificationChannelRegistry::class);

        foreach ($settings as $eventValue => $channels) {
            $eventEnum = NotificationEventEnum::tryFrom($eventValue);

            if ( ! $eventEnum || ! is_array($channels)) {
                continue;
            }

            $normalized = [];
            foreach ($channels as $channelValue => $enabled) {
                $channelEnum = NotificationChannelEnum::tryFrom($channelValue);

                if ( ! $channelEnum) {
                    continue;
                }

                if ( ! $registry->isUserToggleable($eventEnum->value, $channelEnum)) {
                    continue;
                }

                $normalized[$channelEnum->value()] = (bool) $enabled;
            }

            NotificationPreference::setChannels($this, $eventEnum->value, $normalized);
        }
    }

    /** Enable notification for a specific event and channel */
    public function enableNotification(NotificationEventEnum $event, NotificationChannelEnum $channel): void
    {
        $settings = $this->getNotificationSettings();
        $settings[$event->value][$channel->value()] = true;
        $this->updateNotificationSettings($settings);
    }

    /** Disable notification for a specific event and channel */
    public function disableNotification(NotificationEventEnum $event, NotificationChannelEnum $channel): void
    {
        $settings = $this->getNotificationSettings();
        $settings[$event->value][$channel->value()] = false;
        $this->updateNotificationSettings($settings);
    }

    /** Get enabled channels for a specific event */
    public function getEnabledChannelsForEvent(NotificationEventEnum $event): array
    {
        return app(NotificationPreferenceResolver::class)
            ->enabledChannels($this, $event->value)
            ->map(static fn ($channel) => $channel->value)
            ->toArray();
    }

    /** Get merged notification configuration for an event */
    public function notificationPreferences(NotificationEventEnum $event): array
    {
        $registry = app(NotificationChannelRegistry::class);
        $resolver = app(NotificationPreferenceResolver::class);

        $eventValue = $event->value;

        return collect($registry->channelConfig($eventValue))
            ->mapWithKeys(function (array $config, string $channelValue) use ($eventValue, $registry, $resolver) {
                $channelEnum = NotificationChannelEnum::tryFrom($channelValue);

                if ( ! $channelEnum) {
                    return [];
                }

                return [
                    $channelValue => [
                        'enabled' => $resolver->shouldSend($this, $eventValue, $channelEnum),
                        'togglable' => $registry->isUserToggleable($eventValue, $channelEnum),
                        'default' => $registry->defaultState($eventValue, $channelEnum),
                    ],
                ];
            })
            ->toArray();
    }
}
