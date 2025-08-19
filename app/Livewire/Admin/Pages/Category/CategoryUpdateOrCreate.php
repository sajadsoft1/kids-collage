<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Category;

use App\Actions\Category\StoreCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Enums\CategoryTypeEnum;
use App\Livewire\Traits\LivewireCrudHealperTrait;
use App\Livewire\Traits\SeoOptionTrait;
use App\Models\Category;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class CategoryUpdateOrCreate extends Component
{
    use LivewireCrudHealperTrait, SeoOptionTrait, Toast, WithFileUploads;

    public Category $model;
    public ?string $title       = '';
    public ?string $description = '';
    public bool $published      = false;
    public ?string $body        = '';
    public ?string $type        = CategoryTypeEnum::BLOG->value;
    public $image;

    public function mount(Category $category): void
    {
        $this->model = $category;

        if ($this->model->id) {
            $this->mountStaticFields();
            $this->title       = $this->model->title;
            $this->description = $this->model->description;
            $this->body        = $this->model->body;
            $this->type        = $this->model->type->value;
            $this->published   = (bool) $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return array_merge($this->seoOptionRules(), [
            'title'       => 'required|string',
            'description' => 'required|string',
            'slug'        => 'required|string|unique:categories,slug,' . $this->model->id,
            'published'   => 'required|boolean',
            'type'        => 'required|string|in:' . implode(',', CategoryTypeEnum::values()),
            'image'       => 'nullable|image|max:2048',
        ]);
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateCategoryAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('category.model')]),
                redirectTo: route('admin.category.index')
            );
        } else {
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
                ['link' => route('admin.category.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
