<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CourseSessionTemplate;
use App\Models\CourseTemplate;
use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        // Get some existing course templates and session templates
        $courseTemplates  = CourseTemplate::take(3)->get();
        $sessionTemplates = CourseSessionTemplate::take(5)->get();

        // Create resources for course templates
        foreach ($courseTemplates as $template) {
            // Create 2-4 resources per course template
            Resource::factory()
                ->count(fake()->numberBetween(2, 4))
                ->forCourseTemplate($template)
                ->create();

            // Create some PDF resources specifically
            Resource::factory()
                ->count(fake()->numberBetween(1, 2))
                ->pdf()
                ->forCourseTemplate($template)
                ->create();
        }

        // Create resources for session templates
        foreach ($sessionTemplates as $template) {
            // Create 1-3 resources per session template
            Resource::factory()
                ->count(fake()->numberBetween(1, 3))
                ->forSessionTemplate($template)
                ->create();

            // Create some video resources specifically
            Resource::factory()
                ->count(fake()->numberBetween(0, 2))
                ->video()
                ->forSessionTemplate($template)
                ->create();

            // Create some link resources
            Resource::factory()
                ->count(fake()->numberBetween(0, 1))
                ->link()
                ->forSessionTemplate($template)
                ->create();
        }

        // Create some standalone resources (not attached to any specific model)
        Resource::factory()
            ->count(10)
            ->create();
    }
}
