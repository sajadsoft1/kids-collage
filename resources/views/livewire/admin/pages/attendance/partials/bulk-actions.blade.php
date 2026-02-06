{{-- Bulk actions bar: shown in realtime via Alpine store (checkbox uses deferred wire:model) --}}
@if ($this->canBulkUpdateAttendance())
    <div
        class="mb-3 flex flex-wrap items-center gap-2 rounded-lg border border-primary/20 bg-primary/10 px-3 py-2"
        x-data="{ tableName: '{{ $tableName }}', selectedLabel: @js(__('general.table.selected')) }"
        x-show="Alpine.store('pgBulkActions').count(tableName) > 0"
        x-cloak
        x-transition
    >
        <x-icon name="o-check-circle" class="h-4 w-4 text-primary" />
        <span class="text-sm font-semibold text-primary" x-text="Alpine.store('pgBulkActions').count(tableName) + ' ' + selectedLabel"></span>
        <x-button
            wire:click="markBulkPresent"
            wire:loading.attr="disabled"
            wire:target="markBulkPresent"
            class="btn-xs btn-success"
            icon="o-check"
            :spinner="'markBulkPresent'"
        >
            {{ __('attendance.page.all_present') }}
        </x-button>
        <x-button
            wire:click="markBulkAbsent"
            wire:loading.attr="disabled"
            wire:target="markBulkAbsent"
            class="btn-xs btn-error"
            icon="o-x-mark"
            :spinner="'markBulkAbsent'"
        >
            {{ __('attendance.page.all_absent') }}
        </x-button>
    </div>
@endif
