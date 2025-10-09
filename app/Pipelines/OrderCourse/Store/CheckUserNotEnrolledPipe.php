<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse\Store;

use App\Models\Course;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\OrderCourseInterface;
use Closure;

class CheckUserNotEnrolledPipe implements OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO
    {
        // Skip check if force parameter is true (admin override)
        if ($dto->getFromPayload('force')) {
            return $next($dto);
        }

        $user  = $dto->getUser();
        $items = $dto->getItems();

        // Check each course item
        foreach ($items as $item) {
            if (($item['itemable_type'] ?? null) === Course::class) {
                $courseId = $item['itemable_id'] ?? null;

                if ($courseId) {
                    $course = Course::find($courseId);

                    if ($course) {
                        $dto->setCourse($course);

                        // Check if user is already enrolled
                        $alreadyEnrolled = $user->enrollments()
                            ->where('course_id', $courseId)
                            ->exists();

                        abort_if(
                            $alreadyEnrolled,
                            400,
                            trans('order.exceptions.user_already_enrolled', ['course' => $course->template->title ?? 'Course'])
                        );
                    }
                }
            }
        }

        return $next($dto);
    }
}
