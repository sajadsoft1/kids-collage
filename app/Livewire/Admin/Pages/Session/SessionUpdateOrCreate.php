<?php

namespace App\Livewire\Admin\Pages\Session;

use App\Actions\Session\StoreSessionAction;
use App\Actions\Session\UpdateSessionAction;
use App\Models\Session;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class SessionUpdateOrCreate extends Component
{
    use Toast;

    public Session   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Session $session): void
    {
        $this->model = $session;
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
            UpdateSessionAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('session.model')]),
                redirectTo: route('admin.session.index')
            );
        } else {
            StoreSessionAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('session.model')]),
                redirectTo: route('admin.session.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.session.session-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.session.index'), 'label' => trans('general.page.index.title', ['model' => trans('session.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('session.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.session.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
