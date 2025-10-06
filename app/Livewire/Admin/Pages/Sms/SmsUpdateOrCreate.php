<?php

namespace App\Livewire\Admin\Pages\Sms;

use App\Actions\Sms\StoreSmsAction;
use App\Actions\Sms\UpdateSmsAction;
use App\Models\Sms;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class SmsUpdateOrCreate extends Component
{
    use Toast;

    public Sms   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(Sms $sms): void
    {
        $this->model = $sms;
        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->description = $this->model->description;
            $this->published = $this->model->published->value;
        }
    }

    protected function rules(): array
    {
        return [
            'title'       => 'required|string',
            'description' => 'required|string',
            'published'   => 'required'
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            UpdateSmsAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('sms.model')]),
                redirectTo: route('admin.sms.index')
            );
        } else {
            StoreSmsAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('sms.model')]),
                redirectTo: route('admin.sms.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.sms.sms-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.sms.index'), 'label' => trans('general.page.index.title', ['model' => trans('sms.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('sms.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.sms.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
