<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationLog extends Model
{
    use HasFactory;

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

    protected $casts = [
        'queued_at' => 'datetime',
        'sent_at' => 'datetime',
        'failed_at' => 'datetime',
        'payload' => 'array',
        'response' => 'array',
    ];

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
