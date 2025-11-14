<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\License;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LicenseFactory extends Factory
{
    protected $model = License::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'slug' => Str::slug($title),
            'view_count' => $this->faker->numberBetween(0, 1000),
            'languages' => [app()->getLocale()],
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (License $model) {
            $model->translations()->createMany([
                [
                    'locale' => app()->getLocale(),
                    'key' => 'title',
                    'value' => $this->faker->sentence(3),
                ],
                [
                    'locale' => app()->getLocale(),
                    'key' => 'description',
                    'value' => $this->faker->paragraph(2),
                ],
            ]);
        });
    }
}
