<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SeoRobotsMetaEnum;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    use GeneralFactoryFunctionsTrait;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug,
            'published' => true,
            'languages' => [app()->getLocale()],
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Category $category) {
            $category->translations()->createMany([
                [
                    'locale' => app()->getLocale(),
                    'key' => 'title',
                    'value' => $this->slugToText($category->slug),
                ],
                [
                    'locale' => app()->getLocale(),
                    'key' => 'description',
                    'value' => $this->faker->realText,
                ],
            ]);

            // Add Seo options
            $category->seoOption()->create([
                'title' => $this->slugToText($category->slug),
                'description' => $this->faker->realText,
                'canonical' => $this->faker->url,
                'old_url' => $this->faker->url,
                'redirect_to' => $this->faker->url,
                'robots_meta' => $this->faker->randomElement(SeoRobotsMetaEnum::values()),
            ]);
        });
    }
}
