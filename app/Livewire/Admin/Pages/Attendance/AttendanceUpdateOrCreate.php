<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Attendance;

use App\Actions\Attendance\StoreAttendanceAction;
use App\Actions\Attendance\UpdateAttendanceAction;
use App\Enums\BooleanEnum;
use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\Enrollment;
use App\Traits\CrudHelperTrait;
use DateTimeImmutable;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class AttendanceUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public Attendance $model;
    public int $enrollment_id = 0;
    public int $course_session_id = 0;
    public int $present = BooleanEnum::DISABLE->value;
    public ?string $arrival_time = null;
    public ?string $leave_time = null;
    public ?string $excuse_note = null;

    public function mount(Attendance $attendance): void
    {
        $this->model = $attendance;

        if ($this->model->id) {
            $this->enrollment_id = (int) ($this->model->enrollment_id ?? 0);
            $this->course_session_id = (int) ($this->model->course_session_id ?? 0);
            $this->present = (int) $this->model->present->value;
            $this->arrival_time = $this->model->arrival_time?->format('Y-m-d\\TH:i');
            $this->leave_time = $this->model->leave_time?->format('Y-m-d\\TH:i');
            $this->excuse_note = $this->model->excuse_note;
        }
    }

    protected function rules(): array
    {
        return [
            'enrollment_id' => 'required|exists:enrollments,id',
            'course_session_id' => 'required|exists:course_sessions,id',
            'present' => 'required|boolean',
            'arrival_time' => 'nullable|date',
            'leave_time' => 'nullable|date|after:arrival_time',
            'excuse_note' => 'nullable|string|max:1000',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();

        // Convert datetime strings to proper format using core DateTimeImmutable to avoid external dependency warning
        if ( ! empty($payload['arrival_time'])) {
            $payload['arrival_time'] = new DateTimeImmutable($payload['arrival_time']);
        }
        if ( ! empty($payload['leave_time'])) {
            $payload['leave_time'] = new DateTimeImmutable($payload['leave_time']);
        }

        if ($this->model->id) {
            try {
                UpdateAttendanceAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('attendance.model')]),
                    redirectTo: route('admin.attendance.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreAttendanceAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('attendance.model')]),
                    redirectTo: route('admin.attendance.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.attendance.attendance-update-or-create', [
            'edit_mode' => $this->model->id,
            'enrollments' => Enrollment::with(['user', 'course'])
                ->orderByDesc('created_at')
                ->get()
                ->map(function (Enrollment $enrollment): array {
                    $studentName = $enrollment->user?->name ?? 'N/A';
                    $courseTitle = $enrollment->course?->template?->title ?? 'N/A';

                    return [
                        'label' => "{$studentName} — {$courseTitle}",
                        'value' => $enrollment->id,
                    ];
                })
                ->values()
                ->all(),
            'sessions' => CourseSession::with('course')
                ->orderByDesc('date')
                ->orderByDesc('created_at')
                ->get()
                ->map(function (CourseSession $session): array {
                    $courseTitle = $session->course?->template?->title ?? 'N/A';
                    $dateLabel = $session->date?->format('M d, Y') ?? 'Self paced';
                    $timeLabel = $session->start_time ? $session->start_time->format('H:i') : null;
                    $label = $timeLabel ? "{$courseTitle} — {$dateLabel} @ {$timeLabel}" : "{$courseTitle} — {$dateLabel} — {$session->room?->title}";

                    return [
                        'label' => $label ?? 'N/A',
                        'value' => $session->id,
                    ];
                })
                ->values()
                ->all(),
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.attendance.index'), 'label' => trans('general.page.index.title', ['model' => trans('attendance.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('attendance.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.attendance.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
