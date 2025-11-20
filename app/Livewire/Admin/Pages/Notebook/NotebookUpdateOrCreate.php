<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Notebook;

use App\Actions\Notebook\StoreNotebookAction;
use App\Actions\Notebook\UpdateNotebookAction;
use App\Models\Notebook;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class NotebookUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public Notebook $model;
    public string $title = '';
    public string $body = '';
    public array $tags = [];

    public function mount(Notebook $notebook): void
    {
        $this->model = $notebook;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->body = $this->model->body;
            $this->tags = $this->model->tags;
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string',
            'body' => 'required|string',
            'tags' => 'nullable',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateNotebookAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('notebook.model')]),
                redirectTo: route('admin.notebook.index')
            );
        } else {
            StoreNotebookAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('notebook.model')]),
                redirectTo: route('admin.notebook.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.notebook.notebook-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.notebook.index'), 'label' => trans('general.page.index.title', ['model' => trans('notebook.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('notebook.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.notebook.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
