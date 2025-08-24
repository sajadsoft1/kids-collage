<?php

namespace App\Livewire\Admin\Pages\News;

use App\Actions\News\StoreNewsAction;
use App\Actions\News\UpdateNewsAction;
use App\Models\News;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class NewsUpdateOrCreate extends Component
{
    use Toast;

    public News   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(News $news): void
    {
        $this->model = $news;
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
            UpdateNewsAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('news.model')]),
                redirectTo: route('admin.news.index')
            );
        } else {
            StoreNewsAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('news.model')]),
                redirectTo: route('admin.news.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.news.news-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.news.index'), 'label' => trans('general.page.index.title', ['model' => trans('news.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('news.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.news.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
