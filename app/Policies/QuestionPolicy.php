<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Question;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class QuestionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Question::class, 'Index'));
    }

    public function view(User $user, Question $question): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Question::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Question::class, 'Store'));
    }

    public function update(User $user, Question $question): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Question::class, 'Update'));
    }

    public function delete(User $user, Question $question): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Question::class, 'Delete'));
    }

    public function restore(User $user, Question $question): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Question::class, 'Restore'));
    }

    public function forceDelete(User $user, Question $question): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Question::class));
    }
}
