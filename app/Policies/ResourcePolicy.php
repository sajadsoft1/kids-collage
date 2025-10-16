<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class ResourcePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class, 'Index'));
    }

    public function view(User $user, Resource $resource): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class, 'Store'));
    }

    public function update(User $user, Resource $resource): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class, 'Update'));
    }

    public function delete(User $user, Resource $resource): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class, 'Delete'));
    }

    public function restore(User $user, Resource $resource): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class, 'Restore'));
    }

    public function forceDelete(User $user, Resource $resource): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class));
    }
}
