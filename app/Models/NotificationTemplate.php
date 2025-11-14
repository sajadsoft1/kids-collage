<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event',
        'channel',
        'locale',
        'subject',
        'title',
        'subtitle',
        'body',
        'cta',
        'placeholders',
        'is_active',
    ];

    protected $casts = [
        'event' => NotificationEventEnum::class,
        'channel' => NotificationChannelEnum::class,
        'locale' => 'string',
        'cta' => 'array',
        'placeholders' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
