<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class AttendancePolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Attendance::class, 'Index'));
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Attendance::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Attendance::class, 'Store'));
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Attendance::class, 'Update'));
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Attendance::class, 'Delete'));
    }

    public function restore(User $user, Attendance $attendance): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Attendance::class, 'Restore'));
    }

    public function forceDelete(User $user, Attendance $attendance): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Attendance::class));
    }
}
