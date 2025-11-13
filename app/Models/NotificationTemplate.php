<?php

declare(strict_types=1);

namespace App\Models;

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
        'name',
        'icon',
        'subject',
        'title',
        'subtitle',
        'body',
        'cta',
        'placeholders',
        'is_active',
    ];

    protected $casts = [
        'cta' => 'array',
        'placeholders' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
