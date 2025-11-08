<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AttemptStatusEnum;
use App\Enums\ExamStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamAttemptFactory extends Factory
{
    protected $model = ExamAttempt::class;

    public function definition(): array
    {
        $startedAt = fake()->dateTimeBetween('-1 month', 'now');
        $status    = fake()->randomElement(AttemptStatusEnum::cases());

        $completedAt = $status->isFinished()
            ? fake()->dateTimeBetween($startedAt, 'now')
            : null;

        return [
            'exam_id'      => Exam::where('status', ExamStatusEnum::PUBLISHED->value)->inRandomOrder()->first()->id,
            'user_id'      => User::where('type', UserTypeEnum::USER->value)->inRandomOrder()->first()->id,
            'started_at'   => $startedAt,
            'completed_at' => $completedAt,
            'total_score'  => $completedAt ? fake()->randomFloat(2, 0, 100) : null,
            'percentage'   => $completedAt ? fake()->randomFloat(2, 0, 100) : null,
            'status'       => $status,
            'ip_address'   => fake()->ipv4(),
            'user_agent'   => fake()->userAgent(),
            'metadata'     => [],
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'       => AttemptStatusEnum::COMPLETED,
            'completed_at' => now(),
            'total_score'  => fake()->randomFloat(2, 50, 100),
            'percentage'   => fake()->randomFloat(2, 50, 100),
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'       => AttemptStatusEnum::IN_PROGRESS,
            'completed_at' => null,
            'total_score'  => null,
            'percentage'   => null,
        ]);
    }
}
