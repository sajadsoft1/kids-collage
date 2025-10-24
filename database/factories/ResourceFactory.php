<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ResourceType;
use App\Models\CourseSessionTemplate;
use App\Models\CourseTemplate;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(ResourceType::cases());

        return [
            'resourceable_type' => $this->faker->randomElement([
                CourseTemplate::class,
                CourseSessionTemplate::class,
            ]),
            'resourceable_id'   => $this->faker->numberBetween(1, 10),
            'type'              => $type,
            'path'              => $type === ResourceType::LINK
                ? $this->faker->url()
                : '/storage/resources/' . $this->faker->uuid() . '.' . $this->getFileExtension($type),
            'title'             => $this->faker->sentence(3),
            'description'       => $this->faker->paragraph(),
            'order'             => $this->faker->numberBetween(0, 100),
            'is_public'         => $this->faker->boolean(80), // 80% chance of being public
            'extra_attributes'  => $this->generateExtraAttributes($type),
        ];
    }

    private function getFileExtension(ResourceType $type): string
    {
        return match ($type) {
            ResourceType::PDF   => 'pdf',
            ResourceType::VIDEO => 'mp4',
            ResourceType::IMAGE => $this->faker->randomElement(['jpg', 'png', 'gif']),
            ResourceType::AUDIO => 'mp3',
            ResourceType::FILE  => $this->faker->randomElement(['doc', 'docx', 'txt', 'zip']),
            ResourceType::LINK  => '',
        };
    }

    private function generateExtraAttributes(ResourceType $type): array
    {
        $attributes = [];

        if ($type !== ResourceType::LINK) {
            $attributes['file_size'] = $this->faker->numberBetween(1024, 10485760); // 1KB to 10MB
            $attributes['mime_type'] = $this->getMimeType($type);
        }

        if ($type === ResourceType::VIDEO) {
            $attributes['duration']       = $this->faker->numberBetween(60, 3600); // 1 minute to 1 hour
            $attributes['thumbnail_path'] = '/storage/thumbnails/' . $this->faker->uuid() . '.jpg';
        }

        if ($type === ResourceType::IMAGE) {
            $attributes['thumbnail_path'] = '/storage/thumbnails/' . $this->faker->uuid() . '.jpg';
        }

        return $attributes;
    }

    private function getMimeType(ResourceType $type): string
    {
        return match ($type) {
            ResourceType::PDF   => 'application/pdf',
            ResourceType::VIDEO => 'video/mp4',
            ResourceType::IMAGE => $this->faker->randomElement(['image/jpeg', 'image/png', 'image/gif']),
            ResourceType::AUDIO => 'audio/mpeg',
            ResourceType::FILE  => $this->faker->randomElement([
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'text/plain',
                'application/zip',
            ]),
            ResourceType::LINK  => '',
        };
    }

    public function forCourseTemplate(CourseTemplate $template): static
    {
        return $this->state(fn (array $attributes) => [
            'resourceable_type' => CourseTemplate::class,
            'resourceable_id'   => $template->id,
        ]);
    }

    public function forSessionTemplate(CourseSessionTemplate $template): static
    {
        return $this->state(fn (array $attributes) => [
            'resourceable_type' => CourseSessionTemplate::class,
            'resourceable_id'   => $template->id,
        ]);
    }

    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'             => ResourceType::PDF,
            'path'             => '/storage/resources/' . $this->faker->uuid() . '.pdf',
            'extra_attributes' => [
                'file_size' => $this->faker->numberBetween(102400, 5242880), // 100KB to 5MB
                'mime_type' => 'application/pdf',
            ],
        ]);
    }

    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'             => ResourceType::VIDEO,
            'path'             => '/storage/resources/' . $this->faker->uuid() . '.mp4',
            'extra_attributes' => [
                'file_size'      => $this->faker->numberBetween(1048576, 104857600), // 1MB to 100MB
                'mime_type'      => 'video/mp4',
                'duration'       => $this->faker->numberBetween(60, 3600),
                'thumbnail_path' => '/storage/thumbnails/' . $this->faker->uuid() . '.jpg',
            ],
        ]);
    }

    public function link(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'             => ResourceType::LINK,
            'path'             => $this->faker->url(),
            'extra_attributes' => [],
        ]);
    }
}
