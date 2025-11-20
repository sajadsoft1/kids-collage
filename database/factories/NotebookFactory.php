<?php

namespace Database\Factories;
use App\Models\Notebook;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotebookFactory extends Factory
{
    protected $model = Notebook::class;

    public function definition(): array
    {
        return [
            'published' => true,
            'languages'  => [app()->getLocale()]
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Notebook $model) {
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
