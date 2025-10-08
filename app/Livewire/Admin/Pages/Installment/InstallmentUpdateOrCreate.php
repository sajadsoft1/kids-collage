<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Installment;

use App\Actions\Installment\StoreInstallmentAction;
use App\Actions\Installment\UpdateInstallmentAction;
use App\Models\Installment;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class InstallmentUpdateOrCreate extends Component
{
    use Toast;

    public Installment $model;
    public string $title       = '';
    public string $description = '';
    public bool $published     = false;

    public function mount(Installment $installment): void
    {
        $this->model = $installment;
        if ($this->model->id) {
            $this->title       = $this->model->title;
            $this->description = $this->model->description;
            $this->published   = $this->model->published->value;
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
            UpdateInstallmentAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('installment.model')]),
                redirectTo: route('admin.installment.index')
            );
        } else {
            StoreInstallmentAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('installment.model')]),
                redirectTo: route('admin.installment.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.installment.installment-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.installment.index'), 'label' => trans('general.page.index.title', ['model' => trans('installment.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('installment.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.installment.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
