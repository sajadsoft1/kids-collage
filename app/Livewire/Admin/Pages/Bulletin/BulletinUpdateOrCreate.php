<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Bulletin;

use App\Actions\Bulletin\StoreBulletinAction;
use App\Actions\Bulletin\UpdateBulletinAction;
use App\Enums\BooleanEnum;
use App\Enums\CategoryTypeEnum;
use App\Helpers\StringHelper;
use App\Models\Bulletin;
use App\Models\Category;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

class BulletinUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast, WithFileUploads;

    public Bulletin $model;
    public ?string $title = '';
    public ?string $description = '';
    public ?string $body = '';
    public bool $published = false;
    public $published_at = '';
    public array $categories = [];
    public array $tags = [];
    public int $category_id = 1;
    public $image;

    public function mount(Bulletin $bulletin): void
    {
        $this->model = $bulletin;
        $this->categories = Category::where('type', CategoryTypeEnum::BULLETIN)
            ->where('published', BooleanEnum::ENABLE)
            ->get()
            ->map(fn ($item) => ['name' => $item->title, 'id' => $item->id])->toArray();

        $this->published_at = now()->format('Y-m-d');
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->description = $this->model->description;
            $this->body = $this->model->body;
            $this->published = $this->model->published->asBoolean();
            $this->published_at = $this->model->published_at;
            $this->category_id = $this->model->category_id;
            $this->tags = $this->model->tags()->pluck('name')->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255|min:2',
            'description' => 'required|string|max:255',
            'body' => 'required|string',
            'published' => 'required|boolean',
            'published_at' => 'nullable|date',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|file|mimes:png,jpg,jpeg|max:4096',
            'tags' => 'nullable|array',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            try {
                UpdateBulletinAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('bulletin.model')]),
                    redirectTo: route('admin.bulletin.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            $payload['user_id'] = auth()->id();
            $payload['slug'] = StringHelper::slug($this->title);
            
            try {
                StoreBulletinAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('bulletin.model')]),
                    redirectTo: route('admin.bulletin.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.bulletin.bulletin-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.bulletin.index'), 'label' => trans('general.page.index.title', ['model' => trans('bulletin.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('bulletin.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.bulletin.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
