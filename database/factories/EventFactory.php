<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'published' => true,
            'languages' => [app()->getLocale()],
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Event $model) {
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
