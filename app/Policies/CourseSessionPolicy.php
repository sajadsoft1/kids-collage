<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CourseSession;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class CourseSessionPolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSession::class, 'Index'));
    }

    public function view(User $user, CourseSession $courseSession): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSession::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSession::class, 'Store'));
    }

    public function update(User $user, CourseSession $courseSession): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSession::class, 'Update'));
    }

    public function delete(User $user, CourseSession $courseSession): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSession::class, 'Delete'));
    }

    public function restore(User $user, CourseSession $courseSession): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSession::class, 'Restore'));
    }

    public function forceDelete(User $user, CourseSession $courseSession): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSession::class));
    }
}
