<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Teammate;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class TeammatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Teammate::class, 'Index'));
    }

    public function view(User $user, Teammate $teammate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Teammate::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Teammate::class, 'Store'));
    }

    public function update(User $user, Teammate $teammate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Teammate::class, 'Update'));
    }

    public function delete(User $user, Teammate $teammate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Teammate::class, 'Delete'));
    }

    public function restore(User $user, Teammate $teammate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Teammate::class, 'Restore'));
    }

    public function forceDelete(User $user, Teammate $teammate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Teammate::class));
    }
}
