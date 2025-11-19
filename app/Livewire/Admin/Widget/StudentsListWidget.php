<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Isolate;
use Livewire\Component;

#[Isolate]
class StudentsListWidget extends Component
{
    public int $limit = 10;

    public ?string $start_date = null;

    public ?string $end_date = null;

    public ?array $user_ids = null;

    public ?Collection $users = null;

    public ?int $course_id = null;

    public ?int $teacher_id = null;

    /** Initialize the widget with default values */
    public function mount(
        int $limit = 10,
        ?string $start_date = null,
        ?string $end_date = null,
        ?array $user_ids = null,
        ?Collection $users = null,
        ?int $course_id = null,
        ?int $teacher_id = null
    ): void {
        $this->limit = $limit;
        $this->start_date = $start_date ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $this->end_date = $end_date ?? Carbon::now()->format('Y-m-d');
        $this->user_ids = $user_ids;
        $this->users = $users;
        $this->course_id = $course_id;
        $this->teacher_id = $teacher_id;
    }

    /** Get user IDs for filtering */
    private function getUserIds(): ?array
    {
        if ($this->user_ids) {
            return $this->user_ids;
        }

        if ($this->users) {
            return $this->users->pluck('id')->toArray();
        }

        return null;
    }

    /** Get the list of students */
    #[Computed]
    public function students()
    {
        $query = User::query()
            ->where('type', 'user')
            ->with(['profile'])
            ->when($this->getUserIds(), function (Builder $query, array $userIds) {
                $query->whereIn('id', $userIds);
            })
            ->when($this->course_id, function (Builder $query) {
                $query->whereHas('enrollments', function (Builder $q) {
                    $q->where('course_id', $this->course_id);
                });
            })
            ->when($this->teacher_id, function (Builder $query) {
                $query->whereHas('enrollments.course', function (Builder $q) {
                    $q->where('teacher_id', $this->teacher_id);
                });
            })
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('created_at', '<=', $this->end_date);
            })
            ->latest('created_at')
            ->limit($this->limit);

        return $query->get();
    }

    /** Get student statistics */
    #[Computed]
    public function studentStats(): array
    {
        $baseQuery = User::query()
            ->where('type', 'user')
            ->when($this->getUserIds(), function (Builder $query, array $userIds) {
                $query->whereIn('id', $userIds);
            })
            ->when($this->course_id, function (Builder $query) {
                $query->whereHas('enrollments', function (Builder $q) {
                    $q->where('course_id', $this->course_id);
                });
            })
            ->when($this->teacher_id, function (Builder $query) {
                $query->whereHas('enrollments.course', function (Builder $q) {
                    $q->where('teacher_id', $this->teacher_id);
                });
            })
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('created_at', '<=', $this->end_date);
            });

        return [
            'total' => (clone $baseQuery)->count(),
            'with_active_enrollments' => (clone $baseQuery)->whereHas('enrollments', function (Builder $q) {
                $q->where('status', 'active');
            })->count(),
        ];
    }

    /** Get the URL for viewing more items */
    public function getMoreItemsUrl(): string
    {
        $params = http_build_query(array_filter([
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user_ids' => $this->user_ids,
            'course_id' => $this->course_id,
            'teacher_id' => $this->teacher_id,
        ]));

        return route('admin.user.index') . '?' . $params;
    }

    /** Render the component */
    public function render()
    {
        return view('livewire.admin.widget.students-list-widget');
    }
}
