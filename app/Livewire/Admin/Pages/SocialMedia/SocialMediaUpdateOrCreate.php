<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\SocialMedia;

use App\Actions\SocialMedia\StoreSocialMediaAction;
use App\Actions\SocialMedia\UpdateSocialMediaAction;
use App\Enums\SocialMediaPositionEnum;
use App\Models\SocialMedia;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

class SocialMediaUpdateOrCreate extends Component
{
    use Toast;
    use WithFileUploads;

    public SocialMedia $model;
    public string $title    = '';
    public bool $published  = false;
    public string $position = SocialMediaPositionEnum::HEADER->value;
    public string $link     = '';
    public int $ordering    = 1;
    public $image;

    public function mount(SocialMedia $socialMedia): void
    {
        $this->model = $socialMedia;
        if ($this->model->id) {
            $this->title     = $this->model->title;
            $this->link      = $this->model->link;
            $this->ordering  = $this->model->ordering;
            $this->position  = $this->model->position->value;
            $this->published = (bool) $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title'     => 'required|string',
            'link'      => 'required|string',
            'ordering'  => 'required|numeric|min:1',
            'position'  => 'required|string',
            'published' => 'required',
            'image'     => ['required', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();

        if ($this->model->id) {
            try {
                UpdateSocialMediaAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('socialMedia.model')]),
                    redirectTo: route('admin.social-media.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreSocialMediaAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('socialMedia.model')]),
                    redirectTo: route('admin.social-media.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.socialMedia.socialMedia-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.social-media.index'), 'label' => trans('general.page.index.title', ['model' => trans('socialMedia.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('socialMedia.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.social-media.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
