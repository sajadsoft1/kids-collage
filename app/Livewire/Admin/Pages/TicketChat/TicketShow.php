<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\TicketChat;

use App\Models\User;
use App\Services\TicketChat\FeedbackService;
use App\Services\TicketChat\TagService;
use Karnoweb\TicketChat\Enums\TicketStatus;
use Karnoweb\TicketChat\Facades\Chat;
use Karnoweb\TicketChat\Facades\Ticket;
use Karnoweb\TicketChat\Models\CannedResponse;
use Karnoweb\TicketChat\Models\Conversation;
use Karnoweb\TicketChat\Models\Department;
use Karnoweb\TicketChat\Models\Tag;
use Karnoweb\TicketChat\Services\AttachmentService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class TicketShow extends Component
{
    use Toast;
    use WithFileUploads;

    #[Locked]
    public int $ticketId;

    public string $newMessage = '';

    /** @var array<int, \Illuminate\Http\UploadedFile> */
    public array $attachments = [];

    public ?int $replyToId = null;

    public bool $showCloseModal = false;

    public bool $showInternalNotesModal = false;

    public bool $showAssignModal = false;

    public bool $showTransferModal = false;

    public bool $showStatusModal = false;

    public bool $showPriorityModal = false;

    public bool $showTagsModal = false;

    public bool $isInternalNote = false;

    public ?int $assignAgentId = null;

    public ?int $transferDepartmentId = null;

    public string $newStatus = '';

    public string $newPriority = '';

    /** @var array<int> */
    public array $selectedTagIds = [];

    public ?string $selectedCannedId = null;

    public int $csatRating = 0;

    public string $csatComment = '';

    public function openCloseModal(): void
    {
        $this->showCloseModal = true;
    }

    public function openInternalNotesModal(): void
    {
        $this->showInternalNotesModal = true;
    }

    public function openAssignModal(): void
    {
        $this->assignAgentId = $this->ticket->assigned_to;
        $this->showAssignModal = true;
    }

    public function openTransferModal(): void
    {
        $this->transferDepartmentId = $this->ticket->department_id;
        $this->showTransferModal = true;
    }

    public function openStatusModal(): void
    {
        $this->newStatus = $this->ticket->status->value;
        $this->showStatusModal = true;
    }

    public function openPriorityModal(): void
    {
        $this->newPriority = $this->ticket->priority->value;
        $this->showPriorityModal = true;
    }

    public function openTagsModal(): void
    {
        $this->selectedTagIds = $this->ticket->tags->pluck('id')->all();
        $this->showTagsModal = true;
    }

    public function mount(int $ticket): void
    {
        $this->ticketId = $ticket;
        $ticketModel = $this->ticket;
        if ( ! $ticketModel->participants->contains('id', auth()->id())) {
            abort(403);
        }
        Chat::markAsRead($ticketModel, (int) auth()->id());
    }

    #[Computed]
    public function ticket(): Conversation
    {
        return Conversation::with([
            'department:id,name',
            'assignedAgent:id,name,family',
            'creator:id,name,family',
            'tags:id,name,color',
        ])->findOrFail($this->ticketId);
    }

    #[Computed]
    public function ticketMessages()
    {
        return $this->ticket->messages()
            ->visible()
            ->with(['user', 'attachments', 'replyTo:id,body,user_id'])
            ->oldest()
            ->get();
    }

    #[Computed]
    public function internalNotes()
    {
        return $this->ticket->messages()
            ->internalNotes()
            ->with(['user:id,name,family', 'attachments'])
            ->oldest()
            ->get();
    }

    public function sendMessage(AttachmentService $attachmentService): void
    {
        $this->validate([
            'newMessage' => 'required|min:1|max:5000',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        if ($this->ticket->isClosed()) {
            $this->addError('newMessage', __('ticket_chat.ticket_closed'));

            return;
        }

        if ($this->isInternalNote) {
            $message = Ticket::addNote($this->ticket, (int) auth()->id(), $this->newMessage);
        } else {
            $message = Ticket::reply($this->ticket, (int) auth()->id(), $this->newMessage, [
                'reply_to_id' => $this->replyToId,
            ]);
        }

        foreach ($this->attachments as $file) {
            $attachmentService->upload($message, $file);
        }

        $this->reset(['newMessage', 'attachments', 'replyToId', 'isInternalNote']);
        unset($this->ticketMessages, $this->internalNotes);

        $this->dispatch('message-sent');
    }

    public function removeAttachment(int $index): void
    {
        $files = $this->attachments;
        unset($files[$index]);
        $this->attachments = array_values($files);
    }

    public function setReplyTo(int $messageId): void
    {
        $this->replyToId = $messageId;
    }

    public function insertCannedResponse(int $id): void
    {
        $ticket = $this->ticket;
        $response = CannedResponse::query()
            ->active()
            ->forDepartment($ticket->department_id ?? 0)
            ->where('id', $id)
            ->first();
        if ($response) {
            $this->newMessage = $response->render($this->cannedResponseVariables($ticket));
        }
        $this->selectedCannedId = null;
    }

    public function updatedSelectedCannedId(?string $value): void
    {
        if ( ! empty($value)) {
            $this->insertCannedResponse((int) $value);
        }
    }

    /** @return array<string, string> */
    private function cannedResponseVariables(Conversation $ticket): array
    {
        $creator = $ticket->creator;
        $agent = $ticket->assignedAgent;
        $department = $ticket->department;

        return [
            'ticket_number' => $ticket->ticket_number ?? '',
            'ticket_title' => $ticket->title ?? '',
            'customer_name' => $creator ? trim($creator->name . ' ' . ($creator->family ?? '')) : '',
            'agent_name' => $agent ? trim($agent->name . ' ' . ($agent->family ?? '')) : '',
            'department_name' => $department?->name ?? '',
            'current_date' => now()->format('Y/m/d'),
            'current_time' => now()->format('H:i'),
        ];
    }

    /** @return array<int, array{id: string, name: string}> */
    #[Computed]
    public function cannedResponseOptions(): array
    {
        if ( ! config('ticket-chat.features.canned_responses', true)) {
            return [];
        }
        $departmentId = $this->ticket->department_id ?? 0;

        return CannedResponse::query()
            ->active()
            ->forDepartment($departmentId)
            ->orderBy('title')
            ->get()
            ->map(fn (CannedResponse $r) => [
                'id' => (string) $r->id,
                'name' => $r->title . ($r->shortcut ? ' (' . $r->shortcut . ')' : ''),
            ])
            ->values()
            ->all();
    }

    public function cancelReply(): void
    {
        $this->replyToId = null;
    }

    public function closeTicket(): void
    {
        Ticket::close($this->ticket, (int) auth()->id());
        $this->showCloseModal = false;
        unset($this->ticket);
        $this->success(__('ticket_chat.ticket_closed_success'));
    }

    public function changeStatus(): void
    {
        $this->validate(['newStatus' => 'required|in:open,pending,in_progress,resolved,closed,archived']);
        $to = TicketStatus::from($this->newStatus);
        if ( ! $this->ticket->status->canTransitionTo($to)) {
            $this->error(__('ticket_chat.status_transition_not_allowed'));

            return;
        }
        Ticket::changeStatus($this->ticket, $this->newStatus);
        $this->showStatusModal = false;
        unset($this->ticket);
        $this->success(__('ticket_chat.status_updated'));
    }

    public function changeStatusTo(string $status): void
    {
        $this->newStatus = $status;
        $this->changeStatus();
    }

    public function changePriority(): void
    {
        $this->validate(['newPriority' => 'required|in:low,medium,high,urgent']);
        Ticket::changePriority($this->ticket, $this->newPriority);
        $this->showPriorityModal = false;
        unset($this->ticket);
        $this->success(__('ticket_chat.priority_updated'));
    }

    public function assignAgent(): void
    {
        $this->validate(['assignAgentId' => 'nullable|exists:users,id']);
        Ticket::assign($this->ticket, ! empty($this->assignAgentId) ? (int) $this->assignAgentId : null);
        $this->showAssignModal = false;
        unset($this->ticket);
        $this->success(__('ticket_chat.assigned_success'));
    }

    public function transferDepartment(): void
    {
        $this->validate(['transferDepartmentId' => 'required|exists:tc_departments,id']);
        Ticket::transfer($this->ticket, (int) $this->transferDepartmentId);
        $this->showTransferModal = false;
        unset($this->ticket);
        $this->success(__('ticket_chat.transferred_success'));
    }

    public function saveTags(TagService $tagService): void
    {
        $tagService->syncConversationTags($this->ticket, array_map('intval', $this->selectedTagIds));
        $this->showTagsModal = false;
        unset($this->ticket);
        $this->success(__('ticket_chat.tags_updated'));
    }

    public function reopenTicket(): void
    {
        Ticket::reopen($this->ticket);
        unset($this->ticket);
        $this->success(__('ticket_chat.ticket_reopened'));
    }

    public function submitCsat(FeedbackService $feedbackService): void
    {
        $this->validate([
            'csatRating' => 'required|integer|min:1|max:5',
            'csatComment' => 'nullable|string|max:2000',
        ]);
        $feedbackService->submit(
            conversation: $this->ticket,
            userId: (int) auth()->id(),
            rating: $this->csatRating,
            comment: $this->csatComment ?: null
        );
        $this->reset(['csatRating', 'csatComment']);
        unset($this->ticket);
        $this->success(__('ticket_chat.csat_submitted'));
    }

    /** @return array<int, array{id: string, name: string}> */
    #[Computed]
    public function allowedStatusTransitions(): array
    {
        $from = $this->ticket->status;
        $allowed = TicketStatus::allowedTransitions($from);

        return array_map(
            fn (TicketStatus $s) => [
                'id' => $s->value,
                'name' => __('ticket_chat.status_' . $s->value),
            ],
            $allowed
        );
    }

    #[Computed]
    public function csatFeedback()
    {
        if ( ! config('ticket-chat.features.csat', true)) {
            return;
        }

        return app(FeedbackService::class)->getForConversation($this->ticket);
    }

    #[Computed]
    public function canSubmitCsat(): bool
    {
        if ( ! config('ticket-chat.features.csat', true) || ! $this->ticket->isClosed()) {
            return false;
        }
        if ($this->csatFeedback !== null) {
            return false;
        }

        return (int) $this->ticket->created_by === (int) auth()->id();
    }

    /** @return array<int, array{id: string, name: string}> */
    #[Computed]
    public function assignableAgents(): array
    {
        $ticket = $this->ticket;
        $query = User::query()
            ->whereHas('departments')
            ->orderBy('name')
            ->orderBy('family');

        if ($ticket->department_id) {
            $query->whereHas('departments', fn ($q) => $q->where('tc_departments.id', $ticket->department_id));
        }

        return $query->get(['id', 'name', 'family'])
            ->map(fn (User $u) => [
                'id' => (string) $u->id,
                'name' => trim($u->name . ' ' . ($u->family ?? '')),
            ])
            ->values()
            ->all();
    }

    /** @return array<int, array{id: string, name: string}> */
    #[Computed]
    public function departmentsForTransfer(): array
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

    /** @return array<int, array{id: int, name: string, color: string|null}> */
    #[Computed]
    public function allTags(): array
    {
        return Tag::query()
            ->orderBy('name')
            ->get(['id', 'name', 'color'])
            ->map(fn ($t) => ['id' => $t->id, 'name' => $t->name, 'color' => $t->color ?? null])
            ->values()
            ->all();
    }

    public function render()
    {
        $ticket = $this->ticket;
        $actions = [
            ['link' => route('admin.ticket-chat.index'), 'icon' => 's-arrow-left'],
        ];

        $canManage = method_exists(auth()->user(), 'departments') && auth()->user()->departments->isNotEmpty();
        if ( ! $ticket->isClosed() && $canManage) {
            $actions[] = ['action' => 'openAssignModal', 'icon' => 'o-user-plus', 'label' => __('ticket_chat.assign_operator')];
            $actions[] = ['action' => 'openTransferModal', 'icon' => 'o-building-office-2', 'label' => __('ticket_chat.transfer_department')];
            $actions[] = ['action' => 'openStatusModal', 'icon' => 'o-arrow-path', 'label' => __('ticket_chat.change_status')];
            $actions[] = ['action' => 'openPriorityModal', 'icon' => 'o-flag', 'label' => __('ticket_chat.change_priority')];
            if (config('ticket-chat.features.tags', true)) {
                $actions[] = ['action' => 'openTagsModal', 'icon' => 'o-tag', 'label' => __('ticket_chat.manage_tags')];
            }
        }

        if ( ! $ticket->isClosed()) {
            $actions[] = [
                'action' => 'openCloseModal',
                'icon' => 'o-x-circle',
                'label' => __('ticket_chat.close_ticket'),
                'class' => 'btn-error btn-outline',
            ];
        } else {
            $actions[] = [
                'action' => 'reopenTicket',
                'icon' => 'o-arrow-path',
                'label' => __('ticket_chat.reopen_ticket'),
                'class' => 'btn-primary btn-outline',
            ];
        }

        return view('livewire.admin.pages.ticket-chat.ticket-show', [
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.ticket-chat.index'), 'label' => __('ticket_chat.title')],
                ['label' => $ticket->ticket_number],
            ],
            'breadcrumbsActions' => $actions,
        ]);
    }
}
