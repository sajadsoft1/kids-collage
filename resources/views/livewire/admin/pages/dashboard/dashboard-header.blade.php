<?php

use Carbon\Carbon;

use function Livewire\Volt\state;

// Widget state properties
state([
    'selectedPeriod' => 'last_30_days',
    'startDate'      => null,
    'endDate'        => null,
]);

// Watch for selectedPeriod changes
$updatedSelectedPeriod = function () {
    $this->setDateRange($this->selectedPeriod);
};

// Available date periods for quick selection
$getDatePeriods = function () {
    return [['key' => 'today', 'value' => trans('dashboard.date_filter.periods.today')], ['key' => 'yesterday', 'value' => trans('dashboard.date_filter.periods.yesterday')], ['key' => 'last_7_days', 'value' => trans('dashboard.date_filter.periods.last_7_days')], ['key' => 'last_30_days', 'value' => trans('dashboard.date_filter.periods.last_30_days')], ['key' => 'last_90_days', 'value' => trans('dashboard.date_filter.periods.last_90_days')], ['key' => 'this_month', 'value' => trans('dashboard.date_filter.periods.this_month')], ['key' => 'last_month', 'value' => trans('dashboard.date_filter.periods.last_month')], ['key' => 'this_year', 'value' => trans('dashboard.date_filter.periods.this_year')], ['key' => 'custom', 'value' => trans('dashboard.date_filter.periods.custom')]];
};

// Set date range based on selected period
$setDateRange = function (string $period) {
    $this->selectedPeriod = $period;

    switch ($period) {
        case 'today':
            $this->startDate = Carbon::today()->format('Y-m-d');
            $this->endDate   = Carbon::today()->format('Y-m-d');

            break;
        case 'yesterday':
            $this->startDate = Carbon::yesterday()->format('Y-m-d');
            $this->endDate   = Carbon::yesterday()->format('Y-m-d');

            break;
        case 'last_7_days':
            $this->startDate = Carbon::now()->subDays(7)->format('Y-m-d');
            $this->endDate   = Carbon::today()->format('Y-m-d');

            break;
        case 'last_30_days':
            $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
            $this->endDate   = Carbon::today()->format('Y-m-d');

            break;
        case 'last_90_days':
            $this->startDate = Carbon::now()->subDays(90)->format('Y-m-d');
            $this->endDate   = Carbon::today()->format('Y-m-d');

            break;
        case 'this_month':
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $this->endDate   = Carbon::today()->format('Y-m-d');

            break;
        case 'last_month':
            $this->startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
            $this->endDate   = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');

            break;
        case 'this_year':
            $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d');
            $this->endDate   = Carbon::today()->format('Y-m-d');

            break;
    }

    // Emit event to notify parent component and widgets
    $this->dispatch('dateRangeChanged', [
        'startDate' => $this->startDate,
        'endDate'   => $this->endDate,
        'period'    => $this->selectedPeriod,
    ]);
};

// Get formatted display text for current selection
$getDisplayText = function () {
    $periods = $this->getDatePeriods();

    return $periods[$this->selectedPeriod] ?? trans('dashboard.date_filter.periods.last_30_days');
};

?>

<div class="">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <!-- Dashboard Title -->
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ trans('dashboard.title') }}
            </h1>
        </div>

        <!-- Date Filter Section -->
        <div class="flex items-center gap-3">
            <!-- Date Range Select -->
            <x-select wire:model.live="selectedPeriod" :options="$this->getDatePeriods()" option-value="key" option-label="value"
                icon="o-calendar" class="w-48" />

            <!-- Refresh Button -->
            <x-button wire:click="$refresh" class="btn-outline" title="{{ trans('dashboard.refresh') }}">
                <x-icon name="o-arrow-path" class="w-4 h-4" />
            </x-button>
        </div>
    </div>

    <!-- Date Range Display -->
    <x-alert icon="o-exclamation-triangle" class="bg-base-100 dark:bg-base-200 mb-4">
        {{ trans('dashboard.date_filter.showing_data_from') }}
        <strong>{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }}</strong>
        {{ trans('dashboard.date_filter.to') }}
        <strong>{{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</strong>
    </x-alert>

</div>
