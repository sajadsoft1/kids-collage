<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class BlogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Blog::class, 'Index'));
    }

    public function view(User $user, Blog $blog): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Blog::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Blog::class, 'Store'));
    }

    public function update(User $user, Blog $blog): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Blog::class, 'Update'));
    }

    public function delete(User $user, Blog $blog): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Blog::class, 'Delete'));
    }

    public function restore(User $user, Blog $blog): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Blog::class, 'Restore'));
    }

    public function forceDelete(User $user, Blog $blog): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Blog::class));
    }
}
