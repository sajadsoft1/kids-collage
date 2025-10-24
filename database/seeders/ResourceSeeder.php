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
        // Create some standalone resources (not attached to any template yet)
        Resource::factory()
            ->count(10)
            ->create();

        $courseTemplates = CourseTemplate::take(5)->get();
        if ($courseTemplates->isEmpty()) {
            return;
        }
        foreach ($courseTemplates as $template) {
            $courseSessionTemplateIds = $template->sessionTemplates()->pluck('id')->toArray();
            $resource                 = Resource::factory()
                ->count(1)
                ->link()
                ->create()
                ->first();

            $resource->courseSessionTemplates()->attach($courseSessionTemplateIds);
        }
    }
}
