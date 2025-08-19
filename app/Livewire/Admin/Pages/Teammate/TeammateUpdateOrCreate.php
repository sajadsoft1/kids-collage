<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Teammate;

use App\Actions\Teammate\StoreTeammateAction;
use App\Actions\Teammate\UpdateTeammateAction;
use App\Models\Teammate;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class TeammateUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;
    use WithFileUploads;

    public Teammate $model;
    public string $title        = '';
    public ?string $description = '';
    public ?string $bio         = '';
    public string $position     = '';
    public ?string $email       = '';
    public ?string $birthday;
    public bool $published = false;
    public $image;
    //    public $bio_image;

    public function mount(Teammate $teammate): void
    {
        $this->model = $teammate;
        if ($this->model->id) {
            $this->title       = $this->model->title;
            $this->description = $this->model->description;
            $this->position    = $this->model->position;
            $this->email       = $this->model->extra_attributes->get('email');
            $this->birthday    = $this->setPublishedAt($this->model->birthday);
            $this->bio         = $this->model->bio;
            $this->published   = (bool) $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string',
            'description' => 'required|string',
            'bio'         => ['nullable', 'string'],
            'position'    => ['required', 'string'],
            'birthday'    => ['required', 'date'],
            'published'   => ['sometimes', 'boolean'],
            'image'       => ['nullable', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
            //            'bio_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
            'email'       => ['nullable', 'email'],
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        $payload = $this->normalizePublishedAt($payload, 'birthday');
        if ($this->model->id) {
            UpdateTeammateAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('teammate.model')]),
                redirectTo: route('admin.teammate.index')
            );
        } else {
            StoreTeammateAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('teammate.model')]),
                redirectTo: route('admin.teammate.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.teammate.teammate-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.teammate.index'), 'label' => trans('general.page.index.title', ['model' => trans('teammate.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('teammate.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.teammate.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
