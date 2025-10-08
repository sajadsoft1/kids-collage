<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\CourseTemplate;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CourseTemplateIndex extends Component
{
    public bool $showRunCourseDrawer = false;

    #[On('run-course')]
    public function runCourse($rowId): void
    {
        $this->showRunCourseDrawer = true;
    }

    public function render(): View
    {
        return view('livewire.admin.pages.course-template.course-template-index', [
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['label' => trans('general.page.index.title', ['model' => trans('courseTemplate.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.course-template.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('courseTemplate.model')])],
            ],
        ]);
    }
}
