<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Enrollment\StoreEnrollmentAction;
use App\Enums\EnrollmentStatusEnum;
use App\Enums\UserTypeEnum;
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
        // Get students from UserSeeder (USER type)
        $students = User::where('type', UserTypeEnum::USER->value)->get();

        if ($students->isEmpty()) {
            $this->command->warn('No students found. Please run UserSeeder first.');

            return;
        }

        // Get active courses
        $courses = Course::where('status', 'active')->get();

        if ($courses->isEmpty()) {
            $this->command->warn('No active courses found. Please run CourseSeeder first.');

            return;
        }

        // Create enrollments for each student in available courses
        foreach ($students as $student) {
            // Enroll student in 1-3 random courses
            $coursesToEnroll = $courses->random(min(3, $courses->count()));

            foreach ($coursesToEnroll as $course) {
                try {
                    $enrollment = StoreEnrollmentAction::run([
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                    ]);

                    // Update enrollment status and progress randomly
                    $status = fake()->randomElement([
                        EnrollmentStatusEnum::ACTIVE,
                        EnrollmentStatusEnum::ACTIVE,
                        EnrollmentStatusEnum::PENDING,
                    ]);

                    $enrollment->update([
                        'status' => $status,
                        'enrolled_at' => Carbon::now()->subDays(fake()->numberBetween(1, 30)),
                        'progress_percent' => $status === EnrollmentStatusEnum::ACTIVE
                            ? fake()->randomFloat(2, 0, 100)
                            : 0,
                    ]);
                } catch (Exception $e) {
                    // Skip if enrollment already exists or course is at capacity
                    continue;
                }
            }
        }

        $this->command->info('✅ Enrollments created successfully!');
    }
}
