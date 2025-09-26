<?php

namespace App\Livewire\Admin\Pages\Bulletin;

use App\Actions\Bulletin\StoreBulletinAction;
use App\Actions\Bulletin\UpdateBulletinAction;
use App\Models\Bulletin;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class BulletinUpdateOrCreate extends Component
{
    use Toast;

    public Bulletin   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Bulletin $bulletin): void
    {
        $this->model = $bulletin;
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
            UpdateBulletinAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('bulletin.model')]),
                redirectTo: route('admin.bulletin.index')
            );
        } else {
            StoreBulletinAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('bulletin.model')]),
                redirectTo: route('admin.bulletin.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.bulletin.bulletin-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.bulletin.index'), 'label' => trans('general.page.index.title', ['model' => trans('bulletin.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('bulletin.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.bulletin.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
