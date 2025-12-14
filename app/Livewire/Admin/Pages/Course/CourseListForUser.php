<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Enums\CourseLevelEnum;
use App\Enums\CourseTypeEnum;
use App\Enums\EnrollmentStatusEnum;
use App\Models\Category;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('آموزش ها')]
class CourseListForUser extends Component
{
    public string $search = '';

    public string $sortBy = 'latest';

    public ?int $categoryId = null;

    public ?string $level = null;

    public ?string $type = null;

    public ?string $statusFilter = null; // null = all, 'in_progress' = progress < 100, 'completed' = progress >= 100

    /** Get all enrolled courses for the current user (both in progress and completed) */
    #[Computed]
    public function allCourses(): \Illuminate\Support\Collection
    {
        $user = Auth::user();

        return Enrollment::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                EnrollmentStatusEnum::PENDING,
                EnrollmentStatusEnum::PAID,
                EnrollmentStatusEnum::ACTIVE,
            ])
            ->with(['course' => function ($q) {
                $q->with(['template' => function ($t) {
                    $t->with('category');
                }, 'teacher', 'term']);
            }])
            ->when($this->search, function ($q) {
                $q->whereHas('course.template', function ($query) {
                    $query->where('title', 'like', "%{$this->search}%");
                });
            })
            ->when($this->categoryId, function ($q) {
                $q->whereHas('course.template', function ($query) {
                    $query->where('category_id', $this->categoryId);
                });
            })
            ->when($this->level, function ($q) {
                $q->whereHas('course.template', function ($query) {
                    $query->where('level', $this->level);
                });
            })
            ->when($this->type, function ($q) {
                $q->whereHas('course.template', function ($query) {
                    $query->where('type', $this->type);
                });
            })
            ->when($this->statusFilter === 'in_progress', function ($q) {
                $q->where('progress_percent', '<', 100.0);
            })
            ->when($this->statusFilter === 'completed', function ($q) {
                $q->where('progress_percent', '>=', 100.0);
            })
            ->orderBy('enrolled_at', 'desc')
            ->get()
            ->when($this->sortBy === 'latest', fn ($collection) => $collection->sortByDesc('enrolled_at'))
            ->when($this->sortBy === 'oldest', fn ($collection) => $collection->sortBy('enrolled_at'))
            ->when($this->sortBy === 'title_asc', fn ($collection) => $collection->sortBy(fn ($enrollment) => $enrollment->course->template->title ?? ''))
            ->when($this->sortBy === 'title_desc', fn ($collection) => $collection->sortByDesc(fn ($enrollment) => $enrollment->course->template->title ?? ''))
            ->when( ! in_array($this->sortBy, ['latest', 'oldest', 'title_asc', 'title_desc']), fn ($collection) => $collection->sortByDesc('enrolled_at'))
            ->values();
    }

    /** Get statistics for the current user */
    #[Computed]
    public function stats(): array
    {
        $user = Auth::user();

        $allCount = Enrollment::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                EnrollmentStatusEnum::PENDING,
                EnrollmentStatusEnum::PAID,
                EnrollmentStatusEnum::ACTIVE,
            ])
            ->count();

        $inProgressCount = Enrollment::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                EnrollmentStatusEnum::PENDING,
                EnrollmentStatusEnum::PAID,
                EnrollmentStatusEnum::ACTIVE,
            ])
            ->where('progress_percent', '<', 100.0)
            ->count();

        $completedCount = Enrollment::query()
            ->where('user_id', $user->id)
            ->where('progress_percent', '>=', 100.0)
            ->count();

        return [
            'all_count' => $allCount,
            'in_progress_count' => $inProgressCount,
            'completed_count' => $completedCount,
        ];
    }

    #[Computed]
    public function categories(): array
    {
        return Category::where('type', 'course')
            ->get()
            ->map(fn ($category) => [
                'value' => $category->id,
                'label' => $category->title,
            ])
            ->toArray();
    }

    #[Computed]
    public function levels(): array
    {
        return CourseLevelEnum::options();
    }

    #[Computed]
    public function types(): array
    {
        return CourseTypeEnum::options();
    }

    #[Computed]
    public function statusFilters(): array
    {
        return [
            ['value' => '', 'label' => __('general.all')],
            ['value' => 'in_progress', 'label' => __('general.in_progress')],
            ['value' => 'completed', 'label' => __('general.completed')],
        ];
    }

    public function render()
    {
        return view('livewire.admin.pages.course.course-list-for-user');
    }
}
