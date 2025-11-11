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
        $data     = require database_path('seeders/data/karno_lms.php');
        $row      = $data['course_template'][0];
        $template = StoreCourseTemplateAction::run([
            'slug' => StringHelper::slug($row['title']),
            'title' => $row['title'],
            'description' => $row['description'],
            'body' => $row['body'],
            'category_id' => $row['category_id'],
            'level' => $row['level'],
            'type' => $row['type'],
            'sessions' => $row['sessions'],
        ]);

        foreach ($data['course'] as $row) {
            StoreCourseAction::run($template, [
                'term_id' => $row['term_id'],
                'teacher_id' => $row['teacher_id'],
                'price' => $row['price'],
                'capacity' => $row['capacity'],
                'sessions' => $row['sessions'],
                'status' => 'active',
            ]);
        }
    }
}
