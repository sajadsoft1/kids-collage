<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\License;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class LicensePolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(License::class, 'Index'));
    }

    public function view(User $user, License $license): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(License::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(License::class, 'Store'));
    }

    public function update(User $user, License $license): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(License::class, 'Update'));
    }

    public function delete(User $user, License $license): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(License::class, 'Delete'));
    }

    public function restore(User $user, License $license): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(License::class, 'Restore'));
    }

    public function forceDelete(User $user, License $license): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(License::class));
    }
}
