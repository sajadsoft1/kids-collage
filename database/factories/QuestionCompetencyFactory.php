<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\QuestionCompetency;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionCompetencyFactory extends Factory
{
    protected $model = QuestionCompetency::class;

    public function definition(): array
    {
        return [
            'languages' => [app()->getLocale()],
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (QuestionCompetency $model) {
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
