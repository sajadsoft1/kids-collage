<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\TicketChat;

use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Karnoweb\TicketChat\Models\Department;
use Livewire\Component;
use Mary\Traits\Toast;

class TicketDepartmentUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public Department $model;

    public string $name = '';

    public string $description = '';

    public bool $is_active = true;

    public int $order = 0;

    public function mount(?Department $ticket_department = null): void
    {
        $this->model = $ticket_department ?? new Department;
        if ($this->model->id) {
            $this->name = $this->model->name;
            $this->description = (string) $this->model->description;
            $this->is_active = $this->model->is_active;
            $this->order = (int) $this->model->order;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'is_active' => 'required|boolean',
            'order' => 'required|integer|min:0',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->model->id) {
            $this->model->update($payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => __('ticket_chat.department')]),
                redirectTo: route('admin.ticket-chat.departments.index')
            );
        } else {
            Department::query()->create($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => __('ticket_chat.department')]),
                redirectTo: route('admin.ticket-chat.departments.index')
            );
        }
    }

    public function render(): View
    {
        $editMode = (bool) $this->model->id;

        return view('livewire.admin.pages.ticket-chat.ticket-department-update-or-create', [
            'edit_mode' => $editMode,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.ticket-chat.index'), 'label' => __('ticket_chat.title')],
                ['link' => route('admin.ticket-chat.departments.index'), 'label' => __('ticket_chat.departments_manage')],
                ['label' => $editMode ? trans('general.page.edit.title', ['model' => __('ticket_chat.department')]) : trans('general.page.create.title', ['model' => __('ticket_chat.department')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.ticket-chat.departments.index'), 'icon' => 's-arrow-left', 'label' => __('ticket_chat.back_to_list')],
            ],
        ]);
    }
}
