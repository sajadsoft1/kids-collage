<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Enrollment\StoreEnrollmentAction;
use App\Enums\EnrollmentStatusEnum;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Get user@gmail.com
        $user = User::where('email', 'user@gmail.com')->first();

        if ( ! $user) {
            $this->command->warn('User user@gmail.com not found. Please run UserSeeder first.');

            return;
        }

        // Get active courses
        $courses = Course::where('status', 'active')->get();

        if ($courses->isEmpty()) {
            $this->command->warn('No active courses found. Please run CourseSeeder first.');

            return;
        }

        // Enroll user@gmail.com in first 2 courses
        if ($courses->count() >= 2) {
            // Get first 2 courses
            $firstTwoCourses = $courses->take(2);

            foreach ($firstTwoCourses as $index => $course) {
                try {
                    $enrollment = StoreEnrollmentAction::run([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                    ]);

                    // First course: completed (all sessions done)
                    // Second course: active (in progress)
                    $totalSessions = $course->sessions()->count();
                    $pastSessions = $course->sessions()->where('date', '<=', now())->count();

                    if ($index === 0) {
                        // First course - completed
                        $enrollment->update([
                            'status' => EnrollmentStatusEnum::ACTIVE,
                            'enrolled_at' => Carbon::now()->subDays(30),
                            'progress_percent' => 100,
                        ]);
                    } else {
                        // Second course - in progress
                        $progressPercent = $totalSessions > 0
                            ? round(($pastSessions / $totalSessions) * 100, 2)
                            : 0;

                        $enrollment->update([
                            'status' => EnrollmentStatusEnum::ACTIVE,
                            'enrolled_at' => Carbon::now()->subDays(15),
                            'progress_percent' => $progressPercent,
                        ]);
                    }
                } catch (Exception $e) {
                    // Skip if enrollment already exists
                    continue;
                }
            }
        }

        $this->command->info('✅ Enrollments created successfully!');
    }
}
