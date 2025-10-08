<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Blog;

use App\Actions\Blog\StoreBlogAction;
use App\Actions\Blog\UpdateBlogAction;
use App\Enums\BooleanEnum;
use App\Enums\CategoryTypeEnum;
use App\Helpers\StringHelper;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class BlogUpdateOrCreate extends Component
{
    use Toast, WithFileUploads;

    public Blog $model;
    public ?string $title           = '';
    public ?string $description     = '';
    public ?string $body            = '';
    public bool $published          = false;
    public $published_at            = '';
    public array $categories        = [];
    public array $tags              = [];
    public int $category_id         = 1;
    public $image;

    public function mount(Blog $blog): void
    {
        $this->model      = $blog;
        $this->categories = Category::where('type', CategoryTypeEnum::BLOG)
            ->where('published', BooleanEnum::ENABLE)
            ->get()
            ->map(fn ($item) => ['name' => $item->title, 'id' => $item->id])->toArray();

        $this->published_at = now()->format('Y-m-d');
        if ($this->model->id) {
            $this->title        = $this->model->title;
            $this->description  = $this->model->description;
            $this->body         = $this->model->body;
            $this->published    = $this->model->published->asBoolean();
            $this->published_at = $this->model->published_at;
            $this->category_id  = $this->model->category_id;
            $this->tags         = $this->model->tags()->pluck('name')->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'title'        => 'required|string|max:255|min:2',
            'description'  => 'required|string|max:255',
            'body'         => 'required|string',
            'published'    => 'required|boolean',
            'published_at' => 'nullable|date',
            'category_id'  => 'required|exists:categories,id,type,blog',
            'image'        => 'nullable|file|mimes:png,jpg,jpeg|max:4096',
            'tags'         => 'nullable|array',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateBlogAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('blog.model')]),
                redirectTo: route('admin.blog.index')
            );
        } else {
            $payload['slug'] = StringHelper::slug($this->title);
            StoreBlogAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('blog.model')]),
                redirectTo: route('admin.blog.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.blog.blog-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.blog.index'), 'label' => trans('general.page.index.title', ['model' => trans('blog.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('blog.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.blog.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
