<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    @if (!config('ticket-chat.features.csat', true))
        <x-card shadow>
            <p class="text-base-content/60">{{ __('ticket_chat.feedback_report_disabled') }}</p>
        </x-card>
    @else
        {{-- Stats --}}
        <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
            <x-card shadow class="border border-primary/20 bg-primary/5">
                <div class="flex items-center gap-3">
                    <x-icon name="o-star" class="size-10 text-primary" />
                    <div>
                        <p class="text-sm font-medium uppercase tracking-wide text-base-content/50">{{ __('ticket_chat.feedback_average') }}</p>
                        <p class="text-2xl font-bold text-primary">{{ $this->stats['average'] }}/5</p>
                    </div>
                </div>
            </x-card>
            <x-card shadow class="border border-info/20 bg-info/5">
                <div class="flex items-center gap-3">
                    <x-icon name="o-chat-bubble-left-right" class="size-10 text-info" />
                    <div>
                        <p class="text-sm font-medium uppercase tracking-wide text-base-content/50">{{ __('ticket_chat.feedback_total') }}</p>
                        <p class="text-2xl font-bold text-info">{{ $this->stats['total'] }}</p>
                    </div>
                </div>
            </x-card>
            <x-card shadow>
                <p class="mb-2 text-sm font-medium uppercase tracking-wide text-base-content/50">{{ __('ticket_chat.feedback_distribution') }}</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($this->stats['distribution'] as $rating => $count)
                        <span class="badge badge-ghost gap-1">{{ $rating }}: {{ $count }}</span>
                    @endforeach
                </div>
            </x-card>
        </div>

        {{-- Recent feedbacks table --}}
        <x-card :title="__('ticket_chat.feedback_recent')" shadow>
            @if ($this->feedbacks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>{{ __('ticket_chat.ticket_number') }}</th>
                                <th>{{ __('ticket_chat.subject') }}</th>
                                <th>{{ __('ticket_chat.csat_your_rating') }}</th>
                                <th>{{ __('ticket_chat.csat_comment') }}</th>
                                <th>{{ __('ticket_chat.creator') }}</th>
                                <th>{{ __('ticket_chat.created_at') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->feedbacks as $feedback)
                                <tr wire:key="feedback-{{ $feedback->id }}">
                                    <td>
                                        @if ($feedback->conversation)
                                            <a href="{{ route('admin.ticket-chat.show', ['ticket' => $feedback->conversation->id]) }}" wire:navigate class="link link-hover font-mono text-primary">
                                                {{ $feedback->conversation->ticket_number ?? '—' }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $feedback->conversation->title ?? '—' }}</td>
                                    <td>
                                        <span class="badge {{ $feedback->rating >= 4 ? 'badge-success' : ($feedback->rating <= 2 ? 'badge-error' : 'badge-warning') }}">
                                            {{ $feedback->rating }}/5
                                        </span>
                                    </td>
                                    <td class="max-w-xs truncate">{{ Str::limit($feedback->comment, 40) ?: '—' }}</td>
                                    <td>
                                        @if ($feedback->user)
                                            {{ trim($feedback->user->name . ' ' . ($feedback->user->family ?? '')) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-sm text-base-content/60">{{ $feedback->created_at->format('Y/m/d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $this->feedbacks->links() }}
                </div>
            @else
                <p class="py-8 text-center text-base-content/60">{{ __('ticket_chat.no_feedbacks_yet') }}</p>
            @endif
        </x-card>
    @endif
</div>
