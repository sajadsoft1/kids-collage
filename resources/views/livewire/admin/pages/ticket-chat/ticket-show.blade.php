@php
    use App\Helpers\Constants;
@endphp
<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    @php
        $ticket = $this->ticket;
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

    {{-- Summary card with labeled fields --}}
    <x-card class="mb-4" shadow>
        <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">{{ __('ticket_chat.ticket_number') }}</dt>
                <dd class="mt-0.5 font-mono font-semibold text-primary">{{ $ticket->ticket_number }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">{{ __('ticket_chat.status') }}</dt>
                <dd class="mt-0.5">
                    <x-badge :value="__('ticket_chat.status_' . $ticket->status->value)" :class="$statusBadgeClass" />
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">{{ __('ticket_chat.priority') }}</dt>
                <dd class="mt-0.5">
                    <x-badge :value="__('ticket_chat.priority_' . $ticket->priority->value)" :class="$priorityBadgeClass" />
                </dd>
            </div>
            @if($ticket->department)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">{{ __('ticket_chat.department') }}</dt>
                    <dd class="mt-0.5">{{ $ticket->department->name }}</dd>
                </div>
            @endif
            <div class="sm:col-span-2 lg:col-span-3">
                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">{{ __('ticket_chat.subject') }}</dt>
                <dd class="mt-0.5 text-lg font-medium">{{ $ticket->title }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">{{ __('ticket_chat.creator') }}</dt>
                <dd class="mt-0.5">
                    @if($ticket->creator)
                        {{ trim($ticket->creator->name . ' ' . ($ticket->creator->family ?? '')) }}
                    @else
                        —
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">{{ __('ticket_chat.created_at') }}</dt>
                <dd class="mt-0.5">{{ $ticket->created_at->format('Y/m/d H:i') }}</dd>
            </div>
            @if($ticket->assignedAgent)
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">{{ __('ticket_chat.operator') }}</dt>
                    <dd class="mt-0.5">{{ trim($ticket->assignedAgent->name . ' ' . ($ticket->assignedAgent->family ?? '')) }}</dd>
                </div>
            @endif
        </dl>
    </x-card>

    {{-- Chat card --}}
    <x-card class="mb-4 flex flex-col overflow-hidden" shadow>
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-base-200 px-4 py-2">
            <h3 class="font-medium">{{ __('ticket_chat.reply') }}</h3>
            @if($this->internalNotes->count() > 0)
                <x-button wire:click="openInternalNotesModal" class="btn-ghost btn-sm" icon="o-lock-closed">
                    {{ __('ticket_chat.internal_notes_list') }} ({{ $this->internalNotes->count() }})
                </x-button>
            @endif
        </div>

        {{-- Messages area --}}
        <div class="max-h-[50vh] min-h-[200px] overflow-y-auto p-4" id="ticket-messages">
            @foreach($this->ticketMessages as $message)
                @php
                    $isOwn = (int) $message->user_id === (int) auth()->id();
                    $userName = $message->user ? trim($message->user->name . ' ' . ($message->user->family ?? '')) : '';
                    $avatarUrl = $message->user && method_exists($message->user, 'getFirstMediaUrl')
                        ? $message->user->getFirstMediaUrl('avatar', Constants::RESOLUTION_512_SQUARE)
                        : null;
                @endphp
                <div class="mb-4 flex {{ $isOwn ? 'justify-end' : 'justify-start' }}" wire:key="msg-{{ $message->id }}">
                    <div class="flex max-w-[85%] gap-2 {{ $isOwn ? 'flex-row-reverse' : '' }}">
                        @if(!$isOwn && $avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $userName }}" class="size-9 shrink-0 rounded-full object-cover" />
                        @endif
                        @if($isOwn && $avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="" class="size-9 shrink-0 rounded-full object-cover" />
                        @endif
                        <div
                            class="rounded-2xl px-4 py-2.5 shadow-sm {{ $isOwn ? 'bg-primary text-primary-content' : 'bg-base-200' }}">
                            @if(!$isOwn && $userName)
                                <p class="mb-1 text-xs font-semibold opacity-90">{{ $userName }}</p>
                            @endif
                            @if($message->replyTo)
                                <div class="mb-2 border-s-2 border-current ps-2 text-xs opacity-80">
                                    {{ Str::limit($message->replyTo->body, 60) }}
                                </div>
                            @endif
                            <div class="whitespace-pre-wrap break-words text-sm">{{ $message->body }}</div>
                            @if($message->attachments->count() > 0)
                                <div class="mt-2 space-y-1">
                                    @foreach($message->attachments as $att)
                                        @if($att->isImage())
                                            <a href="{{ $att->url }}" target="_blank" rel="noopener" class="block">
                                                <img src="{{ $att->thumbnail_url ?? $att->url }}" alt="{{ $att->file_name }}"
                                                    class="max-h-32 rounded object-cover" />
                                            </a>
                                        @else
                                            <a href="{{ $att->url }}" target="_blank" rel="noopener"
                                                class="link link-hover flex items-center gap-1 text-xs">
                                                <x-icon name="o-paper-clip" class="size-3.5" />
                                                {{ $att->file_name }} ({{ $att->human_size }})
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            <p class="mt-1 text-right text-xs opacity-70">{{ $message->created_at->format('H:i Y/m/d') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(!$ticket->isClosed())
            {{-- Reply bar: compact single row --}}
            <form wire:submit="sendMessage" class="border-t border-base-200 bg-base-100 p-3">
                @if($replyToId)
                    <p class="mb-2 text-xs text-base-content/60">
                        {{ __('ticket_chat.reply') }}
                        <button type="button" wire:click="cancelReply" class="link link-hover font-medium">×</button>
                    </p>
                @endif
                <div class="flex flex-wrap items-end gap-2">
                    <label class="flex shrink-0 items-center gap-1.5 self-center">
                        <input type="checkbox" wire:model="isInternalNote" class="checkbox checkbox-sm" />
                        <span class="text-xs">{{ __('ticket_chat.internal_note') }}</span>
                    </label>
                    <div class="min-w-0 flex-1">
                        <textarea wire:model="newMessage" rows="2" placeholder="{{ __('ticket_chat.reply') }}..."
                            class="textarea textarea-bordered w-full resize-none rounded-xl py-2 text-sm"
                            wire:loading.attr="disabled"></textarea>
                    </div>
                    <div class="flex shrink-0 items-center gap-1">
                        <label class="btn btn-ghost btn-sm btn-square">
                            <x-icon name="o-paper-clip" class="size-5" />
                            <input type="file" wire:model="attachments" multiple class="hidden" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip" />
                        </label>
                        <x-button type="submit" class="btn-primary btn-sm" icon="o-paper-airplane" spinner="sendMessage"
                            wire:loading.attr="disabled">
                            {{ __('ticket_chat.send') }}
                        </x-button>
                    </div>
                </div>
                @if(count($attachments) > 0)
                    <p class="mt-2 text-xs text-base-content/60">
                        {{ count($attachments) }} {{ __('ticket_chat.attachments') }}
                    </p>
                @endif
            </form>
        @else
            <div class="border-t border-base-200 bg-base-200/50 p-4 text-center">
                <p class="text-sm text-base-content/60">{{ __('ticket_chat.ticket_closed') }}</p>
            </div>
        @endif
    </x-card>

    {{-- Internal notes modal --}}
    @if($showInternalNotesModal)
        <x-modal wire:model="showInternalNotesModal" :title="__('ticket_chat.internal_notes_list')" separator class="modal-lg">
            <div class="max-h-[60vh] space-y-3 overflow-y-auto">
                @foreach($this->internalNotes as $note)
                    <div class="rounded-xl border border-warning/30 bg-warning/5 p-3">
                        <p class="mb-1 flex items-center gap-1 text-xs font-medium text-warning">
                            <x-icon name="o-lock-closed" class="size-3.5" />
                            @if($note->user)
                                {{ trim($note->user->name . ' ' . ($note->user->family ?? '')) }}
                            @endif
                            · {{ $note->created_at->format('Y/m/d H:i') }}
                        </p>
                        <div class="whitespace-pre-wrap break-words text-sm">{{ $note->body }}</div>
                    </div>
                @endforeach
            </div>
            <x-slot:actions>
                <x-button wire:click="$set('showInternalNotesModal', false)" class="btn-ghost">{{ __('general.close') }}</x-button>
            </x-slot:actions>
        </x-modal>
    @endif

    {{-- Close confirmation modal --}}
    @if($showCloseModal)
        <x-modal wire:model="showCloseModal" :title="__('ticket_chat.close_ticket')" separator with-close-button>
            <p class="text-base-content">{{ __('ticket_chat.confirm_close') }}</p>
            <x-slot:actions>
                <x-button wire:click="$set('showCloseModal', false)" class="btn-ghost">{{ __('general.cancel') }}</x-button>
                <x-button wire:click="closeTicket" class="btn-error">{{ __('ticket_chat.close_ticket') }}</x-button>
            </x-slot:actions>
        </x-modal>
    @endif
</div>

@script
<script>
    $wire.on('message-sent', () => {
        const el = document.getElementById('ticket-messages');
        if (el) el.scrollTop = el.scrollHeight;
    });
</script>
@endscript
