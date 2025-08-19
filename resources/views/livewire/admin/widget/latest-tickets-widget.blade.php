<div class="py-5">
    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <x-stat title="{{ __('widgets.latest_tickets.stats.total') }}" value="{{ $this->ticketStats['total'] }}"
            icon="o-ticket" color="text-primary"
            tooltip="{{ __('widgets.latest_tickets.stats.total_tooltip', ['total' => $this->ticketStats['total']]) }}" />
        <x-stat title="{{ __('widgets.latest_tickets.stats.open') }}" value="{{ $this->ticketStats['open'] }}"
            icon="o-eye" color="text-success"
            tooltip="{{ __('widgets.latest_tickets.stats.open_tooltip', ['open' => $this->ticketStats['open']]) }}" />
        <x-stat title="{{ __('widgets.latest_tickets.stats.closed') }}" value="{{ $this->ticketStats['closed'] }}"
            icon="o-check-circle" color="text-warning"
            tooltip="{{ __('widgets.latest_tickets.stats.closed_tooltip') }}" />
    </div>

    <!-- Table Section -->
    <x-card title="{{ __('widgets.latest_tickets.title') }}"
        subtitle="{{ __('widgets.latest_tickets.subtitle', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
        shadow separator>
        <x-slot:menu>
            <x-button icon="lucide.external-link" class="btn-circle btn-sm" link="{{ $this->getMoreItemsUrl() }}" />
            <x-select :options="\App\Enums\TicketDepartmentEnum::formatedCases()" option-label="label" option-value="value" wire:model.live="department"
                class="w-48" />
            <x-select :options="\App\Enums\TicketStatusEnum::formatedCases()" option-label="label" option-value="value" wire:model.live="status"
                class="w-48" />
        </x-slot:menu>

        @if ($this->latestTickets->count() > 0)
            <x-table :headers="$this->headers" :rows="$this->latestTickets" striped wire:key="latest-tickets-widget-table">
                @scope('cell_user', $ticket)
                    <div class="flex items-center gap-2">
                        <img src="{{ $ticket->user?->getFirstMediaUrl('avatar', '30_square') }}"
                            alt="{{ $ticket->user?->full_name }}" class="w-6 h-6 rounded-full object-cover" />
                        <div class="text-sm">
                            <div class="font-medium">{{ $ticket->user?->full_name }}</div>
                            <div class="text-xs text-base-content/60">{{ $ticket->user?->email }}</div>
                        </div>
                    </div>
                @endscope

                @scope('cell_priority', $ticket)
                    <x-badge :value="$ticket->priority->title()"
                        class="badge-sm {{ $ticket->priority->value === 'critical' ? 'badge-error' : ($ticket->priority->value === 'high' ? 'badge-warning' : ($ticket->priority->value === 'medium' ? 'badge-info' : 'badge-success')) }}" />
                @endscope

                @scope('cell_status', $ticket)
                    <x-badge :value="$ticket->status->title()"
                        class="badge-sm {{ $ticket->status->value === 'open' ? 'badge-success' : 'badge-neutral' }}" />
                @endscope

                @scope('cell_actions', $ticket)
                    <x-button icon="o-eye" class="btn-sm" link="#" />
                @endscope
            </x-table>

            <x-slot:footer>
                <div class="flex items-center justify-between text-sm text-base-content/60">
                    <span>{{ __('widgets.latest_tickets.showing', ['count' => $this->latestTickets->count()]) }}</span>
                    <span>{{ $start_date }} {{ __('general.to') }} {{ $end_date }}</span>
                </div>
            </x-slot:footer>
        @else
            <x-admin.shared.empty-view title="{{ __('widgets.latest_tickets.empty_title') }}"
                description="{{ __('widgets.latest_tickets.empty_description', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
                icon="o-ticket" btn_label="{{ __('widgets.latest_tickets.view_all') }}"
                btn_link="{{ $this->getMoreItemsUrl() }}" />
        @endif
    </x-card>
</div>
