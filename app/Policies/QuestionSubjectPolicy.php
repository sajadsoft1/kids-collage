<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\QuestionSubject;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class QuestionSubjectPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSubject::class, 'Index'));
    }

    public function view(User $user, QuestionSubject $questionSubject): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSubject::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSubject::class, 'Store'));
    }

    public function update(User $user, QuestionSubject $questionSubject): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSubject::class, 'Update'));
    }

    public function delete(User $user, QuestionSubject $questionSubject): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSubject::class, 'Delete'));
    }

    public function restore(User $user, QuestionSubject $questionSubject): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSubject::class, 'Restore'));
    }

    public function forceDelete(User $user, QuestionSubject $questionSubject): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionSubject::class));
    }
}
