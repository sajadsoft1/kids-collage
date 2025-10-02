<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Models\CourseTemplate;
use Livewire\Component;

class CourseRuner extends Component
{
    public CourseTemplate $courseTemplate;
    public int $runningStep = 1;

    /** Navigate to the next step */
    public function nextStep(): void
    {
        if ($this->runningStep < 3) {
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
        ]);
    }
}
