<div class="py-5">
    <!-- Dashboard Header with Date Filter -->
    <livewire:admin.pages.dashboard.dashboard-header :startDate="$startDate" :endDate="$endDate" />

    @php
        $teacher = auth()->user();
        $totalClasses = \App\Models\Course::where('teacher_id', $teacher->id)->count();
        $activeClasses = \App\Models\Course::where('teacher_id', $teacher->id)->where('status', 'active')->count();
        $todaySessions = \App\Models\CourseSession::whereHas('course', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })
            ->whereDate('date', today())
            ->count();
    @endphp

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-stat title="{{ __('dashboard.teacher.stats.total_classes') }}" value="{{ $totalClasses }}" icon="o-academic-cap"
            color="text-primary" tooltip="{{ __('dashboard.teacher.stats.total_classes_tooltip') }}" />
        <x-stat title="{{ __('dashboard.teacher.stats.active_classes') }}" value="{{ $activeClasses }}"
            icon="o-check-circle" color="text-success"
            tooltip="{{ __('dashboard.teacher.stats.active_classes_tooltip') }}" />
        <x-stat title="{{ __('dashboard.teacher.stats.today_sessions') }}" value="{{ $todaySessions }}"
            icon="o-calendar" color="text-info" tooltip="{{ __('dashboard.teacher.stats.today_sessions_tooltip') }}" />
    </div>

    <!-- Widgets Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <livewire:admin.widget.teacher-classes-widget :limit="5" :start_date="$startDate" :end_date="$endDate"
            :teacher_id="$teacher->id" wire:key="teacher-classes-widget-{{ $startDate }}-{{ $endDate }}" />
        <livewire:admin.widget.teacher-schedule-widget :limit="5" :teacher_id="$teacher->id"
            wire:key="teacher-schedule-widget-{{ $startDate }}-{{ $endDate }}" />
    </div>

    <!-- Full Width Widgets -->
    <div class="grid grid-cols-1 gap-6">
        <livewire:admin.widget.students-list-widget :limit="10" :start_date="$startDate" :end_date="$endDate"
            :teacher_id="$teacher->id" wire:key="students-list-widget-{{ $startDate }}-{{ $endDate }}" />
        <livewire:admin.widget.attendance-widget :limit="10" :start_date="$startDate" :end_date="$endDate"
            :teacher_id="$teacher->id" wire:key="attendance-widget-{{ $startDate }}-{{ $endDate }}" />
    </div>
</div>
