<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Isolate;
use Livewire\Component;

#[Isolate]
class AttendanceWidget extends Component
{
    public int $limit = 10;

    public ?string $start_date = null;

    public ?string $end_date = null;

    public ?int $user_id = null;

    public ?array $user_ids = null;

    public ?Collection $users = null;

    public ?int $enrollment_id = null;

    public ?array $enrollment_ids = null;

    public ?int $teacher_id = null;

    /** Initialize the widget with default values */
    public function mount(
        int $limit = 10,
        ?string $start_date = null,
        ?string $end_date = null,
        ?int $user_id = null,
        ?array $user_ids = null,
        ?Collection $users = null,
        ?int $enrollment_id = null,
        ?array $enrollment_ids = null,
        ?int $teacher_id = null
    ): void {
        $this->limit = $limit;
        $this->start_date = $start_date ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $this->end_date = $end_date ?? Carbon::now()->format('Y-m-d');
        $this->user_id = $user_id;
        $this->user_ids = $user_ids;
        $this->users = $users;
        $this->enrollment_id = $enrollment_id;
        $this->enrollment_ids = $enrollment_ids;
        $this->teacher_id = $teacher_id;
    }

    /** Get user IDs for filtering */
    private function getUserIds(): ?array
    {
        if ($this->user_id) {
            return [$this->user_id];
        }

        if ($this->user_ids) {
            return $this->user_ids;
        }

        if ($this->users) {
            return $this->users->pluck('id')->toArray();
        }

        return null;
    }

    /** Get enrollment IDs for filtering */
    private function getEnrollmentIds(): ?array
    {
        if ($this->enrollment_id) {
            return [$this->enrollment_id];
        }

        return $this->enrollment_ids;
    }

    /** Get the list of attendances */
    #[Computed]
    public function attendances()
    {
        $query = Attendance::query()
            ->with(['enrollment.user', 'enrollment.course.template', 'session'])
            ->when($this->getEnrollmentIds(), function (Builder $query, array $enrollmentIds) {
                $query->whereIn('enrollment_id', $enrollmentIds);
            })
            ->when($this->getUserIds(), function (Builder $query, array $userIds) {
                $query->whereHas('enrollment', function (Builder $q) use ($userIds) {
                    $q->whereIn('user_id', $userIds);
                });
            })
            ->when($this->teacher_id, function (Builder $query) {
                $query->whereHas('session.course', function (Builder $q) {
                    $q->where('teacher_id', $this->teacher_id);
                });
            })
            ->when($this->start_date, function (Builder $query) {
                $query->whereHas('session', function (Builder $q) {
                    $q->whereDate('date', '>=', $this->start_date);
                });
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereHas('session', function (Builder $q) {
                    $q->whereDate('date', '<=', $this->end_date);
                });
            })
            ->latest('created_at')
            ->limit($this->limit);

        return $query->get();
    }

    /** Get attendance statistics */
    #[Computed]
    public function attendanceStats(): array
    {
        $baseQuery = Attendance::query()
            ->when($this->getEnrollmentIds(), function (Builder $query, array $enrollmentIds) {
                $query->whereIn('enrollment_id', $enrollmentIds);
            })
            ->when($this->getUserIds(), function (Builder $query, array $userIds) {
                $query->whereHas('enrollment', function (Builder $q) use ($userIds) {
                    $q->whereIn('user_id', $userIds);
                });
            })
            ->when($this->teacher_id, function (Builder $query) {
                $query->whereHas('session.course', function (Builder $q) {
                    $q->where('teacher_id', $this->teacher_id);
                });
            })
            ->when($this->start_date, function (Builder $query) {
                $query->whereHas('session', function (Builder $q) {
                    $q->whereDate('date', '>=', $this->start_date);
                });
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereHas('session', function (Builder $q) {
                    $q->whereDate('date', '<=', $this->end_date);
                });
            });

        $total = (clone $baseQuery)->count();
        $present = (clone $baseQuery)->where('present', true)->count();
        $absent = (clone $baseQuery)->where('present', false)->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
        ];
    }

    /** Get the URL for viewing more items */
    public function getMoreItemsUrl(): string
    {
        $params = http_build_query(array_filter([
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user_id' => $this->user_id,
            'user_ids' => $this->user_ids,
            'enrollment_id' => $this->enrollment_id,
            'enrollment_ids' => $this->enrollment_ids,
            'teacher_id' => $this->teacher_id,
        ]));

        return route('admin.attendance.index') . '?' . $params;
    }

    /** Render the component */
    public function render()
    {
        return view('livewire.admin.widget.attendance-widget');
    }
}
