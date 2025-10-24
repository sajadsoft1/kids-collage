<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Tag;

use App\Actions\Tag\StoreTagAction;
use App\Actions\Tag\UpdateTagAction;
use App\Enums\TagTypeEnum;
use App\Helpers\StringHelper;
use App\Models\Tag;
use App\Traits\CrudHelperTrait;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

class TagUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;
    use WithFileUploads;

    public Tag $model;
    public ?string $name         = '';
    public ?string $description  = '';
    public ?string $body         = '';
    public ?string $type         = '';
    public $image                = '';
    public ?int $order_column    = 1;

    public function mount(Tag $tag): void
    {
        $this->model = $tag;
        if ($this->model->id) {
            $this->name         = $this->model->getTranslation('name', app()->getLocale());
            $this->description  = $this->model->description;
            $this->body         = $this->model->body;
            $this->type         = $this->model->type;
            $this->order_column = $this->model->order_column;
        }
    }

    protected function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:255'],
            'body'         => ['nullable', 'string'],
            'type'         => ['nullable', 'string', Rule::in(TagTypeEnum::values())],
            'order_column' => ['nullable', 'integer'],
            'image'        => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:1024'],
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();

        if ($this->model->id) {
            try {
                UpdateTagAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('tag.model')]),
                    redirectTo: route('admin.tag.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            $payload['slug'] = StringHelper::slug($this->name);

            try {
                StoreTagAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('tag.model')]),
                    redirectTo: route('admin.tag.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.tag.tag-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.tag.index'), 'label' => trans('general.page.index.title', ['model' => trans('tag.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('tag.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.tag.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
