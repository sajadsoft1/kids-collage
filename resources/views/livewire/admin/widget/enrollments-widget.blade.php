<div class="py-5">
    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <x-stat title="{{ __('widgets.enrollments.stats.total') }}" value="{{ $this->enrollmentStats['total'] }}"
            icon="o-academic-cap" color="text-primary" tooltip="{{ __('widgets.enrollments.stats.total_tooltip') }}" />
        <x-stat title="{{ __('widgets.enrollments.stats.active') }}" value="{{ $this->enrollmentStats['active'] }}"
            icon="o-check-circle" color="text-success" tooltip="{{ __('widgets.enrollments.stats.active_tooltip') }}" />
        <x-stat title="{{ __('widgets.enrollments.stats.completed') }}"
            value="{{ $this->enrollmentStats['completed'] }}" icon="o-trophy" color="text-warning"
            tooltip="{{ __('widgets.enrollments.stats.completed_tooltip') }}" />
        <x-stat title="{{ __('widgets.enrollments.stats.pending') }}" value="{{ $this->enrollmentStats['pending'] }}"
            icon="o-clock" color="text-info" tooltip="{{ __('widgets.enrollments.stats.pending_tooltip') }}" />
    </div>

    <!-- Enrollments List -->
    <x-card title="{{ __('widgets.enrollments.title') }}"
        subtitle="{{ __('widgets.enrollments.subtitle', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
        shadow separator progress-indicator="update">
        <x-slot:menu>
            <x-button icon="lucide.external-link" class="btn-circle btn-sm" link="{{ $this->getMoreItemsUrl() }}" />
        </x-slot:menu>

        @if ($this->enrollments->count() > 0)
            <div class="space-y-3">
                @foreach ($this->enrollments as $index => $enrollment)
                    <x-list-item :item="$enrollment" no-separator
                        wire:key="enrollments-widget-{{ $enrollment->id }}-{{ $index }}">
                        <x-slot:avatar>
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <x-icon name="o-academic-cap" class="w-6 h-6 text-primary" />
                            </div>
                        </x-slot:avatar>
                        <x-slot:value>
                            <div class="font-medium">
                                {{ $enrollment->course->template->title ?? __('widgets.enrollments.unknown_course') }}
                            </div>
                            <div class="text-sm text-base-content/60">
                                {{ $enrollment->user->full_name }}
                            </div>
                        </x-slot:value>
                        <x-slot:sub-value>
                            <div class="flex items-center gap-2">
                                <x-badge :value="$enrollment->status->title()" class="badge-sm badge-{{ $enrollment->status->color() }}" />
                                <span class="text-xs text-base-content/60">
                                    {{ number_format($enrollment->progress_percent, 1) }}%
                                    {{ __('widgets.enrollments.progress') }}
                                </span>
                            </div>
                        </x-slot:sub-value>
                        <x-slot:actions>
                            <x-button icon="o-eye" class="btn-sm"
                                link="{{ route('admin.enrollment.show', $enrollment->id) }}" />
                        </x-slot:actions>
                    </x-list-item>
                @endforeach
            </div>
        @else
            <x-admin.shared.empty-view title="{{ __('widgets.enrollments.empty_title') }}"
                description="{{ __('widgets.enrollments.empty_description', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
                icon="o-academic-cap" btn_label="{{ __('widgets.enrollments.view_all') }}"
                btn_link="{{ $this->getMoreItemsUrl() }}" />
        @endif
    </x-card>
</div>
