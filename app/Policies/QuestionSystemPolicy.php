<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\QuestionSystem;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class QuestionSystemPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSystem::class, 'Index'));
    }

    public function view(User $user, QuestionSystem $questionSystem): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSystem::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSystem::class, 'Store'));
    }

    public function update(User $user, QuestionSystem $questionSystem): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSystem::class, 'Update'));
    }

    public function delete(User $user, QuestionSystem $questionSystem): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSystem::class, 'Delete'));
    }

    public function restore(User $user, QuestionSystem $questionSystem): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSystem::class, 'Restore'));
    }

    public function forceDelete(User $user, QuestionSystem $questionSystem): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSystem::class));
    }
}
