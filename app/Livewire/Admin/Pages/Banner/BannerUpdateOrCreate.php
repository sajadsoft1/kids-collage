<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Banner;

use App\Actions\Banner\StoreBannerAction;
use App\Actions\Banner\UpdateBannerAction;
use App\Enums\BannerSizeEnum;
use App\Models\Banner;
use App\Traits\CrudHelperTrait;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

class BannerUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;
    use WithFileUploads;

    public Banner $model;
    public string $title         = '';
    public ?string $description  = '';
    public string $size          = BannerSizeEnum::S1X1->value;
    public ?string $oldSize      =BannerSizeEnum::S1X1->value;
    public bool $published       = false;
    public ?string $published_at = '';
    public float $ratio          = 1;

    public $image;

    public function mount(Banner $banner): void
    {
        $this->model = $banner;
        if ($this->model->id) {
            $this->title        = $this->model->title;
            $this->description  = $this->model->description;
            $this->published    = (bool) $this->model->published->value;
            $this->size         = $this->model->size->value;
            $this->oldSize      = $this->model->size->value;
            $this->published_at = $this->setPublishedAt($this->model->published_at);
        } else {
            // For new banners, ensure published is properly initialized
            $this->published = false;
        }
    }

    protected function rules(): array
    {
        return [
            'title'        => 'required|string',
            'description'  => 'nullable|string',
            'published'    => 'required',
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
            'size'         => ['required', Rule::in(BannerSizeEnum::values())],
            'image'        => ['image', 'max:2048', $this->size != $this->oldSize ? 'required' : 'nullable'], // 1MB Max
        ];
    }

    public function updatedSize($value)
    {
        $this->image=null;
        if ($value === BannerSizeEnum::S1X1->value) {
            $this->ratio= 1;
        } elseif ($value === BannerSizeEnum::S16X9->value) {
            $this->ratio= 16 / 9;
        } else {
            $this->ratio= 4 / 3;
        }
    }

    public function submit(): void
    {
        $payload = $this->normalizePublishedAt($this->validate());
        if ($this->model->id) {
            try {
                UpdateBannerAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('banner.model')]),
                    redirectTo: route('admin.banner.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreBannerAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('banner.model')]),
                    redirectTo: route('admin.banner.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.banner.banner-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.banner.index'), 'label' => trans('general.page.index.title', ['model' => trans('banner.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('banner.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.banner.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
