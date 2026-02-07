<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CourseTemplateLevel;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class CourseTemplateLevelPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplateLevel::class, 'Index'));
    }

    public function view(User $user, CourseTemplateLevel $courseTemplateLevel): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplateLevel::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplateLevel::class, 'Store'));
    }

    public function update(User $user, CourseTemplateLevel $courseTemplateLevel): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplateLevel::class, 'Update'));
    }

    public function delete(User $user, CourseTemplateLevel $courseTemplateLevel): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplateLevel::class, 'Delete'));
    }

    public function restore(User $user, CourseTemplateLevel $courseTemplateLevel): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplateLevel::class, 'Restore'));
    }

    public function forceDelete(User $user, CourseTemplateLevel $courseTemplateLevel): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(CourseTemplateLevel::class));
    }
}
