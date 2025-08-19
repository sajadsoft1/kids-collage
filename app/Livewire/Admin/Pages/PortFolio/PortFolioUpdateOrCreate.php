<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\PortFolio;

use App\Actions\PortFolio\StorePortFolioAction;
use App\Actions\PortFolio\UpdatePortFolioAction;
use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use App\Models\PortFolio;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class PortFolioUpdateOrCreate extends Component
{
    use CrudHelperTrait, Toast, WithFileUploads;

    public PortFolio $model;
    public ?string $title       = '';
    public ?string $description = '';
    public ?string $body        = '';

    public bool $published         = false;
    public ?string $published_at   = '';
    public int $category_id        = 1;
    public ?int $creator_id        = 1;
    public array $categories       = [];
    public ?string $execution_date = '';
    public $image;

    public function mount(PortFolio $portFolio): void
    {
        $this->model      = $portFolio;
        $this->categories = Category::where('type', CategoryTypeEnum::PORTFOLIO->value)->get()->map(fn ($item) => ['name' => $item->title, 'id' => $item->id])->toArray();

        if ($this->model->id) {
            $this->title       = $this->model->title;
            $this->description = $this->model->description;
            $this->body        = $this->model->body;

            $this->category_id = $this->model->category_id;
            $this->creator_id  = $this->model->creator_id;

            $this->published      = (bool) $this->model->published->value;
            $this->published_at   = $this->setPublishedAt($this->model->published_at);
            $this->execution_date = $this->setPublishedAt($this->model->execution_date);
        } else {
            // For new portfolios, ensure published is properly initialized
            $this->published = false;
        }
    }

    protected function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['required', 'string'],
            'body'           => ['required', 'string'],
            'execution_date' => ['required', 'date'],
            'creator_id'     => ['nullable'],
            'category_id'    => ['required', 'exists:categories,id,type,portfolio'],
            'image'          => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'tags'           => ['nullable', 'array'],
            'tags.*'         => ['required', 'string', 'max:255'],
            'published'      => ['required', 'boolean'],
            'published_at'   => [
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
        ];
    }

    public function submit(): void
    {
        $payload = $this->normalizePublishedAt($this->validate());

        if ($this->model->id) {
            UpdatePortFolioAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('portFolio.model')]),
                redirectTo: route('admin.portFolio.index')
            );
        } else {
            StorePortFolioAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('portFolio.model')]),
                redirectTo: route('admin.portFolio.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.portFolio.portFolio-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.portFolio.index'), 'label' => trans('general.page.index.title', ['model' => trans('portFolio.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('portFolio.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.portFolio.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
