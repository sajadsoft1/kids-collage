<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\UserView;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasView
{
    public function views(): MorphMany
    {
        return $this->morphMany(UserView::class, 'morphable');
    }

    public function readByUser(): bool
    {
        if (auth()->check()) {
            return $this->views()->where('user_id', auth()->id())->exists();
        }

        return $this->views()->where('ip', request()->ip())->exists();
    }
}
