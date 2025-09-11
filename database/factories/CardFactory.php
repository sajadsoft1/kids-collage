<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    protected $model = Card::class;

    public function definition(): array
    {
        return [
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Card $model) {});
    }
}
