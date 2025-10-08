<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Course::class, 'Index'));
    }

    public function view(User $user, Course $course): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Course::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Course::class, 'Store'));
    }

    public function update(User $user, Course $course): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Course::class, 'Update'));
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Course::class, 'Delete'));
    }

    public function restore(User $user, Course $course): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Course::class, 'Restore'));
    }

    public function forceDelete(User $user, Course $course): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Course::class));
    }
}
