<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Term;

use App\Enums\EnrollmentStatusEnum;
use App\Models\Enrollment;
use App\Models\Term;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('term.page.detail_title')]
final class TermDetail extends Component
{
    public Term $term;

    public function mount(Term $term): void
    {
        $this->term = $term->load([
            'courses' => fn ($q) => $q->with(['template', 'teacher', 'room'])
                ->withCount(['enrollments as active_enrollments_count' => fn ($q) => $q->whereIn('status', [EnrollmentStatusEnum::ACTIVE, EnrollmentStatusEnum::PAID])]),
        ]);
    }

    /** Courses in this term (already eager loaded). */
    #[Computed]
    public function courses(): Collection
    {
        return $this->term->courses;
    }

    /** Unique students enrolled in this term (active/paid) with enrollment count per user. */
    #[Computed]
    public function students(): Collection
    {
        $courseIds = $this->term->courses->pluck('id')->all();

        if (empty($courseIds)) {
            return collect();
        }

        $userIds = Enrollment::query()
            ->whereIn('course_id', $courseIds)
            ->whereIn('status', [
                EnrollmentStatusEnum::ACTIVE,
                EnrollmentStatusEnum::PAID,
            ])
            ->pluck('user_id');

        $userIdToCount = $userIds->countBy();

        $uniqueUserIds = $userIdToCount->keys()->unique()->values()->all();

        if (empty($uniqueUserIds)) {
            return collect();
        }

        $users = User::query()
            ->whereIn('id', $uniqueUserIds)
            ->get()
            ->keyBy('id');

        return $userIdToCount->map(function (int $count, int $userId) use ($users) {
            $user = $users->get($userId);

            return $user ? ['user' => $user, 'enrollment_count' => $count] : null;
        })->filter()->values();
    }

    public function render()
    {
        return view('livewire.admin.pages.term.term-detail', [
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.term.index'), 'label' => trans('general.page.index.title', ['model' => trans('term.model')])],
                ['label' => trans('term.page.detail_title')],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.term.index'), 'icon' => 's-arrow-left', 'label' => trans('general.page.index.title', ['model' => trans('term.model')])],
                ['link' => route('admin.term.edit', $this->term), 'icon' => 's-pencil', 'label' => trans('datatable.buttons.edit')],
            ],
        ]);
    }
}
