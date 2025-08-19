<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Slider;

use App\Actions\Slider\StoreSliderAction;
use App\Actions\Slider\UpdateSliderAction;
use App\Models\Category;
use App\Models\Slider;
use App\Models\Tag;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class SliderUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;
    use WithFileUploads;

    public Slider $model;
    public ?string $title        = '';
    public ?string $description  = '';
    public int $ordering         = 1;
    public ?string $published_at = '';
    public ?string $expired_at   = '';
    public ?string $link         = '';
    public int $has_timer        = 0;
    public ?string $timer_start  = '';
    public bool $published       = false;
    public $image;
    public $roles = [];

    public function mount(Slider $slider): void
    {
        $this->model = $slider;
        if ($this->model->id) {
            $this->title        = $this->model->title;
            $this->description  = $this->model->description;
            $this->published    = (bool) $this->model->published->value;
            $this->published    = (bool) $this->model->published->value;
            $this->ordering     = $this->model->ordering;
            $this->published_at = $this->setPublishedAt($this->model->published_at);
            $this->expired_at   = $this->setPublishedAt($this->model->expired_at);
            $this->link         = $this->model->link;
            $this->has_timer    = $this->model->has_timer->value;
            $this->timer_start  = $this->setPublishedAt($this->model->timer_start);
            $this->roles        = $this->model->references
                ->groupBy('morphable_type')
                ->map(fn ($references, $type) => [
                    'type'  => $type,
                    'value' => $references->pluck('morphable_id')->toArray(),
                ])
                ->values()
                ->toArray();
        } else {
            // For new sliders, ensure published is properly initialized
            $this->published = false;
        }
    }

    protected function rules(): array
    {
        return [
            'title'         => ['required', 'string'],
            'description'   => ['required', 'string'],
            'published'     => ['required', 'boolean'],
            'ordering'      => ['required', 'numeric'],
            'published_at'  => [
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
            'expired_at'    => ['nullable', 'required_if:has_timer,true', 'date', 'after:now'],
            'link'          => ['nullable', 'url'],
            'has_timer'     => ['nullable', 'boolean'],
            'timer_start'   => ['nullable', 'required_if:has_timer,true', 'date', 'before:expired_at'],
            'image'         => ['image', 'max:1024', isset($this->model->id) ? 'nullable' : 'required'], // 1MB Max
            'roles'         => ['required', 'array'],
            'roles.*.type'  => ['required', 'string'],
            'roles.*.value' => ['required', 'array'],
        ];
    }

    public function submit(): void
    {
        $payload = $this->normalizePublishedAt($this->validate());
        $payload = $this->normalizePublishedAt($payload, 'expired_at');
        $payload = $this->normalizePublishedAt($payload, 'timer_start');
        if ($this->model->id) {
            UpdateSliderAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('slider.model')]),
                redirectTo: route('admin.slider.index')
            );
        } else {
            StoreSliderAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('slider.model')]),
                redirectTo: route('admin.slider.index')
            );
        }
    }

    public function deleteRole($index): void
    {
        unset($this->roles[$index]);
    }

    public function addRole(): void
    {
        $this->roles[] = [
            'type'  => 'role',
            'value' => [],
        ];
    }

    public function render(): View
    {
        return view('livewire.admin.pages.slider.slider-update-or-create', [
            'edit_mode'          => $this->model->id,
            'reference_type'     => [
                ['id' => '', 'name' => 'Please select reference type'],
                ['id' => Category::class, 'name' => 'Category'],
                ['id' => Tag::class, 'name' => 'Tag'],
            ],
            'categories'         => Category::all()->map(fn ($category) => ['id' => $category->id, 'name' => $category->title]),
            'tags'               => Tag::all()->map(fn ($tag) => ['id' => $tag->id, 'name' => $tag->name]),
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.slider.index'), 'label' => trans('general.page.index.title', ['model' => trans('slider.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('slider.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.slider.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
