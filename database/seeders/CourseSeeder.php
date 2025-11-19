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
        $row = $data['course_template'][0];
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

        // Get teacher from UserSeeder (first teacher user)
        $teacher = \App\Models\User::where('type', \App\Enums\UserTypeEnum::TEACHER->value)->first();

        if ( ! $teacher) {
            $this->command->warn('No teacher found. Please run UserSeeder first.');

            return;
        }

        // Create courses from data file
        foreach ($data['course'] as $row) {
            StoreCourseAction::run($template, [
                'term_id' => $row['term_id'],
                'teacher_id' => $teacher->id,
                'price' => $row['price'],
                'capacity' => $row['capacity'],
                'sessions' => $row['sessions'],
                'status' => 'active',
            ]);
        }

        // Create additional courses with more sessions for better data
        $term = \App\Models\Term::first();
        $room = \App\Models\Room::first();

        if ($term && $room) {
            // Create 2-3 more courses with different dates
            for ($i = 0; $i < 2; $i++) {
                $sessions = [];
                $startDate = now()->subDays(30 + ($i * 7));

                // Create 4-6 sessions per course
                for ($j = 0; $j < fake()->numberBetween(4, 6); $j++) {
                    $sessionDate = $startDate->copy()->addWeeks($j);
                    $sessions[] = [
                        'course_session_template_id' => fake()->numberBetween(1, 2),
                        'date' => $sessionDate->format('Y-m-d'),
                        'start_time' => fake()->randomElement(['09:00', '10:00', '14:00', '15:00']),
                        'end_time' => fake()->randomElement(['11:00', '12:00', '16:00', '17:00']),
                        'room_id' => $room->id,
                        'meeting_link' => null,
                        'session_type' => \App\Enums\SessionType::IN_PERSON->value,
                        'status' => \App\Enums\SessionStatus::PLANNED->value,
                    ];
                }

                StoreCourseAction::run($template, [
                    'term_id' => $term->id,
                    'teacher_id' => $teacher->id,
                    'price' => fake()->numberBetween(500000, 2000000),
                    'capacity' => fake()->numberBetween(20, 50),
                    'sessions' => $sessions,
                    'status' => 'active',
                ]);
            }
        }

        $this->command->info('✅ Courses created successfully!');
    }
}
