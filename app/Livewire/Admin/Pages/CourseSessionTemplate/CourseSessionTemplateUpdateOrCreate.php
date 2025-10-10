<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\CourseSessionTemplate;

use App\Actions\CourseSessionTemplate\StoreCourseSessionTemplateAction;
use App\Actions\CourseSessionTemplate\UpdateCourseSessionTemplateAction;
use App\Models\CourseSessionTemplate;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class CourseSessionTemplateUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public CourseSessionTemplate $model;
    public string $title       = '';
    public string $description = '';
    public bool $published     = false;

    public function mount(CourseSessionTemplate $courseSessionTemplate): void
    {
        $this->model = $courseSessionTemplate;
        if ($this->model->id) {
            $this->title       = $this->model->title;
            $this->description = $this->model->description;
            $this->published   = $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string',
            'description' => 'required|string',
            'published'   => 'required',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            try {
                UpdateCourseSessionTemplateAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('courseSessionTemplate.model')]),
                    redirectTo: route('admin.courseSessionTemplate.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreCourseSessionTemplateAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('courseSessionTemplate.model')]),
                    redirectTo: route('admin.courseSessionTemplate.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.courseSessionTemplate.courseSessionTemplate-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.courseSessionTemplate.index'), 'label' => trans('general.page.index.title', ['model' => trans('courseSessionTemplate.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('courseSessionTemplate.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.courseSessionTemplate.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
