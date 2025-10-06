<?php

namespace App\Livewire\Admin\Pages\NotificationTemplate;

use App\Actions\NotificationTemplate\StoreNotificationTemplateAction;
use App\Actions\NotificationTemplate\UpdateNotificationTemplateAction;
use App\Models\NotificationTemplate;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class NotificationTemplateUpdateOrCreate extends Component
{
    use Toast;

    public NotificationTemplate   $model;
    public string $title       = '';
    public string $description = '';
    public bool   $published   = false;

    public function mount(NotificationTemplate $notificationTemplate): void
    {
        $this->model = $notificationTemplate;
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
            UpdateNotificationTemplateAction::run($this->model, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('notificationTemplate.model')]),
                redirectTo: route('admin.notificationTemplate.index')
            );
        } else {
            StoreNotificationTemplateAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('notificationTemplate.model')]),
                redirectTo: route('admin.notificationTemplate.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.notificationTemplate.notificationTemplate-update-or-create', [
            'edit_mode'          => $this->model->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.notificationTemplate.index'), 'label' => trans('general.page.index.title', ['model' => trans('notificationTemplate.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('notificationTemplate.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.notificationTemplate.index'), 'icon' => 's-arrow-left']
            ],
        ]);
    }
}
