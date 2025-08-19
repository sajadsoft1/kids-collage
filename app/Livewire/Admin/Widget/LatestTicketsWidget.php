<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use App\Enums\TicketStatusEnum;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;

class LatestTicketsWidget extends Component
{
    public int $limit          = 10;
    public ?string $start_date = null;
    public ?string $end_date   = null;
    public string $status      = '';
    public string $department  = '';

    /** Initialize the widget with default values */
    public function mount(int $limit = 10, ?string $start_date = null, ?string $end_date = null, ?string $status = null, ?string $department = null): void
    {
        $this->limit      = $limit;
        $this->start_date = $start_date ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $this->end_date   = $end_date ?? Carbon::now()->format('Y-m-d');
        $this->status     = $status ?? '';
        $this->department = $department ?? '';
    }

    /** Get the latest tickets */
    #[Computed]
    public function latestTickets()
    {
        $query = Ticket::query()
            ->with(['user', 'closeBy'])
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('created_at', '<=', $this->end_date);
            })
            ->when($this->status, function (Builder $query) {
                $query->where('status', $this->status);
            })
            ->when($this->department, function (Builder $query) {
                $query->where('department', $this->department);
            })
            ->latest('created_at')
            ->limit($this->limit);

        return $query->get();
    }

    /** Get ticket statistics */
    #[Computed]
    public function ticketStats()
    {
        $baseQuery = Ticket::query()
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('created_at', '<=', $this->end_date);
            });

        return [
            'total'  => (clone $baseQuery)->count(),
            'open'   => (clone $baseQuery)->where('status', TicketStatusEnum::OPEN)->count(),
            'closed' => (clone $baseQuery)->where('status', TicketStatusEnum::CLOSE)->count(),
        ];
    }

    /** Get table headers */
    #[Computed]
    public function headers()
    {
        return [
            ['key' => 'key', 'label' => __('ticket.table.ticket_number')],
            ['key' => 'subject', 'label' => __('ticket.table.subject')],
            ['key' => 'user.full_name', 'label' => __('ticket.table.user')],
            ['key' => 'priority', 'label' => __('ticket.table.priority')],
            ['key' => 'status', 'label' => __('ticket.table.status')],
            ['key' => 'created_at', 'label' => __('ticket.table.created_at'), 'format' => ['date', 'H:i Y/m/d']],
            ['key' => 'actions', 'label' => __('ticket.table.actions'), 'class' => 'w-1'],
        ];
    }

    /** Get the URL for viewing more items */
    public function getMoreItemsUrl(): string
    {
        $params = http_build_query([
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
            'status'     => $this->status,
            'department' => $this->department,
        ]);

        return route('admin.ticket.index') . '?' . $params;
    }

    /** Filter tickets by status and department */
    public function filterTickets(?string $status = null, ?string $department = null): void
    {
        if ($status !== null) {
            $this->status = $status;
        }
        if ($department !== null) {
            $this->department = $department;
        }
    }

    /** Render the component */
    public function render()
    {
        return view('livewire.admin.widget.latest-tickets-widget');
    }
}
