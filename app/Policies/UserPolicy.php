<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Services\Permissions\PermissionsService;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Index'));
    }

    public function view(User $user, User $userC): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Store'));
    }

    public function update(User $user, User $userC): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Update'));
    }

    public function delete(User $user, User $userC): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Delete'));
    }

    public function restore(User $user, User $userC): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Restore'));
    }

    public function forceDelete(User $user, User $userC): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class));
    }
}
