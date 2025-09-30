<?php

namespace App\Livewire\Admin\Pages\CourseTemplate;

use App\Actions\CourseTemplate\StoreCourseTemplateAction;
use App\Actions\CourseTemplate\UpdateCourseTemplateAction;
use App\Models\CourseTemplate;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class CourseTemplateUpdateOrCreate extends Component
{
    use Toast;

    public CourseTemplate   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(CourseTemplate $courseTemplate): void
    {
        $this->model = $courseTemplate;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->description = $this->model->description;
            $this->published = $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string',
            'description' => 'required|string',
            'published'   => 'required'
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateCourseTemplateAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('courseTemplate.model')]),
                redirectTo: route('admin.courseTemplate.index')
            );
        } else {
            StoreCourseTemplateAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('courseTemplate.model')]),
                redirectTo: route('admin.courseTemplate.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.courseTemplate.courseTemplate-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.courseTemplate.index'), 'label' => trans('general.page.index.title', ['model' => trans('courseTemplate.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('courseTemplate.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.courseTemplate.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
