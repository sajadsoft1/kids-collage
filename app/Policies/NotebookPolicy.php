<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserTypeEnum;
use App\Models\Notebook;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class NotebookPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Notebook::class, 'Index')) ||
            in_array($user->type, [UserTypeEnum::USER, UserTypeEnum::PARENT]);
    }

    public function view(User $user, Notebook $notebook): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Notebook::class, 'Show')) ||
            in_array($user->type, [UserTypeEnum::USER, UserTypeEnum::PARENT]);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Notebook::class, 'Store')) ||
            $user->type == UserTypeEnum::USER;
    }

    public function update(User $user, Notebook $notebook): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Notebook::class, 'Update')) ||
            $user->id == $notebook->user_id;
    }

    public function delete(User $user, Notebook $notebook): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Notebook::class, 'Delete')) ||
            $user->id == $notebook->user_id;
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
