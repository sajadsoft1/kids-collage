<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    @if($this->unreadCount > 0)
        <div class="mb-4">
            <x-badge value="{{ $this->unreadCount }} {{ __('ticket_chat.unread') }}" class="badge-warning" />
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-3 mb-6 md:gap-4 md:mb-8 md:grid-cols-4">
        <x-stat title="{{ __('ticket_chat.stats.total') }}" value="{{ $this->stats['total'] }}" icon="o-ticket"
            color="text-primary" class="border border-primary/20 bg-primary/5" />
        <x-stat title="{{ __('ticket_chat.stats.open') }}" value="{{ $this->stats['open'] }}" icon="o-inbox"
            color="text-info" class="border border-info/20 bg-info/5" />
        <x-stat title="{{ __('ticket_chat.stats.in_progress') }}" value="{{ $this->stats['in_progress'] }}"
            icon="o-clock" color="text-warning" class="border border-warning/20 bg-warning/5" />
        <x-stat title="{{ __('ticket_chat.stats.closed') }}" value="{{ $this->stats['closed'] }}" icon="o-check-circle"
            color="text-neutral" class="border border-neutral/20 bg-neutral/5" />
    </div>

    {{-- Filters --}}
    <x-card class="mb-6" shadow>
        <div class="mb-3 flex justify-end">
            <a href="{{ route('admin.ticket-chat.departments.index') }}" wire:navigate
                class="link link-hover text-sm font-medium text-primary">
                {{ __('ticket_chat.departments_manage') }}
            </a>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <x-input wire:model.live.debounce.300ms="search" icon="o-magnifying-glass"
                placeholder="{{ __('ticket_chat.subject') }}..." />
            <x-select wire:model.live="status" placeholder="{{ __('ticket_chat.status') }}" :options="[
                ['id' => '', 'name' => __('general.all')],
                ['id' => 'open', 'name' => __('ticket_chat.status_open')],
                ['id' => 'pending', 'name' => __('ticket_chat.status_pending')],
                ['id' => 'in_progress', 'name' => __('ticket_chat.status_in_progress')],
                ['id' => 'closed', 'name' => __('ticket_chat.status_closed')],
            ]" option-value="id" option-label="name" />
            <x-select wire:model.live="priority" placeholder="{{ __('ticket_chat.priority') }}" :options="[
                ['id' => '', 'name' => __('general.all')],
                ['id' => 'low', 'name' => __('ticket_chat.priority_low')],
                ['id' => 'medium', 'name' => __('ticket_chat.priority_medium')],
                ['id' => 'high', 'name' => __('ticket_chat.priority_high')],
                ['id' => 'urgent', 'name' => __('ticket_chat.priority_urgent')],
            ]" option-value="id" option-label="name" />
            <x-select wire:model.live="department_id" placeholder="{{ __('ticket_chat.department') }}"
                :options="array_merge([['id' => '', 'name' => __('general.all')]], $this->departmentsList)"
                option-value="id" option-label="name" />
            @if(method_exists(auth()->user(), 'departments') && auth()->user()->departments->isNotEmpty())
                <x-select wire:model.live="filter" :options="[
                    ['id' => 'mine', 'name' => __('ticket_chat.my_tickets')],
                    ['id' => 'all', 'name' => __('general.all')],
                ]" option-value="id" option-label="name" />
            @endif
        </div>
    </x-card>

    {{-- Ticket list as grid --}}
    @if($this->tickets->count() > 0)
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($this->tickets as $ticket)
                @php
                    $unread = $ticket->unreadCountForUser(auth()->id());
                    $statusBadgeClass = match ($ticket->status->value) {
                        'open' => 'badge-info',
                        'pending', 'in_progress' => 'badge-warning',
                        'closed' => 'badge-ghost',
                        default => 'badge-ghost',
                    };
                    $priorityBadgeClass = match ($ticket->priority->value) {
                        'urgent' => 'badge-error',
                        'high' => 'badge-warning',
                        'medium', 'low' => 'badge-ghost',
                        default => 'badge-ghost',
                    };
                @endphp
                <a href="{{ route('admin.ticket-chat.show', ['ticket' => $ticket->id]) }}" wire:navigate
                    wire:key="ticket-{{ $ticket->id }}"
                    class="card compact border border-base-200 bg-base-100 shadow transition-shadow hover:shadow-lg">
                    <div class="card-body gap-2 p-4">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-icon name="o-ticket" class="size-4 shrink-0 text-primary" />
                                    <span class="font-mono text-sm font-medium text-primary">{{ $ticket->ticket_number }}</span>
                                    <x-badge :value="__('ticket_chat.status_' . $ticket->status->value)" :class="$statusBadgeClass" />
                                    <x-badge :value="__('ticket_chat.priority_' . $ticket->priority->value)" :class="$priorityBadgeClass" />
                                    @if($unread > 0)
                                        <span class="badge badge-warning badge-sm">{{ $unread }}</span>
                                    @endif
                                </div>
                                <h3 class="mt-2 line-clamp-2 font-medium">{{ $ticket->title }}</h3>
                                <dl class="mt-2 space-y-0.5 text-xs text-base-content/60">
                                    @if($ticket->creator)
                                        <div class="flex gap-1">
                                            <dt class="shrink-0">{{ __('ticket_chat.creator') }}:</dt>
                                            <dd>{{ trim($ticket->creator->name . ' ' . ($ticket->creator->family ?? '')) }}</dd>
                                        </div>
                                    @endif
                                    @if($ticket->assignedAgent)
                                        <div class="flex gap-1">
                                            <dt class="shrink-0">{{ __('ticket_chat.operator') }}:</dt>
                                            <dd>{{ trim($ticket->assignedAgent->name . ' ' . ($ticket->assignedAgent->family ?? '')) }}</dd>
                                        </div>
                                    @endif
                                    @if($ticket->department)
                                        <div class="flex gap-1">
                                            <dt class="shrink-0">{{ __('ticket_chat.department') }}:</dt>
                                            <dd>{{ $ticket->department->name }}</dd>
                                        </div>
                                    @endif
                                    <div class="flex gap-1">
                                        <dt class="shrink-0">{{ __('ticket_chat.last_message') }}:</dt>
                                        <dd>{{ $ticket->last_message_at?->diffForHumans() ?? $ticket->created_at->diffForHumans() }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $this->tickets->links() }}
        </div>
    @else
        <x-card shadow>
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <x-icon name="o-ticket" class="size-16 text-base-content/30" />
                <h3 class="mt-4 text-lg font-medium">{{ __('ticket_chat.no_tickets') }}</h3>
                <p class="mt-2 max-w-sm text-sm text-base-content/60">{{ __('ticket_chat.no_tickets_message') }}</p>
                <x-button icon="o-plus" class="btn-primary mt-6" link="{{ route('admin.ticket-chat.create') }}" wire:navigate>
                    {{ __('ticket_chat.create_ticket') }}
                </x-button>
            </div>
        </x-card>
    @endif
</div>
