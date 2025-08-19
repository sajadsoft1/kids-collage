<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait MorphableTrait
{
    public function morphable(string $table): MorphToMany
    {
        return $this->morphToMany(User::class, 'morphable', $table)
            ->withTimestamps()
            ->wherePivot('user_id', auth()->user()?->id);
    }
}
