<?php

namespace App\Livewire\Admin\Pages\Course;

use App\Actions\Course\StoreCourseAction;
use App\Actions\Course\UpdateCourseAction;
use App\Models\Course;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class CourseUpdateOrCreate extends Component
{
    use Toast;

    public Course   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Course $course): void
    {
        $this->model = $course;
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
            UpdateCourseAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('course.model')]),
                redirectTo: route('admin.course.index')
            );
        } else {
            StoreCourseAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('course.model')]),
                redirectTo: route('admin.course.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.course.course-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.course.index'), 'label' => trans('general.page.index.title', ['model' => trans('course.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('course.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.course.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
