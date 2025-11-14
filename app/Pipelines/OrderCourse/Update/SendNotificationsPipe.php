<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse\Update;

use App\Models\Course;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\OrderCourseInterface;
use Closure;

class SendNotificationsPipe implements OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO
    {
        $order = $dto->getOrder();
        $user = $dto->getUser();

        if ( ! $order) {
            return $next($dto);
        }

        // TODO: Implement notification sending logic
        // Send notification to:
        // 1. Admin
        // 2. User
        // 3. Parent (if exists)
        // 4. Teacher (for enrolled courses)

        // Get course teachers from order items
        $courseIds = $order->items()
            ->where('itemable_type', Course::class)
            ->pluck('itemable_id')
            ->filter();

        if ($courseIds->isNotEmpty()) {
            $courses = Course::whereIn('id', $courseIds)->with('teacher')->get();

            foreach ($courses as $course) {
                // TODO: Send notification to teacher
                // $course->teacher->notify(new OrderUpdatedNotification($order));
            }
        }

        // TODO: Send notification to user
        // $user->notify(new OrderUpdatedNotification($order));

        // TODO: Send notification to parent if exists
        // if ($user->parent) {
        //     $user->parent->notify(new OrderUpdatedNotification($order));
        // }

        // TODO: Send notification to admin
        // Admin::notify(new OrderUpdatedNotification($order));

        return $next($dto);
    }
}
