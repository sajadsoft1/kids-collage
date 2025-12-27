<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Ticket;

use App\Actions\Ticket\StoreTicketAction;
use App\Actions\Ticket\ToggleTicketStatusAction;
use App\Actions\TicketMessage\StoreTicketMessageAction;
use App\Enums\TicketDepartmentEnum;
use App\Enums\TicketPriorityEnum;
use App\Enums\TicketStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Services\Permissions\PermissionsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class TicketApp extends Component
{
    use Toast, WithFileUploads, WithPagination;

    public ?int $selectedTicketId = null;
    public string $filter_department = '';
    public string $search_ticket = '';
    public string $message = '';
    public string $department = '';
    public int $perPage = 15;
    public int $messagesPage = 1;
    public $file = null;
    public bool $uploading = false;
    public bool $hasMoreMessages = true;

    public bool $show_new_modal = false;
    public bool $show_ticket_info = false;

    public array $newTicket = [];

    public function mount(): void
    {
        $this->resetNewTicket();
    }

    protected function resetNewTicket(): void
    {
        $this->newTicket = [
            'subject' => '',
            'department' => TicketDepartmentEnum::SALE->value,
            'priority' => TicketPriorityEnum::MEDIUM->value,
            'body' => '',
        ];
    }

    protected function validationMessages(): array
    {
        return [];
    }

    public function selectTicket(int $ticketId): void
    {
        $ticket = Ticket::find($ticketId);

        if ( ! $ticket) {
            $this->error(trans('ticket.not_found'));

            return;
        }

        // Authorization check
        if ( ! $this->canAccessTicket($ticket)) {
            $this->error(trans('ticket.unauthorized'));

            return;
        }

        $this->selectedTicketId = $ticketId;
        $this->messagesPage = 1;
        $this->hasMoreMessages = true;
        $this->reset(['message', 'file']);

        // Mark messages as read when selecting ticket
        $this->markMessagesAsRead();
    }

    #[Computed]
    public function selected_ticket(): ?Ticket
    {
        if ( ! $this->selectedTicketId) {
            return null;
        }

        return Ticket::with(['user', 'closeBy'])->find($this->selectedTicketId);
    }

    public function toggleTicketStatus(): void
    {
        $ticket = $this->selected_ticket();

        if ( ! $ticket) {
            return;
        }

        // Authorization check
        if ( ! $this->canModifyTicket($ticket)) {
            $this->error(trans('ticket.unauthorized'));

            return;
        }

        ToggleTicketStatusAction::run($ticket);

        $this->success(
            title: $ticket->status === TicketStatusEnum::CLOSE
                ? trans('ticket.page.ticket_status_changed_to_close')
                : trans('ticket.page.ticket_status_changed_to_open')
        );
    }

    public function loadMoreMessages(): void
    {
        if ( ! $this->selectedTicketId || ! $this->hasMoreMessages) {
            return;
        }

        $currentCount = $this->perPage * $this->messagesPage;
        $totalCount = TicketMessage::where('ticket_id', $this->selectedTicketId)->count();

        if ($currentCount >= $totalCount) {
            $this->hasMoreMessages = false;

            return;
        }

        $this->messagesPage++;
    }

    public function submitNewMessage(): void
    {
        $ticket = $this->selected_ticket();

        if ( ! $ticket) {
            $this->error(trans('ticket.not_found'));

            return;
        }

        // Check if ticket is closed
        if ($ticket->status === TicketStatusEnum::CLOSE) {
            $this->error(trans('ticket.cannot_message_closed'));

            return;
        }

        // Authorization check
        if ( ! $this->canAccessTicket($ticket)) {
            $this->error(trans('ticket.unauthorized'));

            return;
        }

        $this->validate([
            'message' => 'required_without:file|nullable|string|max:5000',
            'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,mp4,webm,mp3,wav',
        ]);

        $userId = auth()->id();

        if ( ! $userId) {
            $this->error(trans('auth.unauthorized'));

            return;
        }

        $data = [
            'ticket_id' => $ticket->id,
            'user_id' => $userId,
            'message' => $this->message ?: null,
        ];

        if ($this->file) {
            $data['file'] = $this->file;
        }

        StoreTicketMessageAction::run($data);

        $this->reset(['message', 'file']);
        $this->messagesPage = 1; // Reset to show latest messages
        $this->hasMoreMessages = true;

        $this->dispatch('message-sent');

        $this->success(trans('ticket.message_sent'));
    }

    public function submitNewTicket(): void
    {
        $payload = $this->validate([
            'newTicket.subject' => 'required|string|max:255',
            'newTicket.department' => 'required|in:finance_and_administration,Sale,technical',
            'newTicket.priority' => 'required|int|in:1,2,3,4',
            'newTicket.body' => 'required|string|max:5000',
        ]);

        $userId = auth()->id();

        if ( ! $userId) {
            $this->error(trans('auth.unauthorized'));

            return;
        }

        $payload['newTicket']['user_id'] = $userId;
        $ticket = StoreTicketAction::run($payload['newTicket']);

        $this->show_new_modal = false;
        $this->resetNewTicket();
        $this->selectTicket($ticket->id);

        $this->success(
            title: trans('general.model_has_stored_successfully', ['model' => trans('ticket.model')]),
        );
    }

    protected function markMessagesAsRead(): void
    {
        if ( ! $this->selectedTicketId) {
            return;
        }

        $userId = auth()->id();

        if ( ! $userId) {
            return;
        }

        TicketMessage::where('ticket_id', $this->selectedTicketId)
            ->where('user_id', '!=', $userId)
            ->whereNull('read_by')
            ->update(['read_by' => $userId, 'read_at' => now()]);
    }

    protected function canAccessTicket(Ticket $ticket): bool
    {
        $user = auth()->user();

        if ( ! $user) {
            return false;
        }

        // Admins with permission can access all tickets
        if ($user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class, 'Index'))) {
            return true;
        }

        // User can access their own tickets
        if ($ticket->user_id === $user->id) {
            return true;
        }

        // Parents can access their children's tickets
        if ($user->type === UserTypeEnum::PARENT) {
            $childrenIds = $user->children->pluck('id')->toArray();

            return in_array($ticket->user_id, $childrenIds, true);
        }

        return false;
    }

    protected function canModifyTicket(Ticket $ticket): bool
    {
        $user = auth()->user();

        if ( ! $user) {
            return false;
        }

        // Admins with permission can modify tickets
        if ($user->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class, 'Update'))) {
            return true;
        }

        // Users can only modify their own tickets
        return $ticket->user_id === $user->id;
    }

    public function render(): View
    {
        $tickets = $this->getTicketsQuery()
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'family');
            }])
            ->with(['messages' => function ($query) {
                $query->latest()->limit(1)->select('id', 'ticket_id', 'created_at');
            }])
            ->withCount([
                'messages as unread_messages_count' => function (Builder $query) {
                    $userId = auth()->id();
                    if ($userId) {
                        $query->whereNull('read_by')
                            ->where('user_id', '!=', $userId);
                    }
                },
            ])
            ->latest()
            ->paginate(15);

        $ticketMessages = $this->loadTicketMessages();

        $view = view('livewire.admin.pages.ticket.ticket-app', [
            'tickets' => $tickets,
            'ticketMessages' => $ticketMessages,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ],
            'breadcrumbsActions' => [],
        ]);

        // @phpstan-ignore-next-line
        return $view->layout(config('livewire.layout'), [
            'fullWidth' => true,
        ]);
    }

    protected function getTicketsQuery(): Builder
    {
        $query = Ticket::query();

        // Filter by department
        if ( ! empty($this->filter_department)) {
            $query->where('department', $this->filter_department);
        }

        // Search by user name
        if ( ! empty(trim($this->search_ticket))) {
            $query->whereHas('user', function (Builder $q) {
                $q->where('name', 'like', '%' . $this->search_ticket . '%')
                    ->orWhere('family', 'like', '%' . $this->search_ticket . '%');
            });
        }

        // Apply user type restrictions
        $user = auth()->user();

        if ( ! $user) {
            return $query->whereRaw('1 = 0'); // No results if no user
        }

        match ($user->type) {
            UserTypeEnum::PARENT => $query->whereIn('user_id', [
                ...$user->children->pluck('id')->toArray(),
                $user->id,
            ]),
            UserTypeEnum::TEACHER, UserTypeEnum::USER => $query->where('user_id', $user->id),
            UserTypeEnum::EMPLOYEE => $user->hasAnyPermission(
                PermissionsService::generatePermissionsByModel(Ticket::class, 'Index')
            ) ? $query : $query->where('user_id', $user->id),
            default => $query,
        };

        return $query;
    }

    protected function loadTicketMessages()
    {
        if ( ! $this->selectedTicketId) {
            return collect();
        }

        $limit = $this->perPage * $this->messagesPage;
        $totalCount = TicketMessage::where('ticket_id', $this->selectedTicketId)->count();

        $this->hasMoreMessages = $limit < $totalCount;

        return TicketMessage::where('ticket_id', $this->selectedTicketId)
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'family');
            }])
            ->latest()
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }
}
