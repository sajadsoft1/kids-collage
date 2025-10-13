<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\CourseSessionTemplate;

use App\Actions\CourseSessionTemplate\StoreCourseSessionTemplateAction;
use App\Actions\CourseSessionTemplate\UpdateCourseSessionTemplateAction;
use App\Enums\SessionType;
use App\Models\CourseSessionTemplate;
use App\Models\CourseTemplate;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class CourseSessionTemplateUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public CourseTemplate $courseTemplate;
    public CourseSessionTemplate $model;
    public string $title         = '';
    public string $description   = '';
    public int $order            = 0;
    public int $duration_minutes = 0;
    public string $type          = SessionType::IN_PERSON->value;

    public function mount(CourseSessionTemplate $courseSessionTemplate): void
    {
        $this->model = $courseSessionTemplate;
        if ($this->model->id) {
            $this->title            = $this->model->title;
            $this->description      = $this->model->description;
            $this->order            = $this->model->order;
            $this->duration_minutes = $this->model->duration_minutes;
            $this->type             = $this->model->type->value;
        } else {
            $this->order = $this->courseTemplate->sessionTemplates()->count() + 1;
        }
    }

    protected function rules(): array
    {
        return [
            'title'            => 'required|string',
            'description'      => 'required|string',
            'order'            => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1',
            'type'             => 'required|in:' . implode(',', SessionType::values()),
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateCourseSessionTemplateAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('courseSessionTemplate.model')]),
                redirectTo: route('admin.course-session-template.index', ['courseTemplate' => $this->courseTemplate->id])
            );
        } else {
            $payload['course_template_id'] = $this->courseTemplate->id;
            StoreCourseSessionTemplateAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('courseSessionTemplate.model')]),
                redirectTo: route('admin.course-session-template.index', ['courseTemplate' => $this->courseTemplate->id])
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.courseSessionTemplate.courseSessionTemplate-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.course-template.index'), 'label' => trans('general.page.index.title', ['model' => trans('coursetemplate.model')])],
                ['link'  => route('admin.course-session-template.index', ['courseTemplate' => $this->courseTemplate->id]), 'label' => $this->courseTemplate->title],
                ['label' => trans('general.page.create.title', ['model' => trans('courseSessionTemplate.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.course-session-template.index', ['courseTemplate' => $this->courseTemplate->id]), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
