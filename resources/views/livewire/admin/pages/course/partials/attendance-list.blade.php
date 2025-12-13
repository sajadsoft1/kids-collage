{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- ATTENDANCE LIST - Teacher View for Marking Attendance with Table --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
@php
    $sessionAttendances = $this->sessionAttendances();
    $headers = [
        ['key' => 'enrollment.user.full_name', 'label' => __('general.table.student_name')],
        ['key' => 'enrollment.user.email', 'label' => __('general.table.email'), 'class' => 'hidden md:table-cell'],
        ['key' => 'status', 'label' => __('general.table.status'), 'sortable' => false],
        ['key' => 'actions', 'label' => __('general.table.actions'), 'sortable' => false, 'class' => 'w-24'],
    ];
@endphp

<x-card>
    <x-slot:title>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                {{ __('attendance.model') }}
                <span class="badge badge-primary badge-sm">
                    {{ $sessionAttendances->count() }} {{ __('user.model') }}
                </span>
            </div>
            <div wire:key="selected-enrollments-{{ count($selectedEnrollments) }}">
                @if (count($selectedEnrollments) > 0)
                    <div class="flex items-center gap-2 bg-primary/10 px-3 py-1.5 rounded-lg border border-primary/20">
                        <x-icon name="o-check-circle" class="w-4 h-4 text-primary" />
                        <span class="text-sm font-semibold text-primary">
                            {{ count($selectedEnrollments) }} {{ __('general.table.selected') }}
                        </span>
                        <x-button wire:click="markSelectedPresent" wire:loading.attr="disabled"
                            wire:target="markSelectedPresent" class="btn-xs btn-success" icon="o-check"
                            spinner="markSelectedPresent">
                            {{ __('attendance.page.all_present') }}
                        </x-button>
                        <x-button wire:click="markSelectedAbsent" wire:loading.attr="disabled"
                            wire:target="markSelectedAbsent" class="btn-xs btn-error" icon="o-x-mark"
                            spinner="markSelectedAbsent">
                            {{ __('attendance.page.all_absent') }}
                        </x-button>
                    </div>
                @endif
            </div>
        </div>
    </x-slot:title>

    @if ($sessionAttendances->count() > 0)
        {{-- Bulk Actions --}}
        <div class="mb-4 p-3 bg-base-200 rounded-lg">
            <div class="flex items-center gap-2 mb-2">
                <x-icon name="o-information-circle" class="w-4 h-4 text-info" />
                <span class="text-xs text-base-content/70">
                    {{ __('attendance.page.bulk_action_hint') }}
                </span>
            </div>
            <div class="flex flex-wrap gap-2">
                <x-button wire:click="markAllPresent" wire:loading.attr="disabled" wire:target="markAllPresent"
                    class="btn-sm btn-success" icon="o-check-circle" spinner="markAllPresent">
                    {{ __('attendance.page.mark_all_present') }}
                </x-button>
                <x-button wire:click="markAllAbsent" wire:loading.attr="disabled" wire:target="markAllAbsent"
                    class="btn-sm btn-error" icon="o-x-circle" spinner="markAllAbsent">
                    {{ __('attendance.page.mark_all_absent') }}
                </x-button>
            </div>
        </div>

        {{-- Attendance Table --}}
        <x-table :headers="$headers" :rows="$sessionAttendances" wire:model.live="selectedEnrollments" selectable
            selectable-key="id">
            {{-- Student Name with Avatar --}}
            @scope('cell_enrollment.user.full_name', $item)
                <div class="flex items-center gap-2.5">
                    <x-avatar :image="$item['enrollment']->user->avatar" class="w-8 h-8" />
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-sm truncate">{{ $item['enrollment']->user->full_name }}</div>
                        <div class="text-xs text-base-content/60 truncate hidden md:block">
                            {{ $item['enrollment']->user->mobile ?? $item['enrollment']->user->email }}
                        </div>
                    </div>
                </div>
            @endscope

            {{-- Email --}}
            @scope('cell_enrollment.user.email', $item)
                <div class="text-sm text-base-content/70">
                    {{ $item['enrollment']->user->email ?? '-' }}
                </div>
            @endscope

            {{-- Status Badge --}}
            @scope('cell_status', $item)
                <div class="flex items-center gap-2">
                    @if ($item['is_present'])
                        <x-badge value="{{ __('attendance.page.present') }}" class="badge-success badge-sm" />
                        @if ($item['arrival_time'])
                            <span class="text-xs text-base-content/60">
                                {{ $item['arrival_time'] }}
                                @if ($item['leave_time'])
                                    - {{ $item['leave_time'] }}
                                @endif
                            </span>
                        @endif
                    @else
                        <x-badge value="{{ __('attendance.page.absent') }}" class="badge-error badge-sm" />
                        @if ($item['excuse_note'])
                            <x-icon name="o-information-circle" class="w-4 h-4 text-warning" />
                        @endif
                    @endif
                </div>
            @endscope

            {{-- Actions --}}
            @scope('actions', $item)
                <div class="flex gap-1.5">
                    <x-button wire:click="markAttendance({{ $item['enrollment']->id }}, true)" wire:loading.attr="disabled"
                        wire:target="markAttendance({{ $item['enrollment']->id }}, true)" wire:loading.class="opacity-50"
                        class="btn-xs {{ $item['is_present'] ? 'btn-success' : 'btn-outline btn-success' }}" icon="o-check"
                        spinner="markAttendance({{ $item['enrollment']->id }}, true)"
                        title="{{ __('attendance.page.present') }}" />
                    <x-button wire:click="markAttendance({{ $item['enrollment']->id }}, false)"
                        wire:loading.attr="disabled" wire:target="markAttendance({{ $item['enrollment']->id }}, false)"
                        wire:loading.class="opacity-50"
                        class="btn-xs {{ !$item['is_present'] ? 'btn-error' : 'btn-outline btn-error' }}" icon="o-x-mark"
                        spinner="markAttendance({{ $item['enrollment']->id }}, false)"
                        title="{{ __('attendance.page.absent') }}" />
                    @if ($item['is_present'] && !$item['leave_time'])
                        <x-button wire:click="recordLeaveTime({{ $item['enrollment']->id }})" wire:loading.attr="disabled"
                            wire:target="recordLeaveTime({{ $item['enrollment']->id }})" class="btn-xs btn-info"
                            icon="o-arrow-left" spinner="recordLeaveTime({{ $item['enrollment']->id }})"
                            title="{{ __('attendance.page.record_leave_time') }}" />
                    @endif
                    @if (!$item['is_present'])
                        <x-button wire:click="openExcuseModal({{ $item['enrollment']->id }})" wire:loading.attr="disabled"
                            wire:target="openExcuseModal({{ $item['enrollment']->id }})" class="btn-xs btn-warning"
                            icon="o-pencil" spinner="openExcuseModal({{ $item['enrollment']->id }})"
                            title="{{ __('attendance.page.record_excuse') }}" />
                    @endif
                </div>
            @endscope
        </x-table>

        {{-- Excuse Note Modal --}}
        <x-modal wire:model="showExcuseModal" :title="__('attendance.page.record_excuse')" separator>
            <div class="space-y-4">
                <x-textarea wire:model="excuseNote" :label="__('attendance.page.excuse_note')"
                    placeholder="{{ __('attendance.page.excuse_note_placeholder') }}" rows="4" />

                <x-slot:actions>
                    <x-button :label="__('general.cancel')" @click="$wire.closeExcuseModal()" class="btn-ghost" />
                    <x-button wire:click="markAbsentWithExcuse" wire:loading.attr="disabled"
                        wire:target="markAbsentWithExcuse" :label="__('attendance.page.mark_absent')" class="btn-error" icon="o-x-mark"
                        spinner="markAbsentWithExcuse" />
                </x-slot:actions>
            </div>
        </x-modal>
    @else
        <x-alert icon="o-information-circle" class="alert-info">
            {{ __('enrollment.page.no_students') }}
        </x-alert>
    @endif
</x-card>
