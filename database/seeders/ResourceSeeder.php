<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CourseTemplate;
use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $courseTemplates = CourseTemplate::all();

        if ($courseTemplates->isEmpty()) {
            $this->command->warn('No course templates found. Please run CourseSeeder first.');

            return;
        }

        // Create resources for each course template
        foreach ($courseTemplates as $template) {
            $sessionTemplates = $template->sessionTemplates;

            // Create a PDF resource for each session template
            foreach ($sessionTemplates as $sessionTemplate) {
                $resource = Resource::factory()
                    ->pdf()
                    ->create([
                        'title' => 'جزوه جلسه ' . $sessionTemplate->order . ' - ' . $template->title,
                        'description' => 'جزوه آموزشی جلسه ' . $sessionTemplate->order,
                    ]);

                // Attach resource to session template
                $resource->courseSessionTemplates()->attach($sessionTemplate->id);
            }

            // Create a video resource for the course template (general course material)
            if ($sessionTemplates->isNotEmpty()) {
                $videoResource = Resource::factory()
                    ->video()
                    ->create([
                        'title' => 'ویدیو آموزشی - ' . $template->title,
                        'description' => 'ویدیو آموزشی کلی دوره',
                    ]);

                // Attach to first session template as example
                $videoResource->courseSessionTemplates()->attach($sessionTemplates->first()->id);
            }
        }

        $this->command->info('✅ Resources created successfully!');
    }
}
