<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use App\Models\CourseSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Isolate;
use Livewire\Component;

#[Isolate]
class TeacherScheduleWidget extends Component
{
    public int $limit = 10;

    public ?int $teacher_id = null;

    public ?User $teacher = null;

    public ?string $date = null;

    /** Initialize the widget with default values */
    public function mount(
        int $limit = 10,
        ?int $teacher_id = null,
        ?User $teacher = null,
        ?string $date = null
    ): void {
        $this->limit = $limit;
        $this->teacher_id = $teacher_id ?? $teacher?->id ?? auth()->id();
        $this->date = $date ?? Carbon::today()->format('Y-m-d');
    }

    /** Get the list of sessions */
    #[Computed]
    public function sessions()
    {
        $query = CourseSession::query()
            ->with(['course.template', 'course.teacher', 'room'])
            ->whereHas('course', function (Builder $q) {
                $q->where('teacher_id', $this->teacher_id);
            })
            ->whereDate('date', $this->date)
            ->orderBy('start_time')
            ->limit($this->limit);

        return $query->get();
    }

    /** Get schedule statistics */
    #[Computed]
    public function scheduleStats(): array
    {
        $today = Carbon::parse($this->date);

        $todaySessions = CourseSession::query()
            ->whereHas('course', function (Builder $q) {
                $q->where('teacher_id', $this->teacher_id);
            })
            ->whereDate('date', $today)
            ->count();

        $weekSessions = CourseSession::query()
            ->whereHas('course', function (Builder $q) {
                $q->where('teacher_id', $this->teacher_id);
            })
            ->whereBetween('date', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()])
            ->count();

        return [
            'today' => $todaySessions,
            'this_week' => $weekSessions,
        ];
    }

    /** Get the URL for viewing more items */
    public function getMoreItemsUrl(): string
    {
        $params = http_build_query(array_filter([
            'teacher_id' => $this->teacher_id,
            'date' => $this->date,
        ]));

        return '#';
        // return route('admin.course-session.index') . '?' . $params;
    }

    /** Render the component */
    public function render()
    {
        return view('livewire.admin.widget.teacher-schedule-widget');
    }
}
