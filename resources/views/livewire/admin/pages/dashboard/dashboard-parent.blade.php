<div class="py-5">
    <!-- Dashboard Header with Date Filter -->
    <livewire:admin.pages.dashboard.dashboard-header :startDate="$startDate" :endDate="$endDate" />

    @php
        $parent = auth()->user();
        $children = $parent->children;
        $childrenIds = $children->pluck('id')->toArray();
        $totalActiveEnrollments = \App\Models\Enrollment::whereIn('user_id', $childrenIds)
            ->where('status', 'active')
            ->count();
    @endphp

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <x-stat title="{{ __('dashboard.parent.stats.total_children') }}" value="{{ $children->count() }}"
            icon="o-user-group" color="text-primary" tooltip="{{ __('dashboard.parent.stats.total_children_tooltip') }}" />
        <x-stat title="{{ __('dashboard.parent.stats.active_courses') }}" value="{{ $totalActiveEnrollments }}"
            icon="o-academic-cap" color="text-success"
            tooltip="{{ __('dashboard.parent.stats.active_courses_tooltip') }}" />
    </div>

    <!-- Widgets Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <livewire:admin.widget.children-list-widget :limit="5" :parent_id="$parent->id"
            wire:key="children-list-widget-{{ $startDate }}-{{ $endDate }}" />
        <livewire:admin.widget.enrollments-widget :limit="5" :start_date="$startDate" :end_date="$endDate"
            :user_ids="$childrenIds" wire:key="enrollments-widget-{{ $startDate }}-{{ $endDate }}" />
    </div>

    <!-- Full Width Widgets -->
    <div class="grid grid-cols-1 gap-6">
        <livewire:admin.widget.attendance-widget :limit="10" :start_date="$startDate" :end_date="$endDate"
            :user_ids="$childrenIds" wire:key="attendance-widget-{{ $startDate }}-{{ $endDate }}" />
        <livewire:admin.widget.payment-list-widget :limit="10" :start_date="$startDate" :end_date="$endDate"
            :user_ids="$childrenIds" wire:key="payment-list-widget-{{ $startDate }}-{{ $endDate }}" />
        <livewire:admin.widget.latest-tickets-widget :limit="10" :start_date="$startDate" :end_date="$endDate"
            :userId="$parent->id" wire:key="latest-tickets-widget-{{ $startDate }}-{{ $endDate }}" />
    </div>
</div>
