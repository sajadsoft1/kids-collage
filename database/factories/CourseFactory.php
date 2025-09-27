<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CourseTypeEnum;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 month');
        $endDate   = $this->faker->dateTimeBetween($startDate, '+6 months');

        return [
            'slug'          => $this->faker->unique()->slug(),
            'published'     => $this->faker->boolean(80),
            'published_at'  => $this->faker->optional(0.8)->dateTimeBetween('-1 month', '+1 month'),
            'user_id'       => User::factory(),
            'teacher_id'    => User::factory(),
            'category_id'   => Category::factory(),
            'price'         => $this->faker->randomFloat(2, 100, 5000),
            'type'          => $this->faker->randomElement(CourseTypeEnum::cases()),
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'view_count'    => $this->faker->numberBetween(0, 1000),
            'comment_count' => $this->faker->numberBetween(0, 50),
            'wish_count'    => $this->faker->numberBetween(0, 100),
            'languages'     => [app()->getLocale()],
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Course $model) {
            $model->translations()->createMany([
                [
                    'locale' => app()->getLocale(),
                    'key'    => 'title',
                    'value'  => $this->faker->sentence(3),
                ],
                [
                    'locale' => app()->getLocale(),
                    'key'    => 'description',
                    'value'  => $this->faker->paragraph(2),
                ],
                [
                    'locale' => app()->getLocale(),
                    'key'    => 'body',
                    'value'  => $this->faker->paragraphs(5, true),
                ],
            ]);
        });
    }
}
