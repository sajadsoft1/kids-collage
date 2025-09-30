<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CourseTemplate;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class CourseTemplatePolicy
{

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplate::class, 'Index'));
    }

    public function view(User $user, CourseTemplate $courseTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplate::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplate::class, 'Store'));
    }

    public function update(User $user, CourseTemplate $courseTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplate::class, 'Update'));
    }

    public function delete(User $user, CourseTemplate $courseTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplate::class, 'Delete'));
    }

    public function restore(User $user, CourseTemplate $courseTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplate::class, 'Restore'));
    }

    public function forceDelete(User $user, CourseTemplate $courseTemplate): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplate::class));
    }
}
