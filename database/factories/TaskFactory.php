<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'scheduled_for' => $this->faker->dateTimeBetween('-1 week', '+2 weeks'),
            'status' => Task::STATUS_PENDING,
            'completed_at' => null,
        ];
    }
}
