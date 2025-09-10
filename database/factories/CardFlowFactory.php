<?php

namespace Database\Factories;

use App\Models\CardFlow;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFlowFactory extends Factory
{

    protected $model = CardFlow::class;
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (CardFlow $model) {
            //
        });
    }
}
