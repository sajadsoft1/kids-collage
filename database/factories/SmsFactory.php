<?php

namespace Database\Factories;
use App\Models\Sms;

use Illuminate\Database\Eloquent\Factories\Factory;

class SmsFactory extends Factory
{
    protected $model = Sms::class;

    public function definition(): array
    {
        return [
            'published' => true,
            'languages'  => [app()->getLocale()]
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Sms $model) {
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
