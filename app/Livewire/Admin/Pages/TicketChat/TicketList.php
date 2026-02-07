<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\TicketChat;

use Karnoweb\TicketChat\Enums\TicketStatus;
use Karnoweb\TicketChat\Facades\Ticket;
use Karnoweb\TicketChat\Models\Conversation;
use Karnoweb\TicketChat\Models\Department;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TicketList extends Component
{
    use WithPagination;

    #[Url]
    public string $status = '';

    #[Url]
    public string $priority = '';

    #[Url]
    public string $search = '';

    #[Url]
    public string $department_id = '';

    /** Filter: show only my tickets or all (for agents). */
    public string $filter = 'mine';

    #[Computed]
    public function tickets()
    {
        $query = Conversation::tickets()
            ->with(['department:id,name', 'assignedAgent:id,name,family', 'creator:id,name,family'])
            ->latest('last_message_at');

        if ($this->filter === 'mine') {
            $query->forUser((int) auth()->id());
        } else {
            $query->when(auth()->user()->departments->isNotEmpty(), function ($q) {
                $q->whereIn('department_id', auth()->user()->departments->pluck('id'));
            });
        }

        $query->when($this->status !== '', fn ($q) => $q->byStatus($this->status));
        $query->when($this->priority !== '', fn ($q) => $q->byPriority($this->priority));
        $query->when($this->department_id !== '', fn ($q) => $q->where('department_id', $this->department_id));
        $query->when($this->search !== '', fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'));

        return $query->paginate(10);
    }

    #[Computed]
    public function departmentsList(): array
    {
        return Department::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($d) => ['id' => (string) $d->id, 'name' => $d->name])
            ->values()
            ->all();
    }

    #[Computed]
    public function unreadCount(): int
    {
        return Ticket::unreadCount((int) auth()->id());
    }

    /** @return array{total: int, open: int, in_progress: int, closed: int} */
    #[Computed]
    public function stats(): array
    {
        $base = Conversation::tickets()->forUser((int) auth()->id());

        return [
            'total' => (clone $base)->count(),
            'open' => (clone $base)->byStatus(TicketStatus::OPEN->value)->count(),
            'in_progress' => (clone $base)->whereIn('status', [TicketStatus::PENDING->value, TicketStatus::IN_PROGRESS->value])->count(),
            'closed' => (clone $base)->byStatus(TicketStatus::CLOSED->value)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.pages.ticket-chat.ticket-list', [
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['label' => __('ticket_chat.title')],
            ],
            'breadcrumbsActions' => [
                [
                    'link' => route('admin.ticket-chat.create'),
                    'icon' => 's-plus',
                    'label' => __('ticket_chat.create_ticket'),
                ],
            ],
        ]);
    }
}
