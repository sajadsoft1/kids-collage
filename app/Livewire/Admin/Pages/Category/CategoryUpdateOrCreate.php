<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Category;

use App\Actions\Category\StoreCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Enums\CategoryTypeEnum;
use App\Helpers\StringHelper;
use App\Models\Category;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CategoryUpdateOrCreate extends Component
{
    use CrudHelperTrait, Toast, WithFileUploads;

    public Category $model;
    public ?string $title       = '';
    public ?string $description = '';
    public bool $published      = false;
    public ?string $body        = '';
    #[Url]
    public ?string $type        = CategoryTypeEnum::BLOG->value;
    public $image;

    public function mount(Category $category): void
    {
        $this->model = $category;

        if ($this->model->id) {
            $this->title       = $this->model->title;
            $this->description = $this->model->description;
            $this->body        = $this->model->body;
            $this->type        = $this->model->type->value;
            $this->published   = (bool) $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string',
            'description' => 'required|string',
            'published'   => 'required|boolean',
            'type'        => 'required|string|in:' . implode(',', CategoryTypeEnum::values()),
            'image'       => 'nullable|image|max:2048',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            $payload['type'] = $this->model->type->value;
            UpdateCategoryAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('category.model')]),
                redirectTo: route('admin.category.index')
            );
        } else {
            $payload['slug'] = StringHelper::slug($this->title);
            StoreCategoryAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('category.model')]),
                redirectTo: route('admin.category.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.category.category-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.category.index'), 'label' => trans('general.page.index.title', ['model' => trans('category.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('category.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.category.index', ['type' => $this->type]), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
