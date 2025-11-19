<div class="py-5">
    <!-- Dashboard Header with Date Filter -->
    <livewire:admin.pages.dashboard.dashboard-header :startDate="$startDate" :endDate="$endDate" />

    @php
        $user = auth()->user();
        $totalEnrollments = $user->enrollments()->count();
        $activeEnrollments = $user->enrollments()->where('status', 'active')->count();
        $certificates = $user->enrollments()->whereHas('certificate')->count();
    @endphp

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-stat title="{{ __('dashboard.student.stats.total_courses') }}" value="{{ $totalEnrollments }}"
            icon="o-academic-cap" color="text-primary"
            tooltip="{{ __('dashboard.student.stats.total_courses_tooltip') }}" />
        <x-stat title="{{ __('dashboard.student.stats.active_courses') }}" value="{{ $activeEnrollments }}"
            icon="o-check-circle" color="text-success"
            tooltip="{{ __('dashboard.student.stats.active_courses_tooltip') }}" />
        <x-stat title="{{ __('dashboard.student.stats.certificates') }}" value="{{ $certificates }}" icon="o-trophy"
            color="text-warning" tooltip="{{ __('dashboard.student.stats.certificates_tooltip') }}" />
    </div>

    <!-- Widgets Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <livewire:admin.widget.enrollments-widget :limit="5" :start_date="$startDate" :end_date="$endDate"
            :user_id="$user->id" wire:key="enrollments-widget-{{ $startDate }}-{{ $endDate }}" />
        <livewire:admin.widget.attendance-widget :limit="5" :start_date="$startDate" :end_date="$endDate" :user_id="$user->id"
            wire:key="attendance-widget-{{ $startDate }}-{{ $endDate }}" />
    </div>

    <!-- Full Width Widgets -->
    <div class="grid grid-cols-1 gap-6">
        <livewire:admin.widget.payment-list-widget :limit="10" :start_date="$startDate" :end_date="$endDate"
            :user_id="$user->id" wire:key="payment-list-widget-{{ $startDate }}-{{ $endDate }}" />
        <livewire:admin.widget.latest-tickets-widget :limit="10" :start_date="$startDate" :end_date="$endDate"
            :userId="$user->id" wire:key="latest-tickets-widget-{{ $startDate }}-{{ $endDate }}" />
    </div>
</div>
