<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Attendance;

use App\Actions\Attendance\StoreAttendanceAction;
use App\Actions\Attendance\UpdateAttendanceAction;
use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\Enrollment;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class AttendanceUpdateOrCreate extends Component
{
    use Toast;

    public Attendance $model;
    public int $enrollment_id = 0;
    public int $session_id    = 0;
    public bool $present      = false;
    public $arrival_time      = '';
    public $leave_time        = '';

    public function mount(Attendance $attendance): void
    {
        $this->model = $attendance;

        if ($this->model->id) {
            $this->enrollment_id = $this->model->enrollment_id;
            $this->session_id    = $this->model->session_id;
            $this->present       = $this->model->present;
            $this->arrival_time  = $this->model->arrival_time?->format('Y-m-d\TH:i');
            $this->leave_time    = $this->model->leave_time?->format('Y-m-d\TH:i');
        }
    }

    protected function rules(): array
    {
        return [
            'enrollment_id' => 'required|exists:enrollments,id',
            'session_id'    => 'required|exists:course_sessions,id',
            'present'       => 'required|boolean',
            'arrival_time'  => 'nullable|date',
            'leave_time'    => 'nullable|date|after:arrival_time',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();

        // Convert datetime strings to proper format
        if ($payload['arrival_time']) {
            $payload['arrival_time'] = \Carbon\Carbon::parse($payload['arrival_time']);
        }
        if ($payload['leave_time']) {
            $payload['leave_time'] = \Carbon\Carbon::parse($payload['leave_time']);
        }

        if ($this->model->id) {
            UpdateAttendanceAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('attendance.model')]),
                redirectTo: route('admin.attendance.index')
            );
        } else {
            StoreAttendanceAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('attendance.model')]),
                redirectTo: route('admin.attendance.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.attendance.attendance-update-or-create', [
            'edit_mode'          => $this->model->id,
            'enrollments'        => Enrollment::with('user')->get()->map(fn ($item) => ['name' => $item->user->name, 'id' => $item->id])->toArray(),
            'sessions'           => CourseSession::with('course')->get()->map(fn ($item) => ['name' => $item->course->title, 'id' => $item->id])->toArray(),
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.attendance.index'), 'label' => trans('general.page.index.title', ['model' => trans('attendance.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('attendance.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.attendance.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
