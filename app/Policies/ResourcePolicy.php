<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\EnrollmentStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\Resource;
use App\Models\User;
use App\Services\Permissions\PermissionsService;

class ResourcePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class, 'Index'));
    }

    public function view(User $user, Resource $resource): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class, 'Show'));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class, 'Store'));
    }

    public function update(User $user, Resource $resource): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class, 'Update'));
    }

    public function delete(User $user, Resource $resource): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class, 'Delete'));
    }

    public function restore(User $user, Resource $resource): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class, 'Restore'));
    }

    public function forceDelete(User $user, Resource $resource): bool
    {
        return $user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Resource::class));
    }

    /**
     * Check if user can download/access a resource.
     * Allows access for:
     * - Public resources (everyone)
     * - Course teachers
     * - Enrolled students
     * - Parents of enrolled students
     */
    public function download(User $user, Resource $resource): bool
    {
        // Public resources are accessible to everyone
        if ($resource->is_public) {
            return true;
        }

        // Get all courses that this resource is attached to
        // Resource -> CourseSessionTemplate -> CourseTemplate -> Course
        $sessionTemplates = $resource->courseSessionTemplates()
            ->with('courseTemplate.courses')
            ->get();

        $courses = $sessionTemplates->flatMap(function ($template) {
            return $template->courseTemplate->courses;
        })->unique('id');

        foreach ($courses as $course) {
            // Check if user is the teacher of this course
            if ($course->teacher_id === $user->id) {
                return true;
            }

            // Check if user is enrolled in this course (student)
            if ($user->type === UserTypeEnum::USER) {
                $enrollment = $course->enrollments()
                    ->where('user_id', $user->id)
                    ->where('status', '!=', EnrollmentStatusEnum::DROPPED->value)
                    ->first();

                if ($enrollment) {
                    return true;
                }
            }

            // Check if user is a parent of a student enrolled in this course
            if ($user->type === UserTypeEnum::PARENT) {
                $childIds = $user->children()->pluck('id');
                $hasEnrolledChild = $course->enrollments()
                    ->whereIn('user_id', $childIds)
                    ->where('status', '!=', EnrollmentStatusEnum::DROPPED->value)
                    ->exists();

                if ($hasEnrolledChild) {
                    return true;
                }
            }
        }

        return false;
    }
}
