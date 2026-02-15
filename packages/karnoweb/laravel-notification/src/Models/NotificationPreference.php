<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationPreference extends Model
{
    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'event',
        'channel',
        'enabled',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }

    public function getTable(): string
    {
        return config('karnoweb-notification.table_prefix', 'karnoweb_') . 'notification_preferences';
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Set channel preferences for a notifiable and event.
     *
     * @param array<string, bool> $channels channel => enabled
     */
    public static function setChannels(object $notifiable, string $event, array $channels): void
    {
        $type = $notifiable->getMorphClass();
        $id = $notifiable->getKey();

        foreach ($channels as $channel => $enabled) {
            static::query()->updateOrInsert(
                [
                    'notifiable_type' => $type,
                    'notifiable_id' => $id,
                    'event' => $event,
                    'channel' => $channel,
                ],
                ['enabled' => (bool) $enabled, 'updated_at' => now()]
            );
        }
    }

    /**
     * Get channel preferences for a notifiable and event.
     *
     * @return array<string, bool>
     */
    public static function getChannels(?object $notifiable, string $event): array
    {
        if ($notifiable === null) {
            return [];
        }

        $type = $notifiable->getMorphClass();
        $id = $notifiable->getKey();

        return static::query()
            ->where('notifiable_type', $type)
            ->where('notifiable_id', $id)
            ->where('event', $event)
            ->pluck('enabled', 'channel')
            ->toArray();
    }

    /**
     * Get all preferences for a notifiable (event => [channel => enabled]).
     *
     * @return array<string, array<string, bool>>
     */
    public static function getAllForNotifiable(object $notifiable): array
    {
        $type = $notifiable->getMorphClass();
        $id = $notifiable->getKey();

        $rows = static::query()
            ->where('notifiable_type', $type)
            ->where('notifiable_id', $id)
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $result[$row->event][$row->channel] = $row->enabled;
        }

        return $result;
    }
}
