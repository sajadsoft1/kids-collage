<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use App\Models\Enrollment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Isolate;
use Livewire\Component;

#[Isolate]
class EnrollmentsWidget extends Component
{
    public int $limit = 10;

    public ?string $start_date = null;

    public ?string $end_date = null;

    public ?int $user_id = null;

    public ?array $user_ids = null;

    public ?Collection $users = null;

    /** Initialize the widget with default values */
    public function mount(
        int $limit = 10,
        ?string $start_date = null,
        ?string $end_date = null,
        ?int $user_id = null,
        ?array $user_ids = null,
        ?Collection $users = null
    ): void {
        $this->limit = $limit;
        $this->start_date = $start_date ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $this->end_date = $end_date ?? Carbon::now()->format('Y-m-d');
        $this->user_id = $user_id;
        $this->user_ids = $user_ids;
        $this->users = $users;
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

        // Default to authenticated user if no parameters provided
        $authUser = auth()->user();
        if ($authUser) {
            return [$authUser->id];
        }

        return null;
    }

    /** Get the list of enrollments */
    #[Computed]
    public function enrollments()
    {
        $query = Enrollment::query()
            ->with(['user', 'course.template', 'course.teacher'])
            ->when($this->getUserIds(), function (Builder $query, array $userIds) {
                $query->whereIn('user_id', $userIds);
            })
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('enrolled_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('enrolled_at', '<=', $this->end_date);
            })
            ->latest('enrolled_at')
            ->limit($this->limit);

        return $query->get();
    }

    /** Get enrollment statistics */
    #[Computed]
    public function enrollmentStats(): array
    {
        $baseQuery = Enrollment::query()
            ->when($this->getUserIds(), function (Builder $query, array $userIds) {
                $query->whereIn('user_id', $userIds);
            })
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('enrolled_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('enrolled_at', '<=', $this->end_date);
            });

        return [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('status', 'active')->count(),
            'completed' => (clone $baseQuery)->where('progress_percent', '>=', 100)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
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
        ]));

        return route('admin.enrollment.index') . '?' . $params;
    }

    /** Render the component */
    public function render()
    {
        return view('livewire.admin.widget.enrollments-widget');
    }
}
