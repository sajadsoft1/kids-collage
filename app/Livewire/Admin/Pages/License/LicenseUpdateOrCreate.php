<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\License;

use App\Actions\License\StoreLicenseAction;
use App\Actions\License\UpdateLicenseAction;
use App\Helpers\StringHelper;
use App\Models\License;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Throwable;

class LicenseUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast, WithFileUploads;

    public License $model;
    public ?string $title = '';
    public ?string $description = '';
    public $image;

    public function mount(License $license): void
    {
        $this->model = $license;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->description = $this->model->description;
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255|min:2',
            'description' => 'required|string|max:255',
            'image' => 'nullable|file|mimes:png,jpg,jpeg|max:4096',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            try {
                UpdateLicenseAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('license.model')]),
                    redirectTo: route('admin.license.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            $payload['slug'] = StringHelper::slug($this->title);
            
            try {
                StoreLicenseAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('license.model')]),
                    redirectTo: route('admin.license.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.license.license-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.license.index'), 'label' => trans('general.page.index.title', ['model' => trans('license.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('license.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.license.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
