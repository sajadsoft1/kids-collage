<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Bulletin;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BulletinFactory extends Factory
{
    protected $model = Bulletin::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'slug'          => Str::slug($title),
            'published'     => $this->faker->boolean(80),
            'published_at'  => $this->faker->dateTimeBetween('-1 year', '+1 month'),
            'user_id'       => 1,
            'category_id'   => Category::where('type', 'bulletin')->inRandomOrder()->first()?->id ?? Category::factory(),
            'view_count'    => $this->faker->numberBetween(0, 1000),
            'comment_count' => $this->faker->numberBetween(0, 50),
            'wish_count'    => $this->faker->numberBetween(0, 100),
            'languages'     => [app()->getLocale()],
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Bulletin $model) {
            $model->translations()->createMany([
                [
                    'locale' => app()->getLocale(),
                    'key'    => 'title',
                    'value'  => $this->faker->sentence(3),
                ],
                [
                    'locale' => app()->getLocale(),
                    'key'    => 'description',
                    'value'  => $this->faker->paragraph(2),
                ],
                [
                    'locale' => app()->getLocale(),
                    'key'    => 'body',
                    'value'  => $this->faker->paragraphs(5, true),
                ],
            ]);
        });
    }
}
