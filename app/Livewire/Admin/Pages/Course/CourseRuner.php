<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Enums\CourseStatusEnum;
use App\Models\CourseTemplate;
use App\Models\Room;
use App\Models\Term;
use App\Models\User;
use Livewire\Component;

class CourseRuner extends Component
{
    public CourseTemplate $courseTemplate;
    public int $runningStep   = 3;
    public int $capacity      = 0;
    public int $price         = 0;
    public string $status     = CourseStatusEnum::DRAFT->value;
    public int $term          = 0;
    public int $teacher       = 0;
    public int $room          = 0;
    public string $start_date = '';
    public string $end_date   = '';
    public string $start_time = '';
    public string $end_time   = '';
    public array $week_days   = [];
    public array $dayNames    = [
        '1' => 'شنبه',
        '2' => 'یکشنبه',
        '3' => 'دوشنبه',
        '4' => 'سه شنبه',
        '5' => 'چهارشنبه',
        '6' => 'پنجشنبه',
        '7' => 'جمعه',
    ];

    /** Handle week days selection updates */
    public function updatedWeekDays($value): void
    {
        // Convert to array if it's not already
        if ( ! is_array($this->week_days)) {
            $this->week_days = [];
        }

        if (empty($this->week_days)) {
            $this->addError('week_days', trans('validation.required', ['attribute' => trans('validation.attributes.week_days')]));
        } else {
            $this->resetErrorBag('week_days');
        }
    }

    /** Handle individual week day toggle */
    public function toggleWeekDay($day): void
    {
        if ( ! is_array($this->week_days)) {
            $this->week_days = [];
        }

        if (in_array($day, $this->week_days)) {
            // Remove the day if it's already selected
            $this->week_days = array_values(array_diff($this->week_days, [$day]));
        } else {
            $this->week_days[] = $day;
        }
    }

    /** Get formatted week days for display */
    public function getFormattedWeekDaysProperty(): string
    {
        if (empty($this->week_days)) {
            return 'هیچ روزی انتخاب نشده';
        }

        // first sort the week days and then map the days to the day names
        $this->week_days = array_values(array_unique($this->week_days));
        sort($this->week_days);

        $selectedDays = array_map(fn ($day) => $this->dayNames[$day] ?? $day, $this->week_days);

        return implode('، ', $selectedDays);
    }

    /** Clear all selected week days */
    public function clearWeekDays(): void
    {
        $this->week_days = [];
    }

    /** Select all week days */
    public function selectAllWeekDays(): void
    {
        $this->week_days = ['1', '2', '3', '4', '5', '6', '7'];
    }

    /** Navigate to the next step */
    public function nextStep(): void
    {
        if ($this->runningStep < 4) {
            $this->runningStep++;
        }
    }

    /** Navigate to the previous step */
    public function previousStep(): void
    {
        if ($this->runningStep > 1) {
            $this->runningStep--;
        }
    }

    /** Start the course */
    public function startCourse(): void
    {
        // TODO: Implement course start logic
        $this->dispatch('course-started', courseId: $this->courseTemplate->id);
    }

    /** Edit the course */
    public function editCourse(): void
    {
        $this->redirect(route('admin.course-template.edit', $this->courseTemplate));
    }

    /** Preview the course */
    public function previewCourse(): void
    {
        // TODO: Implement course preview logic
        $this->dispatch('course-preview', courseId: $this->courseTemplate->id);
    }

    public function render()
    {
        return view('livewire.admin.pages.course.course-runer', [
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.course-template.index'), 'label' => trans('general.page.index.title', ['model' => trans('courseTemplate.model')])],
                ['label' => $this->courseTemplate->title],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.course-template.index'), 'icon' => 's-arrow-left'],
            ],
            'terms'              => Term::all()->map(fn ($term) => ['value' => $term->id, 'label' => $term->title]),
            'teachers'           => User::all()->map(fn ($teacher) => ['value' => $teacher->id, 'label' => $teacher->name]),
            'rooms'              => Room::all()->map(fn ($room) => ['value' => $room->id, 'label' => $room->name]),
            'informations'       => [
                [
                    'label' => trans('validation.attributes.category'),
                    'value' => $this->courseTemplate->category->title ?? 'N/A',
                ],
                [
                    'label' => trans('validation.attributes.tags'),
                    'value' => $this->courseTemplate->tags->map(fn ($tag) => $tag->name)->implode(', '),
                ],
                [
                    'label' => trans('validation.attributes.level'),
                    'value' => $this->courseTemplate->level?->title() ?? 'N/A',
                ],
                [
                    'label' => trans('validation.attributes.session_count'),
                    'value' => $this->courseTemplate->sessionTemplates()->count(),
                ],
            ],
        ]);
    }
}
