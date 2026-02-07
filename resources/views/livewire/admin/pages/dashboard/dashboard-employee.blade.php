<div class="py-5">
    <!-- Dashboard Header with Date Filter -->
    <livewire:admin.pages.dashboard.dashboard-header :startDate="$startDate" :endDate="$endDate" />

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat title="Messages" value="44" icon="o-envelope" tooltip="Hello" color="text-primary" />

        <x-stat title="Sales" description="This month" value="22.124" icon="o-arrow-trending-up" tooltip-bottom="There" />

        <x-stat title="Lost" description="This month" value="34" icon="o-arrow-trending-down"
            tooltip-left="Ops!" />

        <x-stat title="Sales" description="This month" value="22.124" icon="o-arrow-trending-down"
            class="text-orange-500" color="text-pink-500" tooltip-right="Gosh!" />
    </div>

    <!-- Widgets Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <livewire:admin.widget.new-users-widget :limit="5" :start_date="$startDate" :end_date="$endDate"
            wire:key="new-users-widget-{{ $startDate }}-{{ $endDate }}" />
        <livewire:admin.widget.top-blog-writers-widget :limit="5" :start_date="$startDate" :end_date="$endDate"
            wire:key="top-writers-widget-{{ $startDate }}-{{ $endDate }}" />
        <livewire:admin.widget.popular-blogs-widget :limit="5" :start_date="$startDate" :end_date="$endDate"
            wire:key="popular-blogs-widget-{{ $startDate }}-{{ $endDate }}" />
    </div>

</div>
