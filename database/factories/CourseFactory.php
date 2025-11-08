<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CourseStatusEnum;
use App\Enums\CourseTypeEnum;
use App\Enums\UserTypeEnum;
use App\Models\Course;
use App\Models\CourseTemplate;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'course_template_id' => CourseTemplate::factory(),
            'term_id'            => Term::factory(),
            'teacher_id'         => User::where('type', UserTypeEnum::TEACHER->value)->inRandomOrder()->first()->id,
            'capacity'           => $this->faker->optional()->numberBetween(10, 50),
            'price'              => $this->faker->randomFloat(2, 100, 5000),
            'type'               => $this->faker->randomElement(array_map(fn ($c) => $c->value, CourseTypeEnum::cases())),
            'status'             => CourseStatusEnum::DRAFT->value,
            'days_of_week'       => $this->faker->randomElements([0, 1, 2, 3, 4, 5, 6], $this->faker->numberBetween(2, 4)),
            'start_time'         => '09:00',
            'end_time'           => '10:30',
            'room_id'            => null,
            'meeting_link'       => null,
        ];
    }
    // No translations at the course row level in the new schema
}
