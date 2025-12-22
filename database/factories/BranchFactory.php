<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BranchStatusEnum;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('BR##??'),
            'name' => $this->faker->company(),
            'status' => BranchStatusEnum::ACTIVE,
            'is_default' => false,
            'settings' => [],
            'languages' => [
                'fa' => [
                    'name' => $this->faker->company(),
                ],
            ],
        ];
    }

    /** Indicate that the branch is inactive. */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BranchStatusEnum::INACTIVE,
        ]);
    }

    /** Indicate that the branch is the default branch. */
    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }
}
