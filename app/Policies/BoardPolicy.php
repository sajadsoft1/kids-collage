<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Board;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class BoardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Board::class, 'Index'));
    }

    public function view(User $user, Board $board): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Board::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Board::class, 'Store'));
    }

    public function update(User $user, Board $board): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Board::class, 'Update'));
    }

    public function delete(User $user, Board $board): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Board::class, 'Delete'));
    }

    public function restore(User $user, Board $board): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Board::class, 'Restore'));
    }

    public function forceDelete(User $user, Board $board): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Board::class));
    }
}
