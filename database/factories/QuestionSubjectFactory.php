<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\QuestionSubject;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionSubjectFactory extends Factory
{
    protected $model = QuestionSubject::class;

    public function definition(): array
    {
        return [
            'languages' => [app()->getLocale()],
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (QuestionSubject $model) {
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
