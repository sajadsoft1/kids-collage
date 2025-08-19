<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SocialMedia;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class SocialMediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SocialMedia::class, 'Index'));
    }

    public function view(User $user, SocialMedia $socialMedia): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SocialMedia::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SocialMedia::class, 'Store'));
    }

    public function update(User $user, SocialMedia $socialMedia): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SocialMedia::class, 'Update'));
    }

    public function delete(User $user, SocialMedia $socialMedia): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SocialMedia::class, 'Delete'));
    }

    public function restore(User $user, SocialMedia $socialMedia): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SocialMedia::class, 'Restore'));
    }

    public function forceDelete(User $user, SocialMedia $socialMedia): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(SocialMedia::class));
    }
}
