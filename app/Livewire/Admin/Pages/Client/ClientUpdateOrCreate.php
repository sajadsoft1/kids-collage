<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Client;

use App\Actions\Client\StoreClientAction;
use App\Actions\Client\UpdateClientAction;
use App\Models\Client;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

class ClientUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;
    use WithFileUploads;

    public Client $model;
    public string $title = '';
    public string $description = '';
    public bool $published = false;
    public ?string $link = '';
    public $image;

    public function mount(Client $client): void
    {
        $this->model = $client;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->link = $this->model->link;
            $this->published = (bool) $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string',
            'published' => 'required',
            'link' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            try {
                UpdateClientAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('client.model')]),
                    redirectTo: route('admin.client.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreClientAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('client.model')]),
                    redirectTo: route('admin.client.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.client.client-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.client.index'), 'label' => trans('general.page.index.title', ['model' => trans('client.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('client.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.client.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
