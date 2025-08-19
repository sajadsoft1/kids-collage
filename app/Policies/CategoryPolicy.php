<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Index'));
    }

    public function view(User $user, Category $category): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Store'));
    }

    public function update(User $user, Category $category): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Update'));
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Delete'));
    }

    public function restore(User $user, Category $category): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class, 'Restore'));
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Category::class));
    }
}
