<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Page;

use App\Actions\Page\StorePageAction;
use App\Actions\Page\UpdatePageAction;
use App\Enums\PageTypeEnum;
use App\Helpers\StringHelper;
use App\Models\Page;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

class PageUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast, WithFileUploads;

    public Page $model;
    public ?string $title = '';
    public ?string $body = '';
    public ?string $type = PageTypeEnum::RULES->value;
    public $image;

    public function mount(Page $page): void
    {
        $this->model = $page;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->body = $this->model->body;
            $this->type = $this->model->type->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|in:' . implode(',', PageTypeEnum::values()),
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();

        if ($this->model->id) {
            try {
                UpdatePageAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('page.model')]),
                    redirectTo: route('admin.page.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            $payload['slug'] = StringHelper::slug($this->title);

            try {
                StorePageAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('page.model')]),
                    redirectTo: route('admin.page.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.page.page-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.page.index'), 'label' => trans('general.page.index.title', ['model' => trans('page.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('page.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.page.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
