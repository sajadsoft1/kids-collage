<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Branch::class, 'Index'));
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Branch::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Branch::class, 'Store'));
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Branch::class, 'Update'));
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Branch::class, 'Delete'));
    }

    public function restore(User $user, Branch $branch): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Branch::class, 'Restore'));
    }

    public function forceDelete(User $user, Branch $branch): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Branch::class));
    }
}
