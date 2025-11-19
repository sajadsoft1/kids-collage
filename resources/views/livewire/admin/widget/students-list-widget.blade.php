<div class="py-5">
    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <x-stat title="{{ __('widgets.students_list.stats.total') }}" value="{{ $this->studentStats['total'] }}"
            icon="o-users" color="text-primary"
            tooltip="{{ __('widgets.students_list.stats.total_tooltip') }}" />
        <x-stat title="{{ __('widgets.students_list.stats.with_active_enrollments') }}"
            value="{{ $this->studentStats['with_active_enrollments'] }}" icon="o-check-circle" color="text-success"
            tooltip="{{ __('widgets.students_list.stats.with_active_enrollments_tooltip') }}" />
    </div>

    <!-- Students List -->
    <x-card title="{{ __('widgets.students_list.title') }}"
        subtitle="{{ __('widgets.students_list.subtitle', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
        shadow separator progress-indicator="update">
        <x-slot:menu>
            <x-button icon="lucide.external-link" class="btn-circle btn-sm" link="{{ $this->getMoreItemsUrl() }}" />
        </x-slot:menu>

        @if ($this->students->count() > 0)
            <div class="space-y-3">
                @foreach ($this->students as $index => $student)
                    <x-list-item :item="$student" no-separator wire:key="students-list-widget-{{ $student->id }}-{{ $index }}">
                        <x-slot:avatar>
                            <img src="{{ $student->getFirstMediaUrl('avatar', '50_square') }}" alt="{{ $student->full_name }}"
                                class="w-10 h-10 rounded-full object-cover" />
                        </x-slot:avatar>
                        <x-slot:value>
                            <div class="font-medium">{{ $student->full_name }}</div>
                            <div class="text-sm text-base-content/60">
                                {{ $student->email ?? $student->mobile }}
                            </div>
                        </x-slot:value>
                        <x-slot:sub-value>
                            <div class="flex items-center gap-2">
                                @php
                                    $activeEnrollments = $student->enrollments()->where('status', 'active')->count();
                                @endphp
                                <x-badge :value="$activeEnrollments . ' ' . __('widgets.students_list.active_courses')"
                                    class="badge-sm badge-info" />
                            </div>
                        </x-slot:sub-value>
                        <x-slot:actions>
                            <x-button icon="o-eye" class="btn-sm" link="{{ route('admin.user.edit', $student->id) }}" />
                        </x-slot:actions>
                    </x-list-item>
                @endforeach
            </div>
        @else
            <x-admin.shared.empty-view title="{{ __('widgets.students_list.empty_title') }}"
                description="{{ __('widgets.students_list.empty_description', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
                icon="o-users" btn_label="{{ __('widgets.students_list.view_all') }}"
                btn_link="{{ $this->getMoreItemsUrl() }}" />
        @endif
    </x-card>
</div>

