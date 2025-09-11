<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TicketMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketMessageFactory extends Factory
{
    protected $model = TicketMessage::class;

    public function definition(): array
    {
        return [
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (TicketMessage $model) {});
    }
}
