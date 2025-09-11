<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Board;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoardFactory extends Factory
{
    protected $model = Board::class;

    public function definition(): array
    {
        return [
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Board $model) {});
    }
}
