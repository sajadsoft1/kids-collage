<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class ExamPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Exam::class, 'Index'));
    }

    public function view(User $user, Exam $exam): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Exam::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Exam::class, 'Store'));
    }

    public function update(User $user, Exam $exam): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Exam::class, 'Update'));
    }

    public function delete(User $user, Exam $exam): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Exam::class, 'Delete'));
    }

    public function restore(User $user, Exam $exam): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Exam::class, 'Restore'));
    }

    public function forceDelete(User $user, Exam $exam): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Exam::class));
    }
}
