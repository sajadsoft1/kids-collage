<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Opinion;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class OpinionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Index'));
    }

    public function view(User $user, Opinion $opinion): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Store'));
    }

    public function update(User $user, Opinion $opinion): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Update'));
    }

    public function delete(User $user, Opinion $opinion): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Delete'));
    }

    public function restore(User $user, Opinion $opinion): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class, 'Restore'));
    }

    public function forceDelete(User $user, Opinion $opinion): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Opinion::class));
    }
}
