<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\QuestionCompetency;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class QuestionCompetencyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionCompetency::class, 'Index'));
    }

    public function view(User $user, QuestionCompetency $questionCompetency): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionCompetency::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionCompetency::class, 'Store'));
    }

    public function update(User $user, QuestionCompetency $questionCompetency): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionCompetency::class, 'Update'));
    }

    public function delete(User $user, QuestionCompetency $questionCompetency): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionCompetency::class, 'Delete'));
    }

    public function restore(User $user, QuestionCompetency $questionCompetency): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionCompetency::class, 'Restore'));
    }

    public function forceDelete(User $user, QuestionCompetency $questionCompetency): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(QuestionCompetency::class));
    }
}
