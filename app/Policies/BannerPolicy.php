<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Banner;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class BannerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Banner::class, 'Index'));
    }

    public function view(User $user, Banner $banner): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Banner::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Banner::class, 'Store'));
    }

    public function update(User $user, Banner $banner): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Banner::class, 'Update'));
    }

    public function delete(User $user, Banner $banner): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Banner::class, 'Delete'));
    }

    public function restore(User $user, Banner $banner): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Banner::class, 'Restore'));
    }

    public function forceDelete(User $user, Banner $banner): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Banner::class));
    }
}
