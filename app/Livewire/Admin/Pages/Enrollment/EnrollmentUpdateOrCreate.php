<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Enrollment;

use App\Actions\Enrollment\StoreEnrollmentAction;
use App\Actions\Enrollment\UpdateEnrollmentAction;
use App\Models\Enrollment;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class EnrollmentUpdateOrCreate extends Component
{
    use Toast;

    public Enrollment $model;
    public string $title       = '';
    public string $description = '';
    public bool $published     = false;

    public function mount(Enrollment $enrollment): void
    {
        $this->model = $enrollment;
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
            UpdateEnrollmentAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('enrollment.model')]),
                redirectTo: route('admin.enrollment.index')
            );
        } else {
            StoreEnrollmentAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('enrollment.model')]),
                redirectTo: route('admin.enrollment.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.enrollment.enrollment-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.enrollment.index'), 'label' => trans('general.page.index.title', ['model' => trans('enrollment.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('enrollment.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.enrollment.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
