<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\EnrollmentStatusEnum;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
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

        // Get enrollments for user@gmail.com
        $userEnrollments = Enrollment::where('user_id', $user->id)
            ->where('status', EnrollmentStatusEnum::ACTIVE)
            ->orderBy('id')
            ->get();

        if ($userEnrollments->isEmpty()) {
            $this->command->warn('No active enrollments found for user@gmail.com. Please run EnrollmentSeeder first.');

            return;
        }

        foreach ($userEnrollments as $index => $enrollment) {
            $course = $enrollment->course;

            // Get past sessions for this course
            $pastSessions = $course->sessions()
                ->where('date', '<=', now())
                ->orderBy('date')
                ->get();

            if ($pastSessions->isEmpty()) {
                continue;
            }

            // First course: all sessions present + 1 absence
            if ($index === 0) {
                $absenceIndex = fake()->numberBetween(0, $pastSessions->count() - 1);

                foreach ($pastSessions as $sessionIndex => $session) {
                    // Skip if attendance already exists
                    if (Attendance::where('enrollment_id', $enrollment->id)
                        ->where('course_session_id', $session->id)
                        ->exists()) {
                        continue;
                    }

                    // All present except one
                    $isPresent = $sessionIndex !== $absenceIndex;

                    $arrivalTime = null;
                    $leaveTime = null;

                    if ($isPresent && $session->start_time && $session->end_time) {
                        // Arrival time: 0-5 minutes before or after session start
                        $arrivalOffset = fake()->numberBetween(-5, 5);
                        $arrivalTime = Carbon::parse($session->date->format('Y-m-d') . ' ' . $session->start_time->format('H:i:s'))
                            ->addMinutes($arrivalOffset);

                        // Leave time: 0-5 minutes before or after session end
                        $leaveOffset = fake()->numberBetween(-5, 5);
                        $leaveTime = Carbon::parse($session->date->format('Y-m-d') . ' ' . $session->end_time->format('H:i:s'))
                            ->addMinutes($leaveOffset);
                    }

                    Attendance::create([
                        'enrollment_id' => $enrollment->id,
                        'course_session_id' => $session->id,
                        'present' => $isPresent,
                        'arrival_time' => $arrivalTime,
                        'leave_time' => $leaveTime,
                        'excuse_note' => $isPresent ? null : 'غیبت',
                    ]);
                }
            }
            // Second course: in progress (only record attendance for past sessions)
            else {
                foreach ($pastSessions as $session) {
                    // Skip if attendance already exists
                    if (Attendance::where('enrollment_id', $enrollment->id)
                        ->where('course_session_id', $session->id)
                        ->exists()) {
                        continue;
                    }

                    // 80% chance of being present for in-progress course
                    $isPresent = fake()->boolean(80);

                    $arrivalTime = null;
                    $leaveTime = null;

                    if ($isPresent && $session->start_time && $session->end_time) {
                        // Arrival time: 0-5 minutes before or after session start
                        $arrivalOffset = fake()->numberBetween(-5, 5);
                        $arrivalTime = Carbon::parse($session->date->format('Y-m-d') . ' ' . $session->start_time->format('H:i:s'))
                            ->addMinutes($arrivalOffset);

                        // Leave time: 0-5 minutes before or after session end
                        $leaveOffset = fake()->numberBetween(-5, 5);
                        $leaveTime = Carbon::parse($session->date->format('Y-m-d') . ' ' . $session->end_time->format('H:i:s'))
                            ->addMinutes($leaveOffset);
                    }

                    Attendance::create([
                        'enrollment_id' => $enrollment->id,
                        'course_session_id' => $session->id,
                        'present' => $isPresent,
                        'arrival_time' => $arrivalTime,
                        'leave_time' => $leaveTime,
                        'excuse_note' => $isPresent ? null : (fake()->boolean(40) ? 'غیبت موجه' : null),
                    ]);
                }
            }
        }

        $this->command->info('✅ Attendance records created successfully!');
    }
}
