<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\SeoOption;

use App\Actions\SeoOption\StoreSeoOptionAction;
use App\Actions\SeoOption\UpdateSeoOptionAction;
use App\Models\SeoOption;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class SeoOptionUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public SeoOption $model;
    public string $title       = '';
    public string $description = '';
    public bool $published     = false;

    public function mount(SeoOption $seoOption): void
    {
        $this->model = $seoOption;
        if ($this->model->id) {
            $this->title       = $this->model->title;
            $this->description = $this->model->description;
            $this->published   = (bool) $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string',
            'description' => 'required|string',
            'published'   => 'required',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            try {
                UpdateSeoOptionAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('seoOption.model')]),
                    redirectTo: route('admin.seoOption.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreSeoOptionAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('seoOption.model')]),
                    redirectTo: route('admin.seoOption.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.seoOption.seoOption-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.seoOption.index'), 'label' => trans('general.page.index.title', ['model' => trans('seoOption.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('seoOption.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.seoOption.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
