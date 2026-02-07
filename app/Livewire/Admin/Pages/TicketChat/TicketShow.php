<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\TicketChat;

use Karnoweb\TicketChat\Facades\Chat;
use Karnoweb\TicketChat\Facades\Ticket;
use Karnoweb\TicketChat\Models\Conversation;
use Karnoweb\TicketChat\Services\AttachmentService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class TicketShow extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $ticketId;

    public string $newMessage = '';

    /** @var array<int, \Illuminate\Http\UploadedFile> */
    public array $attachments = [];

    public ?int $replyToId = null;

    public bool $showCloseModal = false;

    public bool $showInternalNotesModal = false;

    public bool $isInternalNote = false;

    public function openCloseModal(): void
    {
        $this->showCloseModal = true;
    }

    public function openInternalNotesModal(): void
    {
        $this->showInternalNotesModal = true;
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

    public function setReplyTo(int $messageId): void
    {
        $this->replyToId = $messageId;
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
        session()->flash('success', __('ticket_chat.ticket_closed_success'));
    }

    public function render()
    {
        $ticket = $this->ticket;
        $actions = [
            ['link' => route('admin.ticket-chat.index'), 'icon' => 's-arrow-left', 'label' => __('ticket_chat.back_to_list')],
        ];
        if ( ! $ticket->isClosed()) {
            $actions[] = [
                'action' => 'openCloseModal',
                'icon' => 'o-x-circle',
                'label' => __('ticket_chat.close_ticket'),
                'class' => 'btn-error btn-outline',
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
