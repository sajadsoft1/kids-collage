<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class ClassroomPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Classroom::class, 'Index'));
    }

    public function view(User $user, Classroom $classroom): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Classroom::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Classroom::class, 'Store'));
    }

    public function update(User $user, Classroom $classroom): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Classroom::class, 'Update'));
    }

    public function delete(User $user, Classroom $classroom): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Classroom::class, 'Delete'));
    }

    public function restore(User $user, Classroom $classroom): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Classroom::class, 'Restore'));
    }

    public function forceDelete(User $user, Classroom $classroom): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Classroom::class));
    }
}
