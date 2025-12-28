<?php

declare(strict_types=1);

namespace App\Query;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Builder;

/**
 * BranchBuilder
 *
 * Custom query builder that extends Eloquent Builder with branch-related methods.
 * Used by models that have the HasBranchScope trait.
 *
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 * @extends Builder<TModelClass>
 */
class BranchBuilder extends Builder
{
    /**
     * Query across all branches, bypassing the branch scope.
     *
     * @return $this
     */
    public function withAllBranches()
    {
        return $this->withoutGlobalScope(BranchScope::class);
    }

    /**
     * Query only for a specific branch.
     *
     * @return $this
     */
    public function forBranch(int $branchId)
    {
        return $this->where($this->getModel()->getTable() . '.branch_id', $branchId);
    }
}
