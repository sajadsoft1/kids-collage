<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Resource;

use App\Actions\Resource\StoreResourceAction;
use App\Actions\Resource\UpdateResourceAction;
use App\Models\Resource;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class ResourceUpdateOrCreate extends Component
{
    use Toast;

    public Resource $model;
    public string $title       = '';
    public string $description = '';
    public bool $published     = false;

    public function mount(Resource $resource): void
    {
        $this->model = $resource;
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
            UpdateResourceAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('resource.model')]),
                redirectTo: route('admin.resource.index')
            );
        } else {
            StoreResourceAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('resource.model')]),
                redirectTo: route('admin.resource.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.resource.resource-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.resource.index'), 'label' => trans('general.page.index.title', ['model' => trans('resource.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('resource.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.resource.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
