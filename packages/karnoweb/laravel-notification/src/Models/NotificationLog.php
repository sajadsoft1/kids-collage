<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationLog extends Model
{
    protected $fillable = [
        'event',
        'channel',
        'notifiable_type',
        'notifiable_id',
        'notification_class',
        'status',
        'attempts',
        'queued_at',
        'sent_at',
        'failed_at',
        'payload',
        'response',
        'error_message',
    ];

    public function getTable(): string
    {
        return config('karnoweb-notification.table_prefix', 'karnoweb_') . 'notification_logs';
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'queued_at' => 'datetime',
            'sent_at' => 'datetime',
            'failed_at' => 'datetime',
            'payload' => 'array',
            'response' => 'array',
        ];
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeForEvent(Builder $query, string $event): Builder
    {
        return $query->where('event', $event);
    }

    public function scopeForChannel(Builder $query, string $channel): Builder
    {
        return $query->where('channel', $channel);
    }
}
