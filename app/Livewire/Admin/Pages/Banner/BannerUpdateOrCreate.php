<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Banner;

use App\Actions\Banner\StoreBannerAction;
use App\Actions\Banner\UpdateBannerAction;
use App\Enums\BannerSizeEnum;
use App\Helpers\Constants;
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
            $this->published_at = $this->model->published_at?->format(Constants::DEFAULT_DATE_FORMAT);
            $this->ratio        = $this->calculateRatio($this->size);
        } else {
            // For new banners, ensure published is properly initialized
            $this->published = false;
            $this->ratio     = 1;
        }
    }

    protected function rules(): array
    {
        return [
            'title'        => 'required|string',
            'description'  => 'nullable|string',
            'published'    => 'required',
            'published_at' => 'required_if:published,false|nullable|date',
            'size'         => ['required', Rule::in(BannerSizeEnum::values())],
            'image'        => ['image', 'max:2048', $this->size != $this->oldSize ? 'required' : 'nullable'], // 1MB Max
        ];
    }

    public function updatedSize($value): void
    {
        $this->image = null;
        $this->ratio = $this->calculateRatio($value);
    }

    protected function calculateRatio(string $size): float
    {
        return match ($size) {
            BannerSizeEnum::S1X1->value  => 1,
            BannerSizeEnum::S16X9->value => 16 / 9,
            BannerSizeEnum::S4X3->value  => 4 / 3,
            default                      => 1,
        };
    }

    public function submit(): void
    {
        $payload =$this->validate();
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
