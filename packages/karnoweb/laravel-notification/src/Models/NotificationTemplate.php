<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
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

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'cta' => 'array',
            'placeholders' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function getTable(): string
    {
        return config('karnoweb-notification.table_prefix', 'karnoweb_') . 'notification_templates';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
