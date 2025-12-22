<?php

declare(strict_types=1);

namespace App\Traits;

use App\Helpers\Utils;
use App\Query\BranchBuilder;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * HasBranchScope Trait
 *
 * Provides automatic branch scoping for models.
 * Always applies the branch scope and auto-sets branch_id on creation.
 */
trait HasBranchScope
{
    /** Boot the trait. */
    protected static function bootHasBranchScope(): void
    {
        static::addGlobalScope(new BranchScope);

        static::creating(function ($model) {
            // Only set branch_id if not already set
            if (empty($model->branch_id)) {
                $model->branch_id = Utils::getCurrentBranchId();
            }
        });
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
