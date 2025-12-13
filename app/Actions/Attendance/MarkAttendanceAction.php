<?php

declare(strict_types=1);

namespace App\Actions\Attendance;

use App\Enums\BooleanEnum;
use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\Enrollment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class MarkAttendanceAction
{
    use AsAction;

    /**
     * Mark or update attendance for a student in a session.
     *
     * @throws Throwable
     */
    public function handle(int $enrollmentId, int $sessionId, bool $present, ?string $excuseNote = null): Attendance
    {
        $user = Auth::user();

        if ( ! $user) {
            throw new Exception('User must be authenticated');
        }

        $enrollment = Enrollment::findOrFail($enrollmentId);
        $session = CourseSession::findOrFail($sessionId);

        // Check if user is the teacher of this course
        if ($session->course->teacher_id !== $user->id && $user->type->value !== 'teacher') {
            throw new Exception('Only the course teacher can mark attendance');
        }

        // Verify enrollment belongs to the course
        if ($enrollment->course_id !== $session->course_id) {
            throw new Exception('Enrollment does not belong to this course');
        }

        return DB::transaction(function () use ($enrollmentId, $sessionId, $present, $excuseNote) {
            $attendance = Attendance::query()
                ->where('enrollment_id', $enrollmentId)
                ->where('course_session_id', $sessionId)
                ->first();

            $payload = [
                'enrollment_id' => $enrollmentId,
                'course_session_id' => $sessionId,
                'present' => $present ? BooleanEnum::ENABLE : BooleanEnum::DISABLE,
                'excuse_note' => $excuseNote,
            ];

            if ($present) {
                $payload['arrival_time'] = now();
            } else {
                $payload['arrival_time'] = null;
                $payload['leave_time'] = null;
            }

            if ($attendance) {
                $attendance->update($payload);
                Log::info('Attendance updated', [
                    'attendance_id' => $attendance->id,
                    'enrollment_id' => $enrollmentId,
                    'session_id' => $sessionId,
                    'present' => $present,
                ]);

                return $attendance->refresh();
            }

            $attendance = Attendance::create($payload);
            Log::info('Attendance created', [
                'attendance_id' => $attendance->id,
                'enrollment_id' => $enrollmentId,
                'session_id' => $sessionId,
                'present' => $present,
            ]);

            return $attendance;
        });
    }
}
