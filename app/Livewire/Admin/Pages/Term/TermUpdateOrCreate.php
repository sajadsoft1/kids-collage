<?php

namespace App\Livewire\Admin\Pages\Term;

use App\Actions\Term\StoreTermAction;
use App\Actions\Term\UpdateTermAction;
use App\Models\Term;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class TermUpdateOrCreate extends Component
{
    use Toast;

    public Term   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Term $term): void
    {
        $this->model = $term;
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
            UpdateTermAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('term.model')]),
                redirectTo: route('admin.term.index')
            );
        } else {
            StoreTermAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('term.model')]),
                redirectTo: route('admin.term.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.term.term-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.term.index'), 'label' => trans('general.page.index.title', ['model' => trans('term.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('term.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.term.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
