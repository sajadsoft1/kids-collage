<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasMedia
{
    public function medias(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediaable');
    }
}
