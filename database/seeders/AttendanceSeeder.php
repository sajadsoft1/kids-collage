<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\EnrollmentStatusEnum;
use App\Models\Attendance;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Get active enrollments
        $enrollments = Enrollment::where('status', EnrollmentStatusEnum::ACTIVE)->get();

        if ($enrollments->isEmpty()) {
            $this->command->warn('No active enrollments found. Please run EnrollmentSeeder first.');

            return;
        }

        // Create attendance records for each enrollment
        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;

            // Get sessions for this course
            $sessions = $course->sessions()->where('date', '<=', now())->get();

            if ($sessions->isEmpty()) {
                continue;
            }

            // Create attendance for 70-90% of sessions (some absences)
            $sessionsToAttend = $sessions->random((int) ceil($sessions->count() * fake()->randomFloat(2, 0.7, 0.9)));

            foreach ($sessionsToAttend as $session) {
                // Skip if attendance already exists
                if (Attendance::where('enrollment_id', $enrollment->id)
                    ->where('course_session_id', $session->id)
                    ->exists()) {
                    continue;
                }

                $isPresent = fake()->boolean(85); // 85% chance of being present

                $arrivalTime = null;
                $leaveTime = null;

                if ($isPresent && $session->start_time && $session->end_time) {
                    // Arrival time: 0-10 minutes before or after session start
                    $arrivalOffset = fake()->numberBetween(-10, 10);
                    $arrivalTime = Carbon::parse($session->date->format('Y-m-d') . ' ' . $session->start_time->format('H:i:s'))
                        ->addMinutes($arrivalOffset);

                    // Leave time: 0-10 minutes before or after session end
                    $leaveOffset = fake()->numberBetween(-10, 10);
                    $leaveTime = Carbon::parse($session->date->format('Y-m-d') . ' ' . $session->end_time->format('H:i:s'))
                        ->addMinutes($leaveOffset);
                }

                Attendance::create([
                    'enrollment_id' => $enrollment->id,
                    'course_session_id' => $session->id,
                    'present' => $isPresent,
                    'arrival_time' => $arrivalTime,
                    'leave_time' => $leaveTime,
                    'excuse_note' => $isPresent ? null : (fake()->boolean(30) ? fake()->sentence() : null),
                ]);
            }
        }

        $this->command->info('✅ Attendance records created successfully!');
    }
}
