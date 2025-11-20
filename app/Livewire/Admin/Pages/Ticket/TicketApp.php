<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Ticket;

use App\Actions\Ticket\StoreTicketAction;
use App\Actions\Ticket\ToggleTicketStatusAction;
use App\Actions\TicketMessage\StoreTicketMessageAction;
use App\Enums\TicketDepartmentEnum;
use App\Enums\TicketPriorityEnum;
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
    use Toast, WithFileUploads,WithPagination;

    public $selectedTicketId;
    public $filter_department = '';
    public $search_ticket = '';
    public $message = '';
    public $department = '';
    public $perPage = 15; // تعداد پیام‌ها در هر صفحه
    public $messagesPage = 1;  // صفحه فعلی پیام‌ها
    public $file = null;
    public $uploading = false;

    public bool $show_new_modal = false;
    public bool $show_ticket_info = false;

    public array $newTicket = [
        'subject' => '',
        'department' => TicketDepartmentEnum::SALE->value,
        'priority' => TicketPriorityEnum::MEDIUM->value,
        'body' => '',
    ];

    public function selectTicket($ticketId): void
    {
        $this->selectedTicketId = $ticketId;
        $this->messagesPage = 1; // ریست صفحه پیام‌ها
        $this->reset('message');
    }

    #[Computed]
    public function selected_ticket(): ?Ticket
    {
        return Ticket::find($this->selectedTicketId);
    }

    public function toogleTicketStatus(): void
    {
        ToggleTicketStatusAction::run($this->selected_ticket());
    }

    public function loadMoreMessages(): void
    {
        $this->messagesPage++;
    }

    public function submitNewMessage(): void
    {
        if (empty($this->message) && ! $this->file) {
            return;
        }

        $data = [
            'ticket_id' => $this->selected_ticket()->id,
            'user_id' => auth()->id(),
            'message' => $this->message,
        ];

        if ($this->file) {
            $data['file'] = $this->file;
        }

        StoreTicketMessageAction::run($data);

        $this->reset(['message', 'file']);
    }

    public function submitNewTicket(): void
    {
        $payload = $this->validate([
            'newTicket.subject' => 'required|string|max:255',
            'newTicket.department' => 'required|in:finance_and_administration,Sale,technical',
            'newTicket.priority' => 'required|int|in:' . collect(TicketPriorityEnum::formatedCases())->pluck('value'),
            'newTicket.body' => 'required|string',
        ], attributes: [
            'newTicket.subject' => __('validation.attributes.subject'),
            'newTicket.department' => __('validation.attributes.department'),
            'newTicket.priority' => __('validation.attributes.priority'),
            'newTicket.body' => __('validation.attributes.body'),
        ]);
        $payload['newTicket']['user_id'] = auth()->id();
        StoreTicketAction::run($payload['newTicket']);
        $this->show_new_modal = false;
        $this->success(
            title: trans('general.model_has_stored_successfully', ['model' => trans('ticket.model')]),
        );
    }

    public function render(): View
    {
        $tickets = Ticket::with('user')
            ->when( ! empty($this->filter_department), fn ($q) => $q->where('department', $this->filter_department))
            ->when( ! empty(trim($this->search_ticket)), fn (Builder $query) => $query->whereHas('user', function (Builder $query) {
                $query->where('name', 'like', '%' . $this->search_ticket . '%')
                    ->orWhere('family', 'like', '%' . $this->search_ticket . '%');
            }))
            ->when(
                auth()->user()->type === UserTypeEnum::PARENT,
                function ($q) {
                    $children = auth()->user()->children->pluck('id')->toArray();
                    $q->whereIn('user_id', [...$children, auth()->id()]);
                }
            )
            ->when(
                auth()->user()->type === UserTypeEnum::TEACHER,
                function ($q) {
                    $q->where('user_id', auth()->id());
                }
            )
            ->when(
                auth()->user()->type === UserTypeEnum::EMPLOYEE && ! auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(Ticket::class, 'Index')),
                function ($q) {
                    $q->where('user_id', auth()->id());
                }
            )
            ->when(
                auth()->user()->type === UserTypeEnum::USER,
                function ($q) {
                    $q->where('user_id', auth()->id());
                }
            )
            ->latest()
            ->paginate(15);

        $messages = $this->selectedTicketId
            ? TicketMessage::where('ticket_id', $this->selectedTicketId)
                ->with('user')
                ->take($this->perPage * $this->messagesPage)
                ->latest()
                ->get()
                ->reverse()
            : collect();

        // Mark messages as read
        if ($this->selectedTicketId) {
            TicketMessage::where('ticket_id', $this->selectedTicketId)
                ->where('user_id', '!=', auth()->id())
                ->whereNull('read_by')
                ->update(['read_by' => auth()->id(), 'read_at' => now()]);
        }

        return view('livewire.admin.pages.ticket.ticket-app', [
            'tickets' => $tickets,
            'messages' => $messages,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ],
            'breadcrumbsActions' => [
            ],
        ])->layout(config('livewire.layout'), [
            'fullWidth' => true,
        ]);
    }
}
