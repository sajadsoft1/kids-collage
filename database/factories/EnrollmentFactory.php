<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EnrollmentStatusEnum;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    public function definition(): array
    {
        return [
            'user_id'          => User::factory(),
            'course_id'        => Course::factory(),
            'order_item_id'    => null,
            'status'           => EnrollmentStatusEnum::PENDING->value,
            'enrolled_at'      => now(),
            'progress_percent' => 0,
        ];
    }
}
