<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Page;

use App\Actions\Page\StorePageAction;
use App\Actions\Page\UpdatePageAction;
use App\Enums\PageTypeEnum;
use App\Livewire\Traits\SeoOptionTrait;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class PageUpdateOrCreate extends Component
{
    use SeoOptionTrait, Toast, WithFileUploads;

    public Page $model;
    public ?string $title = '';
    public ?string $body  = '';
    public ?string $type  = PageTypeEnum::RULES->value;
    public $image;

    public function mount(Page $page): void
    {
        $this->model = $page;
        if ($this->model->id) {
            $this->mountStaticFields();
            $this->title = $this->model->title;
            $this->body  = $this->model->body;
            $this->type  = $this->model->type->value;
        }
    }

    protected function rules(): array
    {
        return array_merge($this->seoOptionRules(), [
            'slug'  => 'required|string|unique:pages,slug,' . $this->model->id,
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
            'type'  => 'required|string|in:' . implode(',', PageTypeEnum::values()),
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);
    }

    public function updatedBody($value): void
    {
        if ( ! $this->model->id || empty($this->seo_description)) {
            $this->seo_description = Str::limit($value, 200);
        }
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdatePageAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('page.model')]),
                redirectTo: route('admin.page.index')
            );
        } else {
            StorePageAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('page.model')]),
                redirectTo: route('admin.page.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.page.page-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.page.index'), 'label' => trans('general.page.index.title', ['model' => trans('page.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('page.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.page.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
