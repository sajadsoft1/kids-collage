<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Event;

use App\Actions\Event\StoreEventAction;
use App\Actions\Event\UpdateEventAction;
use App\Enums\CategoryTypeEnum;
use App\Helpers\StringHelper;
use App\Models\Category;
use App\Models\Event;
use App\Traits\CrudHelperTrait;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class EventUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast, WithFileUploads;

    public Event $model;
    public ?string $title = '';
    public ?string $description = '';
    public ?string $body = '';
    public ?string $location = '';
    public int $capacity = 1;
    public int $price = 0;
    public bool $is_online = false;
    public bool $published = false;
    public $published_at = '';
    public $start_date = '';
    public $end_date = '';
    public array $tags = [];
    public $image;

    public function mount(Event $event): void
    {
        $this->model = $event;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->description = $this->model->description;
            $this->body = $this->model->body;
            $this->published = $this->model->published->asBoolean();
            $this->published_at = $this->model->published_at;
            $this->start_date = $this->model->start_date;
            $this->end_date = $this->model->end_date;
            $this->location = $this->model->location;
            $this->price = $this->model->price;
            $this->capacity = $this->model->capacity;
            $this->is_online = $this->model->is_online->asBoolean();
            $this->tags = $this->model->tags()->pluck('name')->toArray();
        } else {
            $this->published_at = Carbon::now();
            $this->start_date = Carbon::now();
            $this->end_date = Carbon::now();
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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|file|mimes:png,jpg,jpeg|max:4096',
            'tags' => 'nullable|array',
            'location' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'is_online' => 'required|boolean',
        ];
    }

    public function submit(): void
    {
        $category = Category::where('type', CategoryTypeEnum::EVENT)->first();
        $payload = $this->validate();
        if ($this->model->id) {
            $payload['category_id'] = $category->id;
            UpdateEventAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('event.model')]),
                redirectTo: route('admin.event.index')
            );
        } else {
            $payload['slug'] = StringHelper::slug($this->title);
            $payload['category_id'] = $category->id;
            StoreEventAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('event.model')]),
                redirectTo: route('admin.event.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.event.event-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.event.index'), 'label' => trans('general.page.index.title', ['model' => trans('event.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('event.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.event.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
