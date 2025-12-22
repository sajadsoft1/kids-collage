<?php

declare(strict_types=1);

namespace App\Scopes;

use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * BranchScope
 *
 * Global scope that automatically filters queries by the current branch ID.
 * Can be disabled globally using disableGlobal() and enabled using enableGlobal().
 */
class BranchScope implements Scope
{
    protected static bool $withAllBranches = false;

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder<Model> $builder
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (static::$withAllBranches) {
            return;
        }

        // Don't apply branch scope in frontend (non-admin routes)
        if ($this->isFrontendRequest()) {
            return;
        }

        $branchId = Utils::getCurrentBranchId();

        // Only apply scope if we have a valid branchId
        if ($branchId && $branchId > 0) {
            $table = $model->getTable();
            $builder->where($table . '.branch_id', $branchId);
        }
    }

    /** Check if the current request is from frontend (non-admin). */
    protected function isFrontendRequest(): bool
    {
        if ( ! request()) {
            return false;
        }

        $path = request()->path();
        $isApi = request()->is('api/*');
        $isAdmin = str_starts_with($path, 'admin') || ($isApi && str_contains($path, 'admin'));

        // Don't apply branch scope in frontend (non-admin routes)
        return ! $isAdmin;
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param Builder<Model> $builder
     */
    public function extend(Builder $builder): void
    {
        $builder->macro('withAllBranches', function (Builder $builder) {
            return $builder->withoutGlobalScope(static::class);
        });
    }

    /** Enable the global scope (default behavior). */
    public static function enableGlobal(): void
    {
        static::$withAllBranches = false;
    }

    /** Disable the global scope temporarily. */
    public static function disableGlobal(): void
    {
        static::$withAllBranches = true;
    }

    /** Check if the scope is currently disabled. */
    public static function isDisabled(): bool
    {
        return static::$withAllBranches;
    }
}
