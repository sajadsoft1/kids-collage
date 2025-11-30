<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Enums\CourseStatusEnum;
use App\Enums\EnrollmentStatusEnum;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('دوره‌های من')]
class CourseListForUser extends Component
{
    public string $activeTab = 'available';

    public string $search = '';

    /** Get available courses for the current user */
    #[Computed]
    public function availableCourses(): \Illuminate\Support\Collection
    {
        $user = Auth::user();

        return Course::query()
            ->whereIn('status', [CourseStatusEnum::SCHEDULED, CourseStatusEnum::ACTIVE])
            ->whereDoesntHave('enrollments', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->whereIn('status', [
                        EnrollmentStatusEnum::PENDING,
                        EnrollmentStatusEnum::PAID,
                        EnrollmentStatusEnum::ACTIVE,
                    ]);
            })
            ->with(['template' => function ($q) {
                $q->with('category');
            }, 'teacher', 'term'])
            ->withCount('activeEnrollments')
            ->when($this->search, function ($q) {
                $q->whereHas('template', function ($query) {
                    $query->where('title', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(fn (Course $course) => $course->canEnroll())
            ->values();
    }

    /** Get enrolled courses for the current user */
    #[Computed]
    public function enrolledCourses(): \Illuminate\Support\Collection
    {
        $user = Auth::user();

        return Enrollment::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                EnrollmentStatusEnum::PENDING,
                EnrollmentStatusEnum::PAID,
                EnrollmentStatusEnum::ACTIVE,
            ])
            ->whereHas('course', function ($q) {
                $q->where('status', CourseStatusEnum::ACTIVE);
            })
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
            ->orderBy('enrolled_at', 'desc')
            ->get();
    }

    /** Get completed courses for the current user */
    #[Computed]
    public function completedCourses(): \Illuminate\Support\Collection
    {
        $user = Auth::user();

        return Enrollment::query()
            ->where('user_id', $user->id)
            ->where('progress_percent', '>=', 100.0)
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
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    /** Get statistics for the current user */
    #[Computed]
    public function stats(): array
    {
        $user = Auth::user();

        $enrolledCount = Enrollment::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                EnrollmentStatusEnum::PENDING,
                EnrollmentStatusEnum::PAID,
                EnrollmentStatusEnum::ACTIVE,
            ])
            ->whereHas('course', function ($q) {
                $q->where('status', CourseStatusEnum::ACTIVE);
            })
            ->count();

        $completedCount = Enrollment::query()
            ->where('user_id', $user->id)
            ->where('progress_percent', '>=', 100.0)
            ->count();

        return [
            'available_count' => $this->availableCourses->count(),
            'enrolled_count' => $enrolledCount,
            'completed_count' => $completedCount,
        ];
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.pages.course.course-list-for-user');
    }
}
