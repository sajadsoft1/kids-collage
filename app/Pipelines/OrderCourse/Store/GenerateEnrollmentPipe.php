<?php

declare(strict_types=1);

namespace App\Pipelines\OrderCourse\Store;

use App\Enums\EnrollmentStatusEnum;
use App\Models\Course;
use App\Pipelines\OrderCourse\OrderCourseDTO;
use App\Pipelines\OrderCourse\OrderCourseInterface;
use Closure;

class GenerateEnrollmentPipe implements OrderCourseInterface
{
    public function handle(OrderCourseDTO $dto, Closure $next): OrderCourseDTO
    {
        $order = $dto->getOrder();
        $user  = $dto->getUser();

        if ( ! $order) {
            return $next($dto);
        }

        // Create enrollment for each course in the order
        foreach ($order->items as $item) {
            if ($item->itemable_type === Course::class && $item->itemable_id) {
                // Check if enrollment already exists
                $existingEnrollment = $user?->enrollments()
                    ->where('course_id', $item->itemable_id)
                    ->first();

                if ($existingEnrollment) {
                    // Update existing enrollment with new order_item_id
                    $existingEnrollment->update([
                        'order_item_id' => $item->id,
                        'enrolled_at' => $existingEnrollment->enrolled_at ?? now(),
                    ]);
                    $dto->setEnrollment($existingEnrollment);
                } else {
                    // Create new enrollment
                    $enrollment = $user?->enrollments()->create([
                        'course_id' => $item->itemable_id,
                        'status' => EnrollmentStatusEnum::ACTIVE->value,
                        'order_item_id' => $item->id,
                        'enrolled_at' => now(),
                    ]);
                    $dto->setEnrollment($enrollment);
                }
            }
        }

        return $next($dto);
    }
}
