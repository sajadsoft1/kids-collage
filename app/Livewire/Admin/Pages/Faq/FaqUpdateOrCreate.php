<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Faq;

use App\Actions\Faq\StoreFaqAction;
use App\Actions\Faq\UpdateFaqAction;
use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use App\Models\Faq;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class FaqUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public Faq $model;
    public ?string $title        = '';
    public ?string $description  = '';
    public bool $published       = false;
    public bool $favorite        = false;
    public int $ordering         = 1;
    public ?string $published_at = '';
    public ?int $category_id;
    public array $categories;

    public function mount(Faq $faq): void
    {
        $this->model     = $faq;
        $this->categories=Category::where('published', true)->where('type', CategoryTypeEnum::FAQ->value)->get()->map(function ($category) {
            return [
                'id'    => $category->id,
                'title' => $category->title,
            ];
        })->toArray();
        $this->category_id=count($this->categories) > 0 ? $this->categories[0]['id'] : null;
        if ($this->model->id) {
            $this->title        = $this->model->title;
            $this->description  = $this->model->description;
            $this->published    = (bool) $this->model->published->value;
            $this->favorite     = (bool) $this->model->favorite->value;
            $this->ordering     = $this->model->ordering;
            $this->category_id  = $this->model->category_id;
            $this->published_at = $this->setPublishedAt($this->model->published_at);
        } else {
            // For new faqs, ensure published is properly initialized
            $this->published = false;
        }
    }

    protected function rules(): array
    {
        return [
            'title'        => 'required|string',
            'description'  => 'required|string',
            'published'    => 'required',
            'favorite'     => 'required',
            'ordering'     => 'required',
            'category_id'  => 'required',
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
        ];
    }

    public function submit(): void
    {
        $payload = $this->normalizePublishedAt($this->validate());
        if ($this->model->id) {
            try {
                UpdateFaqAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('faq.model')]),
                    redirectTo: route('admin.faq.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreFaqAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('faq.model')]),
                    redirectTo: route('admin.faq.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.faq.faq-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.faq.index'), 'label' => trans('general.page.index.title', ['model' => trans('faq.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('faq.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.faq.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
