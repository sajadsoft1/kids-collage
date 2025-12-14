{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- STUDENT ATTENDANCE VIEW --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
@php
    $attendance = $this->userAttendanceForSession();
@endphp

<x-card>
    <x-slot:title>
        {{ __('attendance.page.my_attendance_status') }}
    </x-slot:title>

    @if ($attendance)
        <div
            class="flex flex-wrap gap-4 items-center p-3 rounded-lg {{ $attendance->present->value ? 'bg-success/10 border border-success/20' : 'bg-error/10 border border-error/20' }}">
            {{-- Status --}}
            <div class="flex flex-shrink-0 gap-3 items-center">
                <x-icon name="o-{{ $attendance->present->value ? 'check-circle' : 'x-circle' }}"
                    class="w-6 h-6 text-{{ $attendance->present->value ? 'success' : 'error' }}" />
                <div>
                    <div class="text-xs text-base-content/60">{{ __('attendance.page.status') }}</div>
                    @if ($attendance->present->value)
                        <x-badge value="{{ __('attendance.page.present') }}" class="badge-success badge-sm" />
                    @else
                        <x-badge value="{{ __('attendance.page.absent') }}" class="badge-error badge-sm" />
                    @endif
                </div>
            </div>

            {{-- Details - Inline --}}
            @if ($attendance->present->value)
                <div class="flex flex-wrap gap-4 items-center pl-4 border-l border-base-300">
                    @if ($attendance->arrival_time && $attendance->leave_time)
                        <div class="flex gap-1 items-center text-sm">
                            <x-icon name="o-arrow-right" class="w-3 h-3 text-base-content/60" />
                            <span class="text-base-content/60">{{ __('attendance.page.arrival') }}:</span>
                            <span class="font-semibold">{{ $attendance->arrival_time->format('H:i') }}</span>
                        </div>
                        <div class="flex gap-1 items-center text-sm">
                            <x-icon name="o-arrow-left" class="w-3 h-3 text-base-content/60" />
                            <span class="text-base-content/60">{{ __('attendance.page.departure') }}:</span>
                            <span class="font-semibold">{{ $attendance->leave_time->format('H:i') }}</span>
                        </div>
                    @elseif ($attendance->arrival_time)
                        <div class="flex gap-1 items-center text-sm">
                            <x-icon name="o-arrow-right" class="w-3 h-3 text-base-content/60" />
                            <span class="text-base-content/60">{{ __('attendance.page.arrival_time') }}:</span>
                            <span class="font-semibold">{{ $attendance->arrival_time->format('H:i') }}</span>
                        </div>
                    @endif

                    @if ($attendance->isLate())
                        <x-badge
                            value="{{ __('attendance.page.delay') }}: {{ $attendance->lateness_minutes }} {{ __('attendance.page.minutes') }}"
                            class="badge-warning badge-sm" />
                    @endif
                </div>
            @else
                @if ($attendance->excuse_note)
                    <div class="flex-1 pl-4 border-l border-base-300">
                        <div class="text-xs text-base-content/60">{{ __('attendance.page.excuse') }}:</div>
                        <div class="text-sm">{{ $attendance->excuse_note }}</div>
                    </div>
                @endif
            @endif
        </div>
    @else
        <x-alert icon="o-information-circle" class="alert-info">
            {{ __('attendance.page.attendance_not_recorded') }}
        </x-alert>
    @endif
</x-card>
