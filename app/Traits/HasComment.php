<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\BooleanEnum;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComment
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'morphable');
    }

    public function siteComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'morphable')->where('parent_id', null)->where('published', BooleanEnum::ENABLE->value);
    }
}
