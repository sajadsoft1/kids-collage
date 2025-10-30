<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\QuestionOption;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class QuestionOptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionOption::class, 'Index'));
    }

    public function view(User $user, QuestionOption $questionOption): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionOption::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionOption::class, 'Store'));
    }

    public function update(User $user, QuestionOption $questionOption): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionOption::class, 'Update'));
    }

    public function delete(User $user, QuestionOption $questionOption): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionOption::class, 'Delete'));
    }

    public function restore(User $user, QuestionOption $questionOption): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionOption::class, 'Restore'));
    }

    public function forceDelete(User $user, QuestionOption $questionOption): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionOption::class));
    }
}
