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
        // Get some existing session templates
        $sessionTemplates = CourseSessionTemplate::take(5)->get();

        if ($sessionTemplates->isEmpty()) {
            return;
        }

        // Create resources and attach them to session templates
        foreach ($sessionTemplates as $template) {
            // Create 2-4 mixed resources per session template
            $resources = Resource::factory()
                ->count(fake()->numberBetween(2, 4))
                ->create();
            
            $template->resources()->attach($resources->pluck('id'));

            // Create some PDF resources specifically
            $pdfResources = Resource::factory()
                ->count(fake()->numberBetween(1, 2))
                ->pdf()
                ->create();
            
            $template->resources()->attach($pdfResources->pluck('id'));

            // Create some video resources specifically
            $videoResources = Resource::factory()
                ->count(fake()->numberBetween(0, 2))
                ->video()
                ->create();
            
            $template->resources()->attach($videoResources->pluck('id'));

            // Create some link resources
            $linkResources = Resource::factory()
                ->count(fake()->numberBetween(0, 1))
                ->link()
                ->create();
            
            $template->resources()->attach($linkResources->pluck('id'));
        }

        // Create some standalone resources (not attached to any template yet)
        Resource::factory()
            ->count(10)
            ->create();
    }
}
