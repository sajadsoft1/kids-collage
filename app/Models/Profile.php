<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GenderEnum;
use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use App\Enums\ReligionEnum;
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
        'gender'                 => GenderEnum::class,
        'religion'               => ReligionEnum::class,
        'id_number'              => 'integer',
        'birth_date'             => 'date',
        'cooperation_start_date' => 'date',
        'cooperation_end_date'   => 'date',
        'extra_attributes'       => SchemalessAttributes::class,
    ];

    /** Check if user should receive notification for a specific event and channel */
    public function shouldReceiveNotification(NotificationEventEnum $event, NotificationChannelEnum $channel): bool
    {
        $notificationSettings = $this->extra_attributes->get('notification_settings', []);

        // If settings not configured, return true (default: send all notifications)
        if (empty($notificationSettings)) {
            return true;
        }

        $eventKey   = $event->value;
        $channelKey = $channel->value;

        // Check if the specific event and channel combination is enabled
        return $notificationSettings[$eventKey][$channelKey] ?? false;
    }

    /** Get all notification settings */
    public function getNotificationSettings(): array
    {
        return $this->extra_attributes->get('notification_settings', []);
    }

    /** Update notification settings */
    public function updateNotificationSettings(array $settings): void
    {
        $this->extra_attributes->set('notification_settings', $settings);
        $this->save();
    }

    /** Enable notification for a specific event and channel */
    public function enableNotification(NotificationEventEnum $event, NotificationChannelEnum $channel): void
    {
        $settings                                 = $this->getNotificationSettings();
        $settings[$event->value][$channel->value] = true;
        $this->updateNotificationSettings($settings);
    }

    /** Disable notification for a specific event and channel */
    public function disableNotification(NotificationEventEnum $event, NotificationChannelEnum $channel): void
    {
        $settings                                 = $this->getNotificationSettings();
        $settings[$event->value][$channel->value] = false;
        $this->updateNotificationSettings($settings);
    }

    /** Get enabled channels for a specific event */
    public function getEnabledChannelsForEvent(NotificationEventEnum $event): array
    {
        $settings      = $this->getNotificationSettings();
        $eventSettings = $settings[$event->value] ?? [];

        $enabledChannels = [];
        foreach ($eventSettings as $channel => $enabled) {
            if ($enabled) {
                $enabledChannels[] = $channel;
            }
        }

        return $enabledChannels;
    }
}
