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

    @php
        $canManage = method_exists(auth()->user(), 'departments') && auth()->user()->departments->isNotEmpty();
    @endphp
    {{-- Summary card: collapsible, short summary visible by default --}}
    <x-card class="mb-4 overflow-hidden" shadow>
        <details class="collapse collapse-arrow group">
            <summary class="collapse-title min-h-0 py-3 font-medium cursor-pointer flex flex-wrap gap-2 items-center min-w-0">
                <span class="font-mono shrink-0 text-primary">{{ $ticket->ticket_number }}</span>
                <x-badge :value="__('ticket_chat.status_' . $ticket->status->value)" :class="$statusBadgeClass" />
                <span class="min-w-0 break-words text-base-content/80">{{ $ticket->title }}</span>
            </summary>
            <div class="collapse-content">
                <dl class="grid grid-cols-1 gap-y-3 gap-x-4 pt-2 border-t border-base-200 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                <dt class="text-xs font-medium tracking-wide uppercase text-base-content/50">
                    {{ __('ticket_chat.ticket_number') }}</dt>
                <dd class="mt-0.5 font-mono font-semibold text-primary">{{ $ticket->ticket_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium tracking-wide uppercase text-base-content/50">
                            {{ __('ticket_chat.status') }}</dt>
                <dd class="mt-0.5">
                    @if ($canManage && !$ticket->isClosed())
                        <x-button wire:click="openStatusModal" class="gap-1 px-2 py-1 h-auto min-h-0 btn-ghost btn-sm"
                            icon="o-pencil-square">
                            <x-badge :value="__('ticket_chat.status_' . $ticket->status->value)" :class="$statusBadgeClass" />
                        </x-button>
                    @else
                        <x-badge :value="__('ticket_chat.status_' . $ticket->status->value)" :class="$statusBadgeClass" />
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium tracking-wide uppercase text-base-content/50">
                    {{ __('ticket_chat.priority') }}</dt>
                <dd class="mt-0.5">
                    @if ($canManage && !$ticket->isClosed())
                        <x-button wire:click="openPriorityModal" class="gap-1 px-2 py-1 h-auto min-h-0 btn-ghost btn-sm"
                            icon="o-pencil-square">
                            <x-badge :value="__('ticket_chat.priority_' . $ticket->priority->value)" :class="$priorityBadgeClass" />
                        </x-button>
                    @else
                        <x-badge :value="__('ticket_chat.priority_' . $ticket->priority->value)" :class="$priorityBadgeClass" />
                    @endif
                </dd>
            </div>
            @if ($ticket->department)
                <div>
                    <dt class="text-xs font-medium tracking-wide uppercase text-base-content/50">
                        {{ __('ticket_chat.department') }}</dt>
                    <dd class="mt-0.5">
                        @if ($canManage && !$ticket->isClosed())
                            <x-button wire:click="openTransferModal"
                                class="px-0 h-auto min-h-0 text-left btn-ghost btn-sm" icon="o-pencil-square">
                                {{ $ticket->department->name }}
                            </x-button>
                        @else
                            {{ $ticket->department->name }}
                        @endif
                    </dd>
                </div>
            @endif
            <div class="sm:col-span-2 lg:col-span-3 min-w-0">
                <dt class="text-xs font-medium tracking-wide uppercase text-base-content/50">
                    {{ __('ticket_chat.subject') }}</dt>
                <dd class="mt-0.5 text-lg font-medium break-words">{{ $ticket->title }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium tracking-wide uppercase text-base-content/50">
                    {{ __('ticket_chat.creator') }}</dt>
                <dd class="mt-0.5">
                    @if ($ticket->creator)
                        {{ trim($ticket->creator->name . ' ' . ($ticket->creator->family ?? '')) }}
                    @else
                        —
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium tracking-wide uppercase text-base-content/50">
                    {{ __('ticket_chat.created_at') }}</dt>
                <dd class="mt-0.5">{{ $ticket->created_at->format('Y/m/d H:i') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium tracking-wide uppercase text-base-content/50">
                    {{ __('ticket_chat.operator') }}</dt>
                <dd class="mt-0.5">
                    @if ($canManage && !$ticket->isClosed())
                        <x-button wire:click="openAssignModal" class="px-0 h-auto min-h-0 text-left btn-ghost btn-sm"
                            icon="o-pencil-square">
                            @if ($ticket->assignedAgent)
                                {{ trim($ticket->assignedAgent->name . ' ' . ($ticket->assignedAgent->family ?? '')) }}
                            @else
                                {{ __('ticket_chat.unassigned') }}
                            @endif
                        </x-button>
                    @else
                        @if ($ticket->assignedAgent)
                            {{ trim($ticket->assignedAgent->name . ' ' . ($ticket->assignedAgent->family ?? '')) }}
                        @else
                            —
                        @endif
                    @endif
                </dd>
            </div>
            @if (config('ticket-chat.features.tags', true))
                <div class="sm:col-span-2 lg:col-span-3">
                    <dt class="text-xs font-medium tracking-wide uppercase text-base-content/50">
                        {{ __('ticket_chat.tags') }}</dt>
                    <dd class="flex flex-wrap gap-2 items-center mt-0.5">
                        @forelse($ticket->tags as $tag)
                            <span class="gap-1 badge"
                                @if ($tag->color) style="background-color: {{ $tag->color }}20; color: {{ $tag->color }};" @endif>
                                {{ $tag->name }}
                            </span>
                        @empty
                            <span class="text-base-content/50">{{ __('ticket_chat.no_tags') }}</span>
                        @endforelse
                        @if ($canManage && !$ticket->isClosed())
                            <x-button wire:click="openTagsModal" class="btn-ghost btn-xs" icon="o-pencil-square">
                                {{ __('ticket_chat.manage_tags') }}
                            </x-button>
                        @endif
                    </dd>
                </div>
            @endif
                </dl>
            </div>
        </details>
    </x-card>

    {{-- Chat card --}}
    <x-card class="flex overflow-hidden flex-col mb-4" shadow>
        <div class="flex flex-wrap gap-2 justify-between items-center px-4 py-2 border-b border-base-200">
            <h3 class="font-medium">{{ __('ticket_chat.reply') }}</h3>
            @if ($this->internalNotes->count() > 0)
                <x-button wire:click="openInternalNotesModal" class="btn-ghost btn-sm" icon="o-lock-closed">
                    {{ __('ticket_chat.internal_notes_list') }} ({{ $this->internalNotes->count() }})
                </x-button>
            @endif
        </div>

        {{-- Messages area --}}
        <div class="max-h-[50vh] min-h-[200px] overflow-y-auto p-4" id="ticket-messages">
            @foreach ($this->ticketMessages as $message)
                @php
                    $isOwn = (int) $message->user_id === (int) auth()->id();
                    $userName = $message->user ? trim($message->user->name . ' ' . ($message->user->family ?? '')) : '';
                    $avatarUrl =
                        $message->user && method_exists($message->user, 'getFirstMediaUrl')
                            ? $message->user->getFirstMediaUrl('avatar', Constants::RESOLUTION_512_SQUARE)
                            : null;
                @endphp
                <div class="mb-4 flex {{ $isOwn ? 'justify-end' : 'justify-start' }} min-w-0"
                    wire:key="msg-{{ $message->id }}">
                    <div class="flex max-w-[85%] min-w-0 gap-2 {{ $isOwn ? 'flex-row-reverse' : '' }}">
                        @if (!$isOwn && $avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $userName }}"
                                class="object-cover rounded-full size-9 shrink-0" />
                        @endif
                        @if ($isOwn && $avatarUrl)
                            <img src="{{ $avatarUrl }}" alt=""
                                class="object-cover rounded-full size-9 shrink-0" />
                        @endif
                        <div
                            class="min-w-0 rounded-2xl px-4 py-2.5 shadow-sm {{ $isOwn ? 'bg-primary text-primary-content' : 'bg-base-200' }}">
                            @if (!$isOwn && $userName)
                                <p class="mb-1 text-xs font-semibold opacity-90 break-words">{{ $userName }}</p>
                            @endif
                            @if ($message->replyTo)
                                <div class="mb-2 text-xs break-words border-current opacity-80 border-s-2 ps-2">
                                    {{ Str::limit($message->replyTo->body, 60) }}
                                </div>
                            @endif
                            <div class="text-sm whitespace-pre-wrap break-words min-w-0">{{ $message->body }}</div>
                            @if ($message->attachments->count() > 0)
                                <div class="mt-2 space-y-1">
                                    @foreach ($message->attachments as $att)
                                        @if ($att->isImage())
                                            <a href="{{ $att->url }}" target="_blank" rel="noopener"
                                                class="block">
                                                <img src="{{ $att->thumbnail_url ?? $att->url }}"
                                                    alt="{{ $att->file_name }}"
                                                    class="object-cover max-h-32 rounded" />
                                            </a>
                                        @else
                                            <a href="{{ $att->url }}" target="_blank" rel="noopener"
                                                class="flex gap-1 items-center text-xs link link-hover">
                                                <x-icon name="o-paper-clip" class="size-3.5" />
                                                {{ $att->file_name }} ({{ $att->human_size }})
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            <p class="mt-1 text-xs text-right opacity-70">
                                {{ $message->created_at->format('H:i Y/m/d') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if (!$ticket->isClosed())
            {{-- Reply form: stacked on mobile, row on desktop --}}
            <form wire:submit="sendMessage" class="flex flex-col gap-3 p-3 border-t border-base-200 bg-base-100">
                @if ($replyToId)
                    <p class="text-xs text-base-content/60">
                        {{ __('ticket_chat.reply') }}
                        <button type="button" wire:click="cancelReply" class="font-medium link link-hover">×</button>
                    </p>
                @endif
                @if (config('ticket-chat.features.canned_responses', true) && count($this->cannedResponseOptions) > 0)
                    <div class="w-full sm:max-w-xs">
                        <x-select wire:model.live="selectedCannedId" :placeholder="__('ticket_chat.insert_canned_response')" :options="$this->cannedResponseOptions"
                            option-value="id" option-label="name" class="w-full" clearable />
                    </div>
                @endif
                <label class="flex gap-2 items-center shrink-0">
                    <input type="checkbox" wire:model="isInternalNote" class="checkbox checkbox-sm" />
                    <span class="text-xs">{{ __('ticket_chat.internal_note') }}</span>
                </label>
                <div class="w-full min-w-0">
                    <textarea wire:model="newMessage" rows="3" placeholder="{{ __('ticket_chat.reply') }}..."
                        class="py-2 w-full min-h-[80px] text-sm rounded-xl resize-y textarea textarea-bordered" wire:loading.attr="disabled"></textarea>
                </div>
                <div class="flex gap-2 justify-end items-center flex-wrap">
                    <label class="btn btn-ghost btn-sm btn-square relative" wire:loading.attr="disabled">
                        <x-icon name="o-paper-clip" class="size-5" wire:loading.remove wire:target="attachments" />
                        <span class="loading loading-spinner loading-sm" wire:loading wire:target="attachments"></span>
                        <input type="file" wire:model="attachments" multiple class="hidden"
                            accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip" />
                    </label>
                    <x-button type="submit" class="btn-primary btn-sm" icon="o-paper-airplane"
                        spinner="sendMessage" wire:loading.attr="disabled">
                        {{ __('ticket_chat.send') }}
                    </x-button>
                </div>
                <div wire:loading wire:target="attachments" class="flex gap-2 items-center text-sm text-base-content/60">
                    <span class="loading loading-spinner loading-xs"></span>
                    <span>{{ __('ticket_chat.uploading') }}</span>
                </div>
                @if (count($attachments) > 0)
                    <div class="flex flex-wrap gap-2 items-center p-2 rounded-lg bg-base-200/60">
                        @foreach ($attachments as $index => $file)
                            <span class="inline-flex gap-1 items-center max-w-full px-2 py-1 text-xs rounded-md bg-base-300">
                                <x-icon name="o-document" class="size-3.5 shrink-0" />
                                <span class="truncate min-w-0" title="{{ $file->getClientOriginalName() }}">{{ $file->getClientOriginalName() }}</span>
                                <button type="button" wire:click="removeAttachment({{ $index }})" class="shrink-0 btn btn-ghost btn-xs btn-circle"
                                    aria-label="{{ __('general.delete') }}">
                                    <x-icon name="o-x-mark" class="size-3.5" />
                                </button>
                            </span>
                        @endforeach
                        <span class="text-xs text-base-content/60">{{ count($attachments) }} {{ __('ticket_chat.attachments') }}</span>
                    </div>
                @endif
            </form>
        @else
            <div class="p-4 border-t border-base-200 bg-base-200/50">
                <p class="mb-3 text-sm text-center text-base-content/60">{{ __('ticket_chat.ticket_closed') }}</p>
                @if (config('ticket-chat.features.csat', true))
                    @if ($this->csatFeedback)
                        <div class="p-4 text-center rounded-xl border border-success/30 bg-success/5">
                            <p class="font-medium text-success">{{ __('ticket_chat.csat_your_rating') }}:
                                {{ $this->csatFeedback->rating }}/5</p>
                            @if ($this->csatFeedback->comment)
                                <p class="mt-2 text-sm text-base-content/70">{{ $this->csatFeedback->comment }}</p>
                            @endif
                        </div>
                    @elseif($this->canSubmitCsat)
                        <form wire:submit="submitCsat" class="p-4 rounded-xl border border-base-200 bg-base-100">
                            <p class="mb-3 text-sm font-medium text-center">{{ __('ticket_chat.csat_title') }}</p>
                            <div class="flex gap-1 justify-center mb-3">
                                @foreach (range(1, 5) as $star)
                                    <button type="button" wire:click="$set('csatRating', {{ $star }})"
                                        class="rounded p-1 transition {{ $csatRating >= $star ? 'text-warning' : 'text-base-content/30 hover:text-warning' }}">
                                        <x-icon name="o-star" class="size-8" />
                                    </button>
                                @endforeach
                            </div>
                            <x-textarea wire:model="csatComment" :label="__('ticket_chat.csat_comment')" rows="2" class="mb-3" />
                            <div class="flex justify-center">
                                <x-button type="submit" class="btn-primary btn-sm" spinner="submitCsat"
                                    :label="__('ticket_chat.csat_submit')" />
                            </div>
                        </form>
                    @endif
                @endif
            </div>
        @endif
    </x-card>

    {{-- Internal notes modal --}}
    @if ($showInternalNotesModal)
        <x-modal wire:model="showInternalNotesModal" :title="__('ticket_chat.internal_notes_list')" separator class="modal-lg">
            <div class="max-h-[60vh] space-y-3 overflow-y-auto">
                @foreach ($this->internalNotes as $note)
                    <div class="p-3 rounded-xl border border-warning/30 bg-warning/5">
                        <p class="flex gap-1 items-center mb-1 text-xs font-medium text-warning">
                            <x-icon name="o-lock-closed" class="size-3.5" />
                            @if ($note->user)
                                {{ trim($note->user->name . ' ' . ($note->user->family ?? '')) }}
                            @endif
                            · {{ $note->created_at->format('Y/m/d H:i') }}
                        </p>
                        <div class="text-sm whitespace-pre-wrap break-words">{{ $note->body }}</div>
                    </div>
                @endforeach
            </div>
            <x-slot:actions>
                <x-button wire:click="$set('showInternalNotesModal', false)"
                    class="btn-ghost">{{ __('general.close') }}</x-button>
            </x-slot:actions>
        </x-modal>
    @endif

    {{-- Close confirmation modal --}}
    @if ($showCloseModal)
        <x-modal wire:model="showCloseModal" :title="__('ticket_chat.close_ticket')" separator with-close-button>
            <p class="text-base-content">{{ __('ticket_chat.confirm_close') }}</p>
            <x-slot:actions>
                <x-button wire:click="$set('showCloseModal', false)"
                    class="btn-ghost">{{ __('general.cancel') }}</x-button>
                <x-button wire:click="closeTicket" class="btn-error">{{ __('ticket_chat.close_ticket') }}</x-button>
            </x-slot:actions>
        </x-modal>
    @endif

    {{-- Change status modal: guide + only allowed transitions as buttons --}}
    @if ($showStatusModal)
        <x-modal wire:model="showStatusModal" :title="__('ticket_chat.change_status')" separator with-close-button>
            {{-- راهنمای کامل وضعیت‌ها برای ادمین --}}
            <details class="collapse collapse-arrow bg-base-200/50 rounded-lg mb-4">
                <summary class="collapse-title min-h-0 py-3 font-medium text-base-content">
                    {{ __('ticket_chat.status_guide_title') }}
                </summary>
                <div class="collapse-content text-sm">
                    <p class="text-base-content/80 mb-3">{{ __('ticket_chat.status_guide_intro') }}</p>
                    <ul class="space-y-2 list-none pl-0">
                        <li class="border-b border-base-300/50 pb-2">
                            <span class="font-medium text-base-content">{{ __('ticket_chat.status_open') }}</span>
                            <p class="mt-0.5 text-base-content/70">{{ __('ticket_chat.status_guide_open') }}</p>
                        </li>
                        <li class="border-b border-base-300/50 pb-2">
                            <span class="font-medium text-base-content">{{ __('ticket_chat.status_pending') }}</span>
                            <p class="mt-0.5 text-base-content/70">{{ __('ticket_chat.status_guide_pending') }}</p>
                        </li>
                        <li class="border-b border-base-300/50 pb-2">
                            <span class="font-medium text-base-content">{{ __('ticket_chat.status_in_progress') }}</span>
                            <p class="mt-0.5 text-base-content/70">{{ __('ticket_chat.status_guide_in_progress') }}</p>
                        </li>
                        <li class="border-b border-base-300/50 pb-2">
                            <span class="font-medium text-base-content">{{ __('ticket_chat.status_resolved') }}</span>
                            <p class="mt-0.5 text-base-content/70">{{ __('ticket_chat.status_guide_resolved') }}</p>
                        </li>
                        <li class="border-b border-base-300/50 pb-2">
                            <span class="font-medium text-base-content">{{ __('ticket_chat.status_closed') }}</span>
                            <p class="mt-0.5 text-base-content/70">{{ __('ticket_chat.status_guide_closed') }}</p>
                        </li>
                        <li class="pb-0">
                            <span class="font-medium text-base-content">{{ __('ticket_chat.status_archived') }}</span>
                            <p class="mt-0.5 text-base-content/70">{{ __('ticket_chat.status_guide_archived') }}</p>
                        </li>
                    </ul>
                </div>
            </details>

            <p class="text-base-content/80 mb-2">{{ __('ticket_chat.status') }}</p>
            <div class="flex flex-wrap gap-2">
                @foreach($this->allowedStatusTransitions as $option)
                    <x-button wire:click="changeStatusTo('{{ $option['id'] }}')"
                        wire:loading.attr="disabled"
                        class="btn-outline btn-sm"
                        spinner="changeStatusTo('{{ $option['id'] }}')">
                        {{ $option['name'] }}
                    </x-button>
                @endforeach
            </div>
            @if(empty($this->allowedStatusTransitions))
                <p class="text-base-content/60 text-sm mt-2">{{ __('ticket_chat.no_status_transitions') }}</p>
            @endif
            <x-slot:actions>
                <x-button wire:click="$set('showStatusModal', false)" class="btn-ghost">{{ __('general.cancel') }}</x-button>
            </x-slot:actions>
        </x-modal>
    @endif

    {{-- Change priority modal --}}
    @if ($showPriorityModal)
        <x-modal wire:model="showPriorityModal" :title="__('ticket_chat.change_priority')" separator with-close-button>
            <x-select wire:model="newPriority" :label="__('ticket_chat.priority')" :options="[
                ['id' => 'low', 'name' => __('ticket_chat.priority_low')],
                ['id' => 'medium', 'name' => __('ticket_chat.priority_medium')],
                ['id' => 'high', 'name' => __('ticket_chat.priority_high')],
                ['id' => 'urgent', 'name' => __('ticket_chat.priority_urgent')],
            ]" option-value="id"
                option-label="name" />
            <x-slot:actions>
                <x-button wire:click="$set('showPriorityModal', false)"
                    class="btn-ghost">{{ __('general.cancel') }}</x-button>
                <x-button wire:click="changePriority" class="btn-primary"
                    spinner="changePriority">{{ __('general.submit') }}</x-button>
            </x-slot:actions>
        </x-modal>
    @endif

    {{-- Assign agent modal --}}
    @if ($showAssignModal)
        <x-modal wire:model="showAssignModal" :title="__('ticket_chat.assign_operator')" separator with-close-button>
            <x-select wire:model="assignAgentId" :label="__('ticket_chat.operator')" :options="array_merge([['id' => '', 'name' => __('ticket_chat.unassigned')]], $this->assignableAgents)" option-value="id"
                option-label="name" />
            <x-slot:actions>
                <x-button wire:click="$set('showAssignModal', false)"
                    class="btn-ghost">{{ __('general.cancel') }}</x-button>
                <x-button wire:click="assignAgent" class="btn-primary"
                    spinner="assignAgent">{{ __('general.submit') }}</x-button>
            </x-slot:actions>
        </x-modal>
    @endif

    {{-- Transfer department modal --}}
    @if ($showTransferModal)
        <x-modal wire:model="showTransferModal" :title="__('ticket_chat.transfer_department')" separator with-close-button>
            <x-select wire:model="transferDepartmentId" :label="__('ticket_chat.department')" :options="$this->departmentsForTransfer" option-value="id"
                option-label="name" />
            <x-slot:actions>
                <x-button wire:click="$set('showTransferModal', false)"
                    class="btn-ghost">{{ __('general.cancel') }}</x-button>
                <x-button wire:click="transferDepartment" class="btn-primary"
                    spinner="transferDepartment">{{ __('general.submit') }}</x-button>
            </x-slot:actions>
        </x-modal>
    @endif

    {{-- Tags modal --}}
    @if ($showTagsModal)
        <x-modal wire:model="showTagsModal" :title="__('ticket_chat.manage_tags')" separator with-close-button class="modal-lg">
            <x-choices wire:model="selectedTagIds" :options="$this->allTags" :label="__('ticket_chat.tags')" option-value="id"
                option-label="name" multiple />
            <x-slot:actions>
                <x-button wire:click="$set('showTagsModal', false)"
                    class="btn-ghost">{{ __('general.cancel') }}</x-button>
                <x-button wire:click="saveTags" class="btn-primary"
                    spinner="saveTags">{{ __('general.submit') }}</x-button>
            </x-slot:actions>
        </x-modal>
    @endif

    @script
        <script>
            $wire.on('message-sent', () => {
                const el = document.getElementById('ticket-messages');
                if (el) el.scrollTop = el.scrollHeight;
            });
        </script>
    @endscript
</div>
