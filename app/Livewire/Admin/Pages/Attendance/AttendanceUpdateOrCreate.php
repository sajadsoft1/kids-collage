<?php

namespace App\Livewire\Admin\Pages\Attendance;

use App\Actions\Attendance\StoreAttendanceAction;
use App\Actions\Attendance\UpdateAttendanceAction;
use App\Models\Attendance;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class AttendanceUpdateOrCreate extends Component
{
    use Toast;

    public Attendance   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Attendance $attendance): void
    {
        $this->model = $attendance;
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
            UpdateAttendanceAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('attendance.model')]),
                redirectTo: route('admin.attendance.index')
            );
        } else {
            StoreAttendanceAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('attendance.model')]),
                redirectTo: route('admin.attendance.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.attendance.attendance-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.attendance.index'), 'label' => trans('general.page.index.title', ['model' => trans('attendance.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('attendance.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.attendance.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
