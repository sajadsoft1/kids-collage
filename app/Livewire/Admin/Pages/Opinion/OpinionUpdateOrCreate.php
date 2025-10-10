<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Opinion;

use App\Actions\Opinion\StoreOpinionAction;
use App\Actions\Opinion\UpdateOpinionAction;
use App\Models\Opinion;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

class OpinionUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;
    use WithFileUploads;

    public Opinion $model;
    public string $user_name       = '';
    public string $comment         = '';
    public string $company         = '';
    public bool $published         = false;
    public int $ordering           = 1;
    public ?string $published_at   = '';
    public $image;

    public function mount(Opinion $opinion): void
    {
        $this->model   = $opinion;
        $this->ordering=Opinion::query()->count() + 1;
        if ($this->model->id) {
            $this->user_name    = $this->model->user_name;
            $this->comment      = $this->model->comment;
            $this->company      = $this->model->company;
            $this->published    = (bool) $this->model->published->value;
            $this->ordering     = $this->model->ordering;
            $this->published_at = $this->setPublishedAt($this->model->published_at);
        } else {
            // For new opinions, ensure published is properly initialized
            $this->published = false;
        }
    }

    protected function rules(): array
    {
        return [
            'user_name'    => 'required|string',
            'comment'      => 'required|string',
            'company'      => 'nullable|string',
            'published'    => ['required', 'boolean'],
            'ordering'     => ['required', 'numeric'],
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
            'image'        => 'nullable|image|max:1024',
        ];
    }

    public function submit(): void
    {
        $payload = $this->normalizePublishedAt($this->validate());
        if ($this->model->id) {
            try {
                UpdateOpinionAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('opinion.model')]),
                    redirectTo: route('admin.opinion.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreOpinionAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('opinion.model')]),
                    redirectTo: route('admin.opinion.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.opinion.opinion-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.opinion.index'), 'label' => trans('general.page.index.title', ['model' => trans('opinion.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('opinion.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.opinion.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
