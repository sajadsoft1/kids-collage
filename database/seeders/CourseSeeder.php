<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Course\StoreCourseAction;
use App\Actions\CourseTemplate\StoreCourseTemplateAction;
use App\Helpers\StringHelper;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno_lms.php');

        // Get teacher from UserSeeder (first teacher user)
        $teacher = \App\Models\User::where('type', \App\Enums\UserTypeEnum::TEACHER->value)->first();

        if ( ! $teacher) {
            $this->command->warn('No teacher found. Please run UserSeeder first.');

            return;
        }

        // Get term from TermSeeder
        $term = \App\Models\Term::first();

        if ( ! $term) {
            $this->command->warn('No term found. Please run TermSeeder first.');

            return;
        }

        // Create all course templates from data file
        $templates = [];
        foreach ($data['course_template'] as $templateData) {
            $template = StoreCourseTemplateAction::run([
                'slug' => StringHelper::slug($templateData['title']),
                'title' => $templateData['title'],
                'description' => $templateData['description'],
                'body' => $templateData['body'] ?? '',
                'category_id' => $templateData['category_id'] ?? null,
                'level' => $templateData['level'],
                'type' => $templateData['type'],
                'sessions' => $templateData['sessions'],
                'tags' => $templateData['tags'] ?? [],
                'prerequisites' => $templateData['prerequisites'] ?? [],
                'is_self_paced' => $templateData['is_self_paced'] ?? false,
            ]);
            $templates[] = $template;
        }

        // Create courses from data file
        $courseIndex = 0;
        foreach ($data['course'] as $courseData) {
            // Use template based on course index (cycle through templates)
            $template = $templates[$courseIndex % count($templates)];

            StoreCourseAction::run($template, [
                'term_id' => $courseData['term_id'] ?? $term->id,
                'teacher_id' => $teacher->id,
                'price' => $courseData['price'],
                'capacity' => $courseData['capacity'],
                'sessions' => $courseData['sessions'],
                'status' => 'active',
            ]);

            $courseIndex++;
        }

        $this->command->info('✅ Courses created successfully!');
    }
}
