<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SeoOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeoOptionFactory extends Factory
{
    protected $model = SeoOption::class;

    public function definition(): array
    {
        return [
            'published' => true,
            'languages' => [app()->getLocale()],
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (SeoOption $model) {
            $model->translations()->createMany([
                [
                    'locale' => app()->getLocale(),
                    'key' => 'title',
                    'value' => $this->faker->word(),
                ],
                [
                    'locale' => app()->getLocale(),
                    'key' => 'description',
                    'value' => $this->faker->realText,
                ],
            ]);
        });
    }
}
