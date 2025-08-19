<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Blog;

use App\Actions\Blog\StoreBlogAction;
use App\Actions\Blog\UpdateBlogAction;
use App\Enums\BooleanEnum;
use App\Enums\CategoryTypeEnum;
use App\Livewire\Traits\SeoOptionTrait;
use App\Models\Blog;
use App\Models\Category;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class BlogUpdateOrCreate extends Component
{
    use CrudHelperTrait, SeoOptionTrait, Toast, WithFileUploads;

    public Blog $model;
    public ?string $title           = '';
    public ?string $description     = '';
    public ?string $body            = '';
    public bool $published          = false;
    public ?string $published_at    = '';
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

        if ($this->model->id) {
            $this->mountStaticFields();
            $this->title        = $this->model->title;
            $this->description  = $this->model->description;
            $this->body         = $this->model->body;
            $this->published    = (bool) $this->model->published->value;
            $this->published    = (bool) $this->model->published->value;
            $this->published_at = $this->setPublishedAt($this->model->published_at);
            $this->category_id  = $this->model->category_id;
            $this->tags         = $this->model->tags()->pluck('name')->toArray();
        } else {
            // For new blogs, ensure published is properly initialized
            $this->published = false;
        }
    }

    protected function rules(): array
    {
        return array_merge($this->seoOptionRules(), [
            'slug'         => 'required|string|unique:blogs,slug,' . $this->model->id,
            'title'        => 'required|string|max:255|min:2',
            'description'  => 'required|string|max:255',
            'body'         => 'nullable|string',
            'published'    => 'required|boolean',
            'published_at' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $carbon = $this->parseTimestamp($value);
                        if ($carbon && $carbon->addMinutes(2)->isBefore(now())) {
                            $fail(trans('slider.exceptions.published_at_after_now'));
                        }
                    }
                },
            ],
            'category_id'  => 'required|exists:categories,id,type,blog',
            'image'        => 'nullable|file|mimes:png,jpg,jpeg|max:4096',
            'tags'         => 'nullable|array',
        ]);
    }

    public function submit(): void
    {
        $payload = $this->normalizePublishedAt($this->validate());
        if ($this->model->id) {
            UpdateBlogAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('blog.model')]),
                redirectTo: route('admin.blog.index')
            );
        } else {
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
