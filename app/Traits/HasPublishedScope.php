<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\BooleanEnum;
use Illuminate\Database\Eloquent\Builder;

trait HasPublishedScope
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', BooleanEnum::ENABLE->value);
    }
}
