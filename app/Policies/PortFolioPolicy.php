<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PortFolio;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class PortFolioPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PortFolio::class, 'Index'));
    }

    public function view(User $user, PortFolio $portFolio): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PortFolio::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PortFolio::class, 'Store'));
    }

    public function update(User $user, PortFolio $portFolio): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PortFolio::class, 'Update'));
    }

    public function delete(User $user, PortFolio $portFolio): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PortFolio::class, 'Delete'));
    }

    public function restore(User $user, PortFolio $portFolio): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PortFolio::class, 'Restore'));
    }

    public function forceDelete(User $user, PortFolio $portFolio): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(PortFolio::class));
    }
}
