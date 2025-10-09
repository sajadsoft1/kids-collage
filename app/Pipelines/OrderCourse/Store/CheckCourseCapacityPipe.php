<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse\Store;

use App\Models\Course;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\OrderCourseInterface;
use Closure;

class CheckCourseCapacityPipe implements OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO
    {
        foreach ($dto->getItems() as $item) {
            if (($item['itemable_type'] ?? null) === Course::class) {
                $courseId = $item['itemable_id'] ?? null;

                if ($courseId) {
                    $course = Course::find($courseId);

                    if ($course && $course->capacity) {
                        $currentEnrollments = $course->enrollments()->count();

                        abort_if(
                            $currentEnrollments >= $course->capacity,
                            400,
                            trans('order.exceptions.course_full', ['course' => $course->template->title ?? 'Course'])
                        );
                    }
                }
            }
        }

        return $next($dto);
    }
}
