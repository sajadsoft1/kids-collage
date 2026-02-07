<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\TicketChat;

use App\Traits\HasLearningModal;
use Karnoweb\TicketChat\Enums\TicketStatus;
use Karnoweb\TicketChat\Facades\Ticket;
use Karnoweb\TicketChat\Models\Conversation;
use Karnoweb\TicketChat\Models\Department;
use Karnoweb\TicketChat\Models\Tag;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TicketList extends Component
{
    use HasLearningModal;
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

    /** Filter: assigned to me / unassigned / all (for agents when filter=all). */
    #[Url]
    public string $assigned = '';

    /** Filter by tag(s): one or more tag IDs. */
    #[Url]
    public array $tag_ids = [];

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
            $query->when($this->assigned === 'mine', fn ($q) => $q->where('assigned_to', auth()->id()));
            $query->when($this->assigned === 'unassigned', fn ($q) => $q->whereNull('assigned_to'));
        }

        $query->when($this->status !== '', fn ($q) => $q->byStatus($this->status));
        $query->when($this->priority !== '', fn ($q) => $q->byPriority($this->priority));
        $query->when($this->department_id !== '', fn ($q) => $q->where('department_id', $this->department_id));
        $query->when($this->search !== '', fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'));

        $tagIds = array_filter(array_map('intval', $this->tag_ids));
        $query->when($tagIds !== [], fn ($q) => $q->whereHas('tags', fn ($q) => $q->whereIn('tc_tags.id', $tagIds)));

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

    /** @return array<int, array{id: string, name: string}> */
    #[Computed]
    public function tagsList(): array
    {
        return Tag::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($t) => ['id' => (string) $t->id, 'name' => $t->name])
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

    /**
     * Learning modal sections for operators and admins (ticket flow, goal, rating, department, tag, operator assignment).
     *
     * @return array<int|string, array{title: string, content: string, icon?: string}>
     */
    public function getLearningSections(): array
    {
        $keys = ['overview', 'goal', 'rating', 'statuses', 'department', 'tag', 'operator'];

        $sections = [];
        foreach ($keys as $key) {
            $sections[$key] = [
                'title' => __('ticket_chat.learning.' . $key . '.title'),
                'content' => __('ticket_chat.learning.' . $key . '.content'),
                'icon' => __('ticket_chat.learning.' . $key . '.icon'),
            ];
        }

        return $sections;
    }

    public function render()
    {
        return view('livewire.admin.pages.ticket-chat.ticket-list', [
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['label' => __('ticket_chat.title')],
            ],
            'breadcrumbsActions' => $this->withLearningModalActions([
                [
                    'link' => route('admin.ticket-chat.departments.index'),
                    'icon' => 'o-building-office-2',
                    'label' => __('ticket_chat.departments_manage'),
                ],
                [
                    'link' => route('admin.ticket-chat.tags.index'),
                    'icon' => 'o-tag',
                    'label' => __('ticket_chat.manage_tags'),
                ],
                [
                    'link' => route('admin.ticket-chat.feedback-report'),
                    'icon' => 'o-star',
                    'label' => __('ticket_chat.feedback_report'),
                ],
                [
                    'link' => route('admin.ticket-chat.create'),
                    'icon' => 's-plus',
                    'label' => __('ticket_chat.create_ticket'),
                ],
            ]),
        ]);
    }
}
