<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class EnrollmentPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Enrollment::class, 'Index'));
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Enrollment::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Enrollment::class, 'Store'));
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Enrollment::class, 'Update'));
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Enrollment::class, 'Delete'));
    }

    public function restore(User $user, Enrollment $enrollment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Enrollment::class, 'Restore'));
    }

    public function forceDelete(User $user, Enrollment $enrollment): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Enrollment::class));
    }
}
