<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Bulletin;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class BulletinPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Bulletin::class, 'Index'));
    }

    public function view(User $user, Bulletin $bulletin): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Bulletin::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Bulletin::class, 'Store'));
    }

    public function update(User $user, Bulletin $bulletin): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Bulletin::class, 'Update'));
    }

    public function delete(User $user, Bulletin $bulletin): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Bulletin::class, 'Delete'));
    }

    public function restore(User $user, Bulletin $bulletin): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Bulletin::class, 'Restore'));
    }

    public function forceDelete(User $user, Bulletin $bulletin): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Bulletin::class));
    }
}
