<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class CommentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class, 'Index'));
    }

    public function view(User $user, Comment $comment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class, 'Store'));
    }

    public function update(User $user, Comment $comment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class, 'Update'));
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class, 'Delete'));
    }

    public function restore(User $user, Comment $comment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class, 'Restore'));
    }

    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Comment::class));
    }
}
