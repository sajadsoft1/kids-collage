<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Page;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class PagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class, 'Index'));
    }

    public function view(User $user, Page $page): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class, 'Store'));
    }

    public function update(User $user, Page $page): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class, 'Update'));
    }

    public function delete(User $user, Page $page): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class, 'Delete'));
    }

    public function restore(User $user, Page $page): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class, 'Restore'));
    }

    public function forceDelete(User $user, Page $page): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class));
    }
}
