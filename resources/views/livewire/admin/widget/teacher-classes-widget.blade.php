<div class="py-5">
    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <x-stat title="{{ __('widgets.teacher_classes.stats.total') }}" value="{{ $this->classStats['total'] }}"
            icon="o-academic-cap" color="text-primary" tooltip="{{ __('widgets.teacher_classes.stats.total_tooltip') }}" />
        <x-stat title="{{ __('widgets.teacher_classes.stats.active') }}" value="{{ $this->classStats['active'] }}"
            icon="o-check-circle" color="text-success"
            tooltip="{{ __('widgets.teacher_classes.stats.active_tooltip') }}" />
        <x-stat title="{{ __('widgets.teacher_classes.stats.total_students') }}"
            value="{{ $this->classStats['total_students'] }}" icon="o-users" color="text-info"
            tooltip="{{ __('widgets.teacher_classes.stats.total_students_tooltip') }}" />
    </div>

    <!-- Classes List -->
    <x-card title="{{ __('widgets.teacher_classes.title') }}"
        subtitle="{{ __('widgets.teacher_classes.subtitle', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
        shadow separator progress-indicator="update">
        <x-slot:menu>
            <x-button icon="lucide.external-link" class="btn-circle btn-sm" link="{{ $this->getMoreItemsUrl() }}" />
        </x-slot:menu>

        @if ($this->classes->count() > 0)
            <div class="space-y-3">
                @foreach ($this->classes as $index => $class)
                    <x-list-item :item="$class" no-separator
                        wire:key="teacher-classes-widget-{{ $class->id }}-{{ $index }}">
                        <x-slot:avatar>
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <x-icon name="o-academic-cap" class="w-6 h-6 text-primary" />
                            </div>
                        </x-slot:avatar>
                        <x-slot:value>
                            <div class="font-medium">
                                {{ $class->template->title ?? __('widgets.teacher_classes.unknown_course') }}</div>
                            <div class="text-sm text-base-content/60">
                                {{ $class->term->name ?? __('widgets.teacher_classes.no_term') }}
                            </div>
                        </x-slot:value>
                        <x-slot:sub-value>
                            <div class="flex items-center gap-2">
                                <x-badge :value="$class->status->title()" class="badge-sm badge-{{ $class->status->color() }}" />
                                <span class="text-xs text-base-content/60">
                                    {{ $class->enrollments_count ?? 0 }} {{ __('widgets.teacher_classes.students') }}
                                </span>
                            </div>
                        </x-slot:sub-value>
                        <x-slot:actions>
                            {{-- <x-button icon="o-eye" class="btn-sm" link="{{ route('admin.course.show', $class->id) }}" /> --}}
                        </x-slot:actions>
                    </x-list-item>
                @endforeach
            </div>
        @else
            <x-admin.shared.empty-view title="{{ __('widgets.teacher_classes.empty_title') }}"
                description="{{ __('widgets.teacher_classes.empty_description', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
                icon="o-academic-cap" btn_label="{{ __('widgets.teacher_classes.view_all') }}"
                btn_link="{{ $this->getMoreItemsUrl() }}" />
        @endif
    </x-card>
</div>
