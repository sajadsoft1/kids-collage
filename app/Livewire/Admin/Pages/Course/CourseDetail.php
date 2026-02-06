<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Actions\Attendance\MarkAttendanceAction;
use App\Enums\UserTypeEnum;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\CourseTemplate;
use App\Models\Enrollment;
use App\Models\Resource;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Title('course.page.detail_title')]
class CourseDetail extends Component
{
    use Toast;

    // ═══════════════════════════════════════════════════════════════════════════
    // PROPERTIES - Component State
    // ═══════════════════════════════════════════════════════════════════════════

    public CourseTemplate $courseTemplate;

    public Course $course;

    /** Selected session ID for displaying session details */
    public ?int $selectedSessionId = null;

    /** Sessions drawer state (mobile/tablet) */
    public bool $showSessionsDrawer = false;

    /** Selected enrollments for bulk attendance actions */
    public array $selectedEnrollments = [];

    /** Resource modal state */
    public bool $showResourceModal = false;

    public ?int $modalResourceId = null;

    /** Excuse note modal state */
    public bool $showExcuseModal = false;

    public ?int $excuseModalEnrollmentId = null;

    public string $excuseNote = '';

    // ═══════════════════════════════════════════════════════════════════════════
    // LIFECYCLE METHODS
    // ═══════════════════════════════════════════════════════════════════════════
    // Used by: course-detail.blade.php (main layout)

    public function mount(CourseTemplate $courseTemplate, Course $course): void
    {
        $this->courseTemplate = $courseTemplate;
        $this->course = $course->load([
            'template' => function ($q) {
                $q->with(['category', 'sessionTemplates']);
            },
            'teacher',
            'term',
            'sessions' => function ($q) {
                $q->with('sessionTemplate.resources')->orderBy('date')->orderBy('start_time');
            },
            'room',
        ]);

        // Select session from query or first session by default
        if (request()->has('session_id')) {
            $sessionId = (int) request('session_id');
            if ($this->course->sessions->contains('id', $sessionId)) {
                $this->selectedSessionId = $sessionId;
            }
        }
        if ( ! $this->selectedSessionId && $this->course->sessions->isNotEmpty()) {
            $this->selectedSessionId = $this->course->sessions->first()->id;
        }
    }

    /** Hook to ensure UI updates when selectedEnrollments changes */
    public function updatedSelectedEnrollments(): void
    {
        // This method ensures the component re-renders when selection changes
        // No need to do anything, just the method existence triggers Livewire to update
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // COURSE & ENROLLMENT METHODS
    // ═══════════════════════════════════════════════════════════════════════════
    // Used by: course-header.blade.php, course-info-card-compact.blade.php

    /** Get the current user's enrollment for this course */
    #[Computed]
    public function userEnrollment(): ?Enrollment
    {
        $user = Auth::user();

        if ( ! $user) {
            return null;
        }

        return Enrollment::query()
            ->where('user_id', $user->id)
            ->where('course_id', $this->course->id)
            ->first();
    }

    /** Check if the current user can enroll in this course */
    #[Computed]
    public function canEnroll(): bool
    {
        if ($this->userEnrollment) {
            return false; // Already enrolled
        }

        return $this->course->canEnroll();
    }

    /** Get enrollment status for the current user */
    #[Computed]
    public function enrollmentStatus(): ?string
    {
        if ( ! $this->userEnrollment) {
            return null;
        }

        return $this->userEnrollment->status->title();
    }

    /** Check if the current user is the teacher of this course */
    #[Computed]
    public function isTeacher(): bool
    {
        $user = Auth::user();

        if ( ! $user) {
            return false;
        }

        return $this->course->teacher_id === $user->id || $user->type === UserTypeEnum::TEACHER;
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // SESSION METHODS
    // ═══════════════════════════════════════════════════════════════════════════
    // Used by: session-list.blade.php, session-details.blade.php

    public function selectSession(int $sessionId): void
    {
        $this->selectedSessionId = $sessionId;
    }

    /** Get the selected session */
    #[Computed]
    public function selectedSession(): ?CourseSession
    {
        if ( ! $this->selectedSessionId) {
            return null;
        }

        return $this->course->sessions->firstWhere('id', $this->selectedSessionId);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // RESOURCE METHODS
    // ═══════════════════════════════════════════════════════════════════════════
    // Used by: session-resources.blade.php, resource-modal.blade.php

    /** Get resources for the selected session */
    #[Computed]
    public function sessionResources()
    {
        if ( ! $this->selectedSession) {
            return collect();
        }

        // Resources are only attached to CourseSessionTemplate via pivot table
        // There's no direct polymorphic relationship on CourseSession
        $templateResources = $this->selectedSession->sessionTemplate?->resources()->ordered()->get() ?? collect();

        return $templateResources->sortBy('order')->values();
    }

    public function openResourceModal(int $resourceId): void
    {
        $this->modalResourceId = $resourceId;
        $this->showResourceModal = true;
    }

    public function closeResourceModal(): void
    {
        $this->showResourceModal = false;
        $this->modalResourceId = null;
    }

    /** Get the resource for modal display */
    #[Computed]
    public function modalResource(): ?Resource
    {
        if ( ! $this->modalResourceId) {
            return null;
        }

        // Load resource with media
        return Resource::with('media')->find($this->modalResourceId);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // ATTENDANCE METHODS - Teacher Actions
    // ═══════════════════════════════════════════════════════════════════════════
    // Used by: attendance-list.blade.php

    /** Get attendances for the selected session (for teacher) */
    #[Computed]
    public function sessionAttendances()
    {
        if ( ! $this->selectedSession || ! $this->isTeacher()) {
            return collect();
        }

        $enrollments = $this->course->activeEnrollments()->with('user')->get();

        return $enrollments->map(function ($enrollment) {
            $attendance = Attendance::query()
                ->where('enrollment_id', $enrollment->id)
                ->where('course_session_id', $this->selectedSessionId)
                ->first();

            return [
                'id' => $enrollment->id,
                'enrollment' => $enrollment,
                'attendance' => $attendance,
                'is_present' => $attendance?->present->value ?? false,
                'excuse_note' => $attendance?->excuse_note,
                'arrival_time' => $attendance?->arrival_time?->format('H:i'),
                'leave_time' => $attendance?->leave_time?->format('H:i'),
            ];
        });
    }

    /** Mark attendance for a student */
    public function markAttendance(int $enrollmentId, bool $present, ?string $excuseNote = null): void
    {
        if ( ! $this->isTeacher() || ! $this->selectedSessionId) {
            $this->error(__('attendance.exceptions.unauthorized_action'));

            return;
        }

        try {
            MarkAttendanceAction::run($enrollmentId, $this->selectedSessionId, $present, $excuseNote);
            $this->success(__('attendance.notifications.attendance_marked_successfully'));
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /** Record leave time for a student */
    public function recordLeaveTime(int $enrollmentId): void
    {
        if ( ! $this->isTeacher() || ! $this->selectedSessionId) {
            $this->error(__('attendance.exceptions.unauthorized_action'));

            return;
        }

        try {
            $attendance = Attendance::query()
                ->where('enrollment_id', $enrollmentId)
                ->where('course_session_id', $this->selectedSessionId)
                ->first();

            if ( ! $attendance) {
                $this->error(__('attendance.exceptions.attendance_must_be_marked_first'));

                return;
            }

            if ( ! $attendance->present->value) {
                $this->error(__('attendance.exceptions.leave_time_only_for_present'));

                return;
            }

            $attendance->recordDeparture();
            $this->success(__('attendance.notifications.leave_time_recorded_successfully'));
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /** Mark all students as present */
    public function markAllPresent(): void
    {
        if ( ! $this->isTeacher() || ! $this->selectedSessionId) {
            $this->error(__('attendance.exceptions.unauthorized_action'));

            return;
        }

        try {
            $enrollments = $this->course->activeEnrollments()->pluck('id');
            $count = 0;

            foreach ($enrollments as $enrollmentId) {
                MarkAttendanceAction::run($enrollmentId, $this->selectedSessionId, true);
                $count++;
            }

            $this->success(__('attendance.notifications.students_marked_present', ['count' => $count]));
            $this->selectedEnrollments = [];
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /** Mark all students as absent */
    public function markAllAbsent(): void
    {
        if ( ! $this->isTeacher() || ! $this->selectedSessionId) {
            $this->error(__('attendance.exceptions.unauthorized_action'));

            return;
        }

        try {
            $enrollments = $this->course->activeEnrollments()->pluck('id');
            $count = 0;

            foreach ($enrollments as $enrollmentId) {
                MarkAttendanceAction::run($enrollmentId, $this->selectedSessionId, false);
                $count++;
            }

            $this->success(__('attendance.notifications.students_marked_absent', ['count' => $count]));
            $this->selectedEnrollments = [];
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /** Mark selected students as present */
    public function markSelectedPresent(): void
    {
        if ( ! $this->isTeacher() || ! $this->selectedSessionId || empty($this->selectedEnrollments)) {
            $this->error(__('attendance.exceptions.select_at_least_one_student'));

            return;
        }

        try {
            $count = 0;

            foreach ($this->selectedEnrollments as $enrollmentId) {
                MarkAttendanceAction::run((int) $enrollmentId, $this->selectedSessionId, true);
                $count++;
            }

            $this->success(__('attendance.notifications.students_marked_present', ['count' => $count]));
            $this->selectedEnrollments = [];
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /** Mark selected students as absent */
    public function markSelectedAbsent(): void
    {
        if ( ! $this->isTeacher() || ! $this->selectedSessionId || empty($this->selectedEnrollments)) {
            $this->error(__('attendance.exceptions.select_at_least_one_student'));

            return;
        }

        try {
            $count = 0;

            foreach ($this->selectedEnrollments as $enrollmentId) {
                MarkAttendanceAction::run((int) $enrollmentId, $this->selectedSessionId, false);
                $count++;
            }

            $this->success(__('attendance.notifications.students_marked_absent', ['count' => $count]));
            $this->selectedEnrollments = [];
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // ATTENDANCE METHODS - Student View
    // ═══════════════════════════════════════════════════════════════════════════
    // Used by: student-attendance.blade.php

    /** Get user's attendance for the selected session (for student) */
    #[Computed]
    public function userAttendanceForSession(): ?Attendance
    {
        if ( ! $this->selectedSession || ! $this->userEnrollment) {
            return null;
        }

        return Attendance::query()
            ->where('enrollment_id', $this->userEnrollment->id)
            ->where('course_session_id', $this->selectedSessionId)
            ->first();
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // ATTENDANCE METHODS - Excuse Note Modal
    // ═══════════════════════════════════════════════════════════════════════════
    // Used by: attendance-list.blade.php (excuse modal)

    /** Open excuse modal for a specific enrollment */
    public function openExcuseModal(int $enrollmentId): void
    {
        $this->excuseModalEnrollmentId = $enrollmentId;
        $attendance = Attendance::query()
            ->where('enrollment_id', $enrollmentId)
            ->where('course_session_id', $this->selectedSessionId)
            ->first();
        $this->excuseNote = $attendance?->excuse_note ?? '';
        $this->showExcuseModal = true;
    }

    /** Close excuse modal */
    public function closeExcuseModal(): void
    {
        $this->showExcuseModal = false;
        $this->excuseModalEnrollmentId = null;
        $this->excuseNote = '';
    }

    /** Mark attendance as absent with excuse note */
    public function markAbsentWithExcuse(): void
    {
        if ( ! $this->isTeacher() || ! $this->selectedSessionId || ! $this->excuseModalEnrollmentId) {
            $this->error(__('attendance.exceptions.unauthorized_action'));

            return;
        }

        try {
            MarkAttendanceAction::run($this->excuseModalEnrollmentId, $this->selectedSessionId, false, $this->excuseNote ?: null);
            $this->success(__('attendance.notifications.absence_marked_successfully'));
            $this->closeExcuseModal();
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // RENDER METHOD
    // ═══════════════════════════════════════════════════════════════════════════

    public function render()
    {
        return view('livewire.admin.pages.course.course-detail');
    }
}
