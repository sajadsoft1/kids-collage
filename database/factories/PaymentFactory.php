<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'published' => true,
            'languages' => [app()->getLocale()],
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Payment $model) {
            $model->translations()->createMany([
                [
                    'locale' => app()->getLocale(),
                    'key'    => 'title',
                    'value'  => $this->faker->word(),
                ],
                [
                    'locale' => app()->getLocale(),
                    'key'    => 'description',
                    'value'  => $this->faker->realText,
                ],
            ]);
        });
    }
}
