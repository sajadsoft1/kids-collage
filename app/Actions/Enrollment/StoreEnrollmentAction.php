<?php

declare(strict_types=1);

namespace App\Actions\Enrollment;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreEnrollmentAction
{
    use AsAction;

    /**
     * @param array{
     *     user_id:int,
     *     course_id:int,
     *     order_item_id?:int|null
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Enrollment
    {
        return DB::transaction(function () use ($payload) {
            $course = Course::findOrFail($payload['course_id']);

            return $course->enrollStudent(
                userId: (int) $payload['user_id'],
                orderItemId: $payload['order_item_id'] ?? null,
            );
        });
    }
}
