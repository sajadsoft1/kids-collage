<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\CourseSession;

use App\Actions\CourseSession\StoreCourseSessionAction;
use App\Actions\CourseSession\UpdateCourseSessionAction;
use App\Models\CourseSession;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class CourseSessionUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public CourseSession $model;
    public string $title       = '';
    public string $description = '';
    public bool $published     = false;

    public function mount(CourseSession $courseSession): void
    {
        $this->model = $courseSession;
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
                UpdateCourseSessionAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('courseSession.model')]),
                    redirectTo: route('admin.courseSession.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreCourseSessionAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('courseSession.model')]),
                    redirectTo: route('admin.courseSession.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.courseSession.courseSession-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.courseSession.index'), 'label' => trans('general.page.index.title', ['model' => trans('courseSession.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('courseSession.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.courseSession.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
