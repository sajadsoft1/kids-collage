<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Models\Course;
use App\Models\CourseTemplate;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('جزئیات دوره')]
class CourseDetail extends Component
{
    public CourseTemplate $courseTemplate;

    public Course $course;

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
                $q->with('sessionTemplate')->orderBy('date')->orderBy('start_time');
            },
            'room',
        ]);
    }

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

    public function render()
    {
        return view('livewire.admin.pages.course.course-detail');
    }
}
