<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class TagPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Tag::class, 'Index'));
    }

    public function view(User $user, Tag $tag): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Tag::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Tag::class, 'Store'));
    }

    public function update(User $user, Tag $tag): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Tag::class, 'Update'));
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Tag::class, 'Delete'));
    }

    public function restore(User $user, Tag $tag): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Tag::class, 'Restore'));
    }

    public function forceDelete(User $user, Tag $tag): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Tag::class));
    }
}
