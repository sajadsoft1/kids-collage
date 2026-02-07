<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\TicketChat;

use Karnoweb\TicketChat\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class TicketDepartmentIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $departments = Department::query()
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.pages.ticket-chat.ticket-department-index', [
            'departments' => $departments,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.ticket-chat.index'), 'label' => __('ticket_chat.title')],
                ['label' => __('ticket_chat.departments_manage')],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.ticket-chat.index'), 'icon' => 's-arrow-left', 'label' => __('ticket_chat.back_to_list')],
                ['link' => route('admin.ticket-chat.departments.create'), 'icon' => 's-plus', 'label' => __('general.page.create.title', ['model' => __('ticket_chat.department')])],
            ],
        ]);
    }
}
