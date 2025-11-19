<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Isolate;
use Livewire\Component;

#[Isolate]
class TeacherClassesWidget extends Component
{
    public int $limit = 10;

    public ?string $start_date = null;

    public ?string $end_date = null;

    public ?int $teacher_id = null;

    public ?User $teacher = null;

    /** Initialize the widget with default values */
    public function mount(
        int $limit = 10,
        ?string $start_date = null,
        ?string $end_date = null,
        ?int $teacher_id = null,
        ?User $teacher = null
    ): void {
        $this->limit = $limit;
        $this->start_date = $start_date ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $this->end_date = $end_date ?? Carbon::now()->format('Y-m-d');
        $this->teacher_id = $teacher_id ?? $teacher?->id ?? auth()->id();
    }

    /** Get the list of classes */
    #[Computed]
    public function classes()
    {
        $query = Course::query()
            ->with(['template', 'term', 'teacher'])
            ->where('teacher_id', $this->teacher_id)
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

    /** Get class statistics */
    #[Computed]
    public function classStats(): array
    {
        $baseQuery = Course::query()
            ->where('teacher_id', $this->teacher_id)
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('created_at', '<=', $this->end_date);
            });

        return [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('status', 'active')->count(),
            'total_students' => (clone $baseQuery)->withCount('enrollments')->get()->sum('enrollments_count'),
        ];
    }

    /** Get the URL for viewing more items */
    public function getMoreItemsUrl(): string
    {
        $params = http_build_query(array_filter([
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'teacher_id' => $this->teacher_id,
        ]));

        return route('admin.course.index') . '?' . $params;
    }

    /** Render the component */
    public function render()
    {
        return view('livewire.admin.widget.teacher-classes-widget');
    }
}
