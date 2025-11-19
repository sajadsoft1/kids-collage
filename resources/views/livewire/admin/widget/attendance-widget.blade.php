<div class="py-5">
    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <x-stat title="{{ __('widgets.attendance.stats.total') }}" value="{{ $this->attendanceStats['total'] }}"
            icon="o-calendar" color="text-primary"
            tooltip="{{ __('widgets.attendance.stats.total_tooltip') }}" />
        <x-stat title="{{ __('widgets.attendance.stats.present') }}" value="{{ $this->attendanceStats['present'] }}"
            icon="o-check-circle" color="text-success"
            tooltip="{{ __('widgets.attendance.stats.present_tooltip') }}" />
        <x-stat title="{{ __('widgets.attendance.stats.absent') }}" value="{{ $this->attendanceStats['absent'] }}"
            icon="o-x-circle" color="text-error"
            tooltip="{{ __('widgets.attendance.stats.absent_tooltip') }}" />
        <x-stat title="{{ __('widgets.attendance.stats.percentage') }}" value="{{ $this->attendanceStats['percentage'] }}%"
            icon="o-chart-bar" color="text-info"
            tooltip="{{ __('widgets.attendance.stats.percentage_tooltip') }}" />
    </div>

    <!-- Attendance List -->
    <x-card title="{{ __('widgets.attendance.title') }}"
        subtitle="{{ __('widgets.attendance.subtitle', ['start_date' => $start_date, 'end_date' => $end_date]) }}" shadow
        separator progress-indicator="update">
        <x-slot:menu>
            <x-button icon="lucide.external-link" class="btn-circle btn-sm" link="{{ $this->getMoreItemsUrl() }}" />
        </x-slot:menu>

        @if ($this->attendances->count() > 0)
            <div class="space-y-3">
                @foreach ($this->attendances as $index => $attendance)
                    <x-list-item :item="$attendance" no-separator
                        wire:key="attendance-widget-{{ $attendance->id }}-{{ $index }}">
                        <x-slot:avatar>
                            <div
                                class="w-10 h-10 rounded-full flex items-center justify-center {{ $attendance->present ? 'bg-success/10' : 'bg-error/10' }}">
                                <x-icon name="{{ $attendance->present ? 'o-check-circle' : 'o-x-circle' }}"
                                    class="w-6 h-6 {{ $attendance->present ? 'text-success' : 'text-error' }}" />
                            </div>
                        </x-slot:avatar>
                        <x-slot:value>
                            <div class="font-medium">
                                {{ $attendance->enrollment->course->template->title ?? __('widgets.attendance.unknown_course') }}
                            </div>
                            <div class="text-sm text-base-content/60">
                                {{ $attendance->enrollment->user->full_name }}
                            </div>
                        </x-slot:value>
                        <x-slot:sub-value>
                            <div class="flex items-center gap-2">
                                <x-badge :value="$attendance->present ? __('widgets.attendance.present') : __('widgets.attendance.absent')"
                                    class="badge-sm {{ $attendance->present ? 'badge-success' : 'badge-error' }}" />
                                @if ($attendance->session?->date)
                                    <span class="text-xs text-base-content/60">
                                        {{ $attendance->session->date->format('Y/m/d') }}
                                    </span>
                                @endif
                            </div>
                        </x-slot:sub-value>
                        <x-slot:actions>
                            <x-button icon="o-eye" class="btn-sm" link="{{ route('admin.attendance.show', $attendance->id) }}" />
                        </x-slot:actions>
                    </x-list-item>
                @endforeach
            </div>
        @else
            <x-admin.shared.empty-view title="{{ __('widgets.attendance.empty_title') }}"
                description="{{ __('widgets.attendance.empty_description', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
                icon="o-calendar" btn_label="{{ __('widgets.attendance.view_all') }}"
                btn_link="{{ $this->getMoreItemsUrl() }}" />
        @endif
    </x-card>
</div>

