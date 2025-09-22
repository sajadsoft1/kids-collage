<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasWishList
{
    use MorphableTrait;

    public function wishes(): MorphMany
    {
        return $this->morphMany(Wishlist::class, 'morphable');
    }

    public function isWished(): bool
    {
        if (auth()->check()) {
            return $this->wishes()->where('user_id', auth()->id())->exists();
        }
        return false;
    }
}
