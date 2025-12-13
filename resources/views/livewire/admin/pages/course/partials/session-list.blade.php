{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- SESSION LIST - Sidebar List of Sessions --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<x-card class="h-full">
    <x-slot:title class="text-base">
        {{ __('session.page.session_list') }}
        <span class="badge badge-primary badge-sm">
            {{ $course->sessions->count() }} {{ __('session.page.session') }}
        </span>
    </x-slot:title>

    @if ($course->sessions->count() > 0)
        <div class="space-y-1.5 max-h-[calc(100vh-400px)] overflow-y-auto">
            @foreach ($course->sessions as $index => $session)
                <button wire:click="selectSession({{ $session->id }})" wire:key="session-{{ $session->id }}"
                    wire:loading.attr="disabled" wire:target="selectSession({{ $session->id }})"
                    wire:loading.class="opacity-50 cursor-wait" @click="$wire.showSessionsDrawer = false" type="button"
                    class="w-full text-right p-2.5 rounded-lg border transition-all duration-200
                      {{ $this->selectedSessionId === $session->id
                          ? 'border-primary bg-primary/10 shadow-md'
                          : 'border-base-300 bg-base-50 hover:bg-primary/10 hover:border-primary/80 hover:shadow-lg' }}
                      cursor-pointer group
                      focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <div class="flex items-center gap-2.5">
                        {{-- Session Number with Loading Indicator --}}
                        <div
                            class="flex-shrink-0 w-7 h-7 rounded-full bg-primary text-primary-content flex items-center justify-center font-bold text-xs relative
                              group-hover:scale-105 group-hover:shadow-lg transition-transform duration-200">
                            <span wire:loading.remove
                                wire:target="selectSession({{ $session->id }})">{{ $index + 1 }}</span>
                            <span wire:loading wire:target="selectSession({{ $session->id }})"
                                class="loading loading-spinner loading-xs"></span>
                        </div>

                        {{-- Session Info --}}
                        <div class="flex-1 min-w-0">
                            <div
                                class="font-semibold text-sm truncate mb-0.5 group-hover:text-primary transition-colors duration-200">
                                {{ $session->sessionTemplate?->title ?? __('session.page.session') . ' ' . ($index + 1) }}
                            </div>
                            <div class="flex items-center gap-2 text-xs text-base-content/60">
                                @if ($session->date)
                                    <span class="flex items-center gap-1">
                                        <x-icon name="o-calendar" class="w-3 h-3" />
                                        {{ $session->date->format('m/d') }}
                                    </span>
                                @endif
                                @if ($session->start_time && $session->end_time)
                                    <span class="flex items-center gap-1">
                                        <x-icon name="o-clock" class="w-3 h-3" />
                                        {{ $session->start_time->format('H:i') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        @php
                            $statusColor = match ($session->status->value) {
                                'done' => 'badge-success',
                                'cancelled' => 'badge-error',
                                default => 'badge-info',
                            };
                        @endphp
                        <x-badge :value="$session->status->title()" class="badge-xs {{ $statusColor }}" />
                    </div>
                </button>
            @endforeach
        </div>
    @else
        <x-alert icon="o-information-circle" class="alert-info">
            {{ __('session.page.no_sessions') }}
        </x-alert>
    @endif
</x-card>
