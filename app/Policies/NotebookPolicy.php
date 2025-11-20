<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Notebook;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class NotebookPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Notebook::class, 'Index'));
    }

    public function view(User $user, Notebook $notebook): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Notebook::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Notebook::class, 'Store'));
    }

    public function update(User $user, Notebook $notebook): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Notebook::class, 'Update'));
    }

    public function delete(User $user, Notebook $notebook): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Notebook::class, 'Delete'));
    }

    public function restore(User $user, Notebook $notebook): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Notebook::class, 'Restore'));
    }

    public function forceDelete(User $user, Notebook $notebook): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Notebook::class));
    }
}
