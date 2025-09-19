<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\News;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class NewsPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(News::class, 'Index'));
    }

    public function view(User $user, News $news): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(News::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(News::class, 'Store'));
    }

    public function update(User $user, News $news): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(News::class, 'Update'));
    }

    public function delete(User $user, News $news): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(News::class, 'Delete'));
    }

    public function restore(User $user, News $news): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(News::class, 'Restore'));
    }

    public function forceDelete(User $user, News $news): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(News::class));
    }
}
