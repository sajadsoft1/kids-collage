<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Tag;

use App\Actions\Tag\StoreTagAction;
use App\Actions\Tag\UpdateTagAction;
use App\Enums\TagTypeEnum;
use App\Helpers\StringHelper;
use App\Livewire\Traits\SeoOptionTrait;
use App\Models\Tag;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class TagUpdateOrCreate extends Component
{
    use SeoOptionTrait;
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
            $this->mountStaticFields();
            $this->name         = $this->model->name;
            $this->description  = $this->model->description;
            $this->body         = $this->model->body;
            $this->type         = $this->model->type;
            $this->order_column = $this->model->order_column;
        }
    }

    public function updatedName($value): void
    {
        if ( ! $this->model->id || empty($this->seo_title)) {
            $this->seo_title = $value;
        }
        if ( ! $this->model->id || empty($this->slug)) {
            $this->slug = StringHelper::slug($value);
        }
    }

    protected function rules(): array
    {
        return array_merge($this->seoOptionRules(), [
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:255'],
            'body'         => ['nullable', 'string'],
            'type'         => ['nullable', 'string', Rule::in(TagTypeEnum::values())],
            'order_column' => ['nullable', 'integer'],
            'image'        => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:1024'],
        ]);
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateTagAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('tag.model')]),
                redirectTo: route('admin.tag.index')
            );
        } else {
            StoreTagAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('tag.model')]),
                redirectTo: route('admin.tag.index')
            );
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
