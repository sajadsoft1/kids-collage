{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- SESSION DETAILS - Main Content Area for Selected Session --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
@php
    $selectedSession = $this->selectedSession();
@endphp

@if ($selectedSession)
    <div class="space-y-4 relative" wire:key="session-details-{{ $selectedSession->id }}">
        {{-- Loading Overlay --}}
        <div wire:loading.delay.shortest wire:target="selectSession({{ $selectedSession->id }})"
            class="absolute inset-0 bg-base-100/80 backdrop-blur-sm z-10 flex items-center justify-center rounded-lg">
            <div class="text-center">
                <x-loading class="loading-spinner loading-lg" />
                <p class="mt-4 text-sm text-base-content/60">{{ __('session.page.loading_session') }}</p>
            </div>
        </div>

        {{-- Session Header --}}
        <x-card>
            <div class="flex items-start justify-between gap-4 mb-3">
                <div class="flex-1">
                    <h2 class="text-xl font-bold mb-2">
                        {{ $selectedSession->sessionTemplate?->title ?? __('session.page.session') }}
                    </h2>
                    @if ($selectedSession->sessionTemplate?->description)
                        <p class="text-sm text-base-content/70 mb-3">
                            {{ $selectedSession->sessionTemplate->description }}
                        </p>
                    @endif
                </div>
                @php
                    $statusColor = match ($selectedSession->status->value) {
                        'done' => 'badge-success',
                        'cancelled' => 'badge-error',
                        default => 'badge-info',
                    };
                @endphp
                <x-badge :value="$selectedSession->status->title()" class="badge-lg {{ $statusColor }}" />
            </div>

            {{-- Session Info Grid - Compact --}}
            <div class="flex flex-wrap gap-2">
                @if ($selectedSession->date)
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-base-50 border border-base-300">
                        <x-icon name="o-calendar" class="w-4 h-4 text-primary" />
                        <span class="text-sm font-semibold">{{ $selectedSession->date->format('Y/m/d') }}</span>
                    </div>
                @endif

                @if ($selectedSession->start_time && $selectedSession->end_time)
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-base-50 border border-base-300">
                        <x-icon name="o-clock" class="w-4 h-4 text-secondary" />
                        <span class="text-sm font-semibold">
                            {{ $selectedSession->start_time->format('H:i') }} -
                            {{ $selectedSession->end_time->format('H:i') }}
                        </span>
                    </div>
                @endif

                @if ($selectedSession->room)
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-base-50 border border-base-300">
                        <x-icon name="o-map-pin" class="w-4 h-4 text-info" />
                        <span class="text-sm font-semibold">{{ $selectedSession->room->name }}</span>
                    </div>
                @endif

                @if ($selectedSession->meeting_link)
                    <a href="{{ $selectedSession->meeting_link }}" target="_blank"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-base-50 border border-base-300 hover:bg-base-100 transition-colors">
                        <x-icon name="o-link" class="w-4 h-4 text-success" />
                        <span
                            class="text-sm font-semibold link link-primary">{{ __('session.page.session_link') }}</span>
                    </a>
                @endif

                @if ($selectedSession->recording_link)
                    <a href="{{ $selectedSession->recording_link }}" target="_blank"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-base-50 border border-base-300 hover:bg-base-100 transition-colors">
                        <x-icon name="o-video-camera" class="w-4 h-4 text-warning" />
                        <span
                            class="text-sm font-semibold link link-warning">{{ __('session.page.recording_link') }}</span>
                    </a>
                @endif
            </div>
        </x-card>

        {{-- Attendance Section (Teacher View) --}}
        @if ($this->isTeacher())
            @include('livewire.admin.pages.course.partials.attendance-list')
        @endif

        {{-- Student Attendance View --}}
        @if ($this->userEnrollment && !$this->isTeacher())
            @include('livewire.admin.pages.course.partials.student-attendance')
        @endif

        {{-- Resources Section --}}
        @include('livewire.admin.pages.course.partials.session-resources')
    </div>
@else
    <x-card>
        <x-alert icon="o-information-circle" class="alert-info">
            {{ __('session.page.select_session') }}
        </x-alert>
    </x-card>
@endif
