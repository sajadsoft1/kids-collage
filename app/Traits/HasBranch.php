<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * HasBranch Trait
 *
 * Provides a branch relationship for models that belong to a branch.
 */
trait HasBranch
{
    /** Get the branch that this model belongs to. */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
