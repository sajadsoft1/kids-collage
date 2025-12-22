<?php

declare(strict_types=1);

namespace App\Traits;

use App\Helpers\Utils;
use App\Query\BranchBuilder;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * HasConditionalBranchScope Trait
 *
 * Provides conditional branch scoping for models.
 * Only applies scope if the model is not in the shared models list.
 * Always sets branch_id on creation for tracking purposes.
 */
trait HasConditionalBranchScope
{
    /** Boot the trait. */
    protected static function bootHasConditionalBranchScope(): void
    {
        // Only add global scope if model is not shared across branches
        if ( ! static::isSharedAcrossBranches()) {
            static::addGlobalScope(new BranchScope);
        }

        static::creating(function ($model) {
            // Always set branch_id if not already set (for tracking)
            if (empty($model->branch_id)) {
                $model->branch_id = Utils::getCurrentBranchId();
            }
        });
    }

    /** Check if this model is shared across branches. */
    protected static function isSharedAcrossBranches(): bool
    {
        $sharedModels = config('branch.models_shared_across_branches', []);

        return in_array(static::class, $sharedModels, true);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     */
    public function newEloquentBuilder($query): BranchBuilder
    {
        return new BranchBuilder($query);
    }
}
