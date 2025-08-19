<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Role::class, 'Index'));
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Role::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Role::class, 'Store'));
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Role::class, 'Update'));
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Role::class, 'Delete'));
    }

    public function restore(User $user, Role $role): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Role::class, 'Restore'));
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Role::class));
    }
}
