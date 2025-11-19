<div class="py-5">
    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <x-stat title="{{ __('widgets.teacher_schedule.stats.today') }}" value="{{ $this->scheduleStats['today'] }}"
            icon="o-calendar" color="text-primary"
            tooltip="{{ __('widgets.teacher_schedule.stats.today_tooltip') }}" />
        <x-stat title="{{ __('widgets.teacher_schedule.stats.this_week') }}"
            value="{{ $this->scheduleStats['this_week'] }}" icon="o-calendar-days" color="text-info"
            tooltip="{{ __('widgets.teacher_schedule.stats.this_week_tooltip') }}" />
    </div>

    <!-- Schedule List -->
    <x-card title="{{ __('widgets.teacher_schedule.title') }}"
        subtitle="{{ __('widgets.teacher_schedule.subtitle', ['date' => $date]) }}" shadow separator
        progress-indicator="update">
        <x-slot:menu>
            <x-button icon="lucide.external-link" class="btn-circle btn-sm" link="{{ $this->getMoreItemsUrl() }}" />
        </x-slot:menu>

        @if ($this->sessions->count() > 0)
            <div class="space-y-3">
                @foreach ($this->sessions as $index => $session)
                    <x-list-item :item="$session" no-separator
                        wire:key="teacher-schedule-widget-{{ $session->id }}-{{ $index }}">
                        <x-slot:avatar>
                            <div class="w-10 h-10 rounded-full bg-info/10 flex items-center justify-center">
                                <x-icon name="o-clock" class="w-6 h-6 text-info" />
                            </div>
                        </x-slot:avatar>
                        <x-slot:value>
                            <div class="font-medium">
                                {{ $session->course->template->title ?? __('widgets.teacher_schedule.unknown_course') }}
                            </div>
                            <div class="text-sm text-base-content/60">
                                @if ($session->start_time && $session->end_time)
                                    {{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}
                                @else
                                    {{ __('widgets.teacher_schedule.time_tbd') }}
                                @endif
                            </div>
                        </x-slot:value>
                        <x-slot:sub-value>
                            <div class="flex items-center gap-2">
                                @if ($session->room)
                                    <x-badge :value="$session->room->name" class="badge-sm badge-info" />
                                @elseif ($session->meeting_link)
                                    <x-badge value="{{ __('widgets.teacher_schedule.online') }}" class="badge-sm badge-success" />
                                @else
                                    <x-badge value="{{ __('widgets.teacher_schedule.tbd') }}" class="badge-sm badge-neutral" />
                                @endif
                            </div>
                        </x-slot:sub-value>
                        <x-slot:actions>
                            <x-button icon="o-eye" class="btn-sm" link="{{ route('admin.course-session.show', $session->id) }}" />
                        </x-slot:actions>
                    </x-list-item>
                @endforeach
            </div>
        @else
            <x-admin.shared.empty-view title="{{ __('widgets.teacher_schedule.empty_title') }}"
                description="{{ __('widgets.teacher_schedule.empty_description', ['date' => $date]) }}" icon="o-calendar"
                btn_label="{{ __('widgets.teacher_schedule.view_all') }}" btn_link="{{ $this->getMoreItemsUrl() }}" />
        @endif
    </x-card>
</div>

