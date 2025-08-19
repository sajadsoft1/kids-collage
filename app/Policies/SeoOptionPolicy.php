<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SeoOption;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class SeoOptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SeoOption::class, 'Index'));
    }

    public function view(User $user, SeoOption $seoOption): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SeoOption::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SeoOption::class, 'Store'));
    }

    public function update(User $user, SeoOption $seoOption): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SeoOption::class, 'Update'));
    }

    public function delete(User $user, SeoOption $seoOption): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SeoOption::class, 'Delete'));
    }

    public function restore(User $user, SeoOption $seoOption): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SeoOption::class, 'Restore'));
    }

    public function forceDelete(User $user, SeoOption $seoOption): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SeoOption::class));
    }
}
