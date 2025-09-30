<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CourseSessionTemplate;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class CourseSessionTemplatePolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSessionTemplate::class, 'Index'));
    }

    public function view(User $user, CourseSessionTemplate $courseSessionTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSessionTemplate::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSessionTemplate::class, 'Store'));
    }

    public function update(User $user, CourseSessionTemplate $courseSessionTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSessionTemplate::class, 'Update'));
    }

    public function delete(User $user, CourseSessionTemplate $courseSessionTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSessionTemplate::class, 'Delete'));
    }

    public function restore(User $user, CourseSessionTemplate $courseSessionTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSessionTemplate::class, 'Restore'));
    }

    public function forceDelete(User $user, CourseSessionTemplate $courseSessionTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseSessionTemplate::class));
    }
}
