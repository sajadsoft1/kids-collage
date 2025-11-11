<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ExamStatusEnum;
use App\Enums\ExamTypeEnum;
use App\Models\Category;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition(): array
    {
        $type = fake()->randomElement(ExamTypeEnum::cases());
        $totalScore = $type === ExamTypeEnum::SCORED ? fake()->numberBetween(50, 200) : null;

        return [
            'title' => fake()->sentence(),
            'description' => fake()->optional()->paragraph(),
            'category_id' => Category::factory(),
            'type' => $type,
            'total_score' => $totalScore,
            'duration' => fake()->optional()->numberBetween(30, 180),
            'pass_score' => $totalScore ? $totalScore * 0.6 : null,
            'max_attempts' => fake()->optional()->numberBetween(1, 5),
            'shuffle_questions' => fake()->boolean(),
            'show_results' => fake()->randomElement(['immediate', 'after_submit', 'manual', 'never']),
            'allow_review' => fake()->boolean(70),
            'settings' => [],
            'starts_at' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'ends_at' => fake()->optional()->dateTimeBetween('+1 month', '+3 months'),
            'status' => fake()->randomElement(ExamStatusEnum::cases()),
            'created_by' => 1,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExamStatusEnum::PUBLISHED,
            'starts_at' => now()->subDays(1),
            'ends_at' => now()->addDays(30),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExamStatusEnum::DRAFT,
        ]);
    }

    public function scored(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ExamTypeEnum::SCORED,
            'total_score' => 100,
            'pass_score' => 60,
        ]);
    }

    public function survey(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ExamTypeEnum::SURVEY,
            'total_score' => null,
            'pass_score' => null,
        ]);
    }
}
