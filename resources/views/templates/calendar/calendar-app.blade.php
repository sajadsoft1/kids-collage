<div @if ($pollMillis !== null && $pollAction !== null) wire:poll.{{ $pollMillis }}ms="{{ $pollAction }}"
    @elseif($pollMillis !== null)
        wire:poll.{{ $pollMillis }}ms @endif
    class="py-6 bg-base-200">
    <div class="px-4 mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
        <div>
            @includeIf($beforeCalendarView)
        </div>

        <div class="flex flex-wrap gap-6 justify-between items-center">
            <div>
                <p class="text-sm text-base-content/70">{{ $currentMonthSubtitle }}</p>
                <div class="flex gap-2 items-center">
                    <h1 class="text-3xl font-semibold text-base-content">
                        {{ $currentMonthLabel }}
                    </h1>
                    <span class="text-2xl">✨</span>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 items-center">
                <div class="flex p-1 ring-1 shadow-sm bg-base-100 border-base-content/20">
                    @foreach ($availableCalendarTypes as $type => $label)
                        <x-button wire:click="setCalendarType('{{ $type }}')" :label="$label" spinner
                            wire:loading.attr="disabled" wire:target="setCalendarType('{{ $type }}')"
                            class="px-4 py-2 text-sm font-medium transition {{ $calendarType === $type ? 'btn-primary' : 'btn-ghost' }}" />
                    @endforeach
                </div>

                <x-button wire:click="$toggle('showTaskDrawer')" icon="o-plus" :label="__('calendar.actions.create_task')" class="btn-primary" />
            </div>
        </div>

        <div class="flex flex-wrap gap-3 items-center">
            @foreach ($categoryFilters as $filter)
                @php
                    $isActive = in_array($filter['slug'], $activeCategorySlugs, true);
                @endphp
                <x-button wire:click="toggleCategoryFilter('{{ $filter['slug'] }}')" :icon="$isActive ? 'o-check' : 'o-plus'" spinner
                    wire:loading.attr="disabled" wire:target="toggleCategoryFilter('{{ $filter['slug'] }}')"
                    :label="$filter['label']"
                    class="inline-flex items-center gap-2 {{ $isActive ? $filter['chip_classes'] : 'border-base-content/20 bg-base-300 text-base-content/70' }}" />
            @endforeach

            <x-button wire:click="resetCategoryFilters" icon="o-plus" :label="__('calendar.actions.reset_filters')"
                class="inline-flex gap-2 items-center btn-dash btn-ghost" />
        </div>

        <x-drawer wire:model="showTaskDrawer" :title="$isEditingTask ? __('calendar.form.edit_task') : __('calendar.form.create_task')" separator with-close-button close-on-escape
            class="w-11/12 lg:w-1/2">
            <form wire:submit.prevent="saveTask" id="taskForm" class="space-y-4">
                <x-input :label="__('calendar.form.title')" required wire:model="taskForm.title" icon="o-document-text"
                    :hint="__('calendar.form.title_hint')" />

                @if ($calendarType === 'jalali')
                    <x-admin.shared.smart-datetime :label="__('calendar.form.scheduled_for')" wire-property-name="taskForm.scheduled_for"
                        :with-time="true" return-format="YYYY-MM-DD HH:mm:ss" :default-date="$selectedDay?->format('Y-m-d H:i:s')" :required="true" />
                @else
                    <x-datetime :label="__('calendar.form.scheduled_for')" wire:model="taskForm.scheduled_for" type="datetime-local" />
                @endif

                <x-textarea :label="__('calendar.form.description')" wire:model="taskForm.description" rows="4" :hint="__('calendar.form.description_hint')" />

                <div class="flex gap-3 justify-end mt-6">
                    <x-button :label="__('calendar.actions.cancel')" type="button" @click="$wire.showTaskDrawer = false" class="btn-ghost"
                        wire:loading.attr="disabled" wire:target="saveTask" />
                    <x-button type="submit" :label="$isEditingTask ? __('calendar.form.update') : __('calendar.form.submit')" icon="o-check" class="btn-primary" spinner
                        wire:loading.attr="disabled" wire:target="saveTask" />
                </div>
            </form>
        </x-drawer>

        @if ($selectedEvent)
            @php
                $eventDate =
                    $selectedEvent['date'] instanceof \Carbon\CarbonInterface
                        ? $selectedEvent['date']
                        : \Carbon\Carbon::parse($selectedEvent['date']);
                $eventEnd = $selectedEvent['end'] ?? null;
                $eventEndDate =
                    $eventEnd instanceof \Carbon\CarbonInterface
                        ? $eventEnd
                        : ($eventEnd
                            ? \Carbon\Carbon::parse($eventEnd)
                            : null);
                $eventLabel =
                    $calendarType === 'jalali'
                        ? \Morilog\Jalali\Jalalian::fromCarbon($eventDate)->format('%A %d %B %Y')
                        : $eventDate->translatedFormat('l, F j, Y');
            @endphp
            <x-modal wire:model="showEventModal" :title="$selectedEvent['title'] ?? __('calendar.event.details')" separator with-close-button close-on-escape
                class="backdrop-blur">
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-base-content/20">
                        <span
                            class="h-2 w-2 rounded-full {{ $selectedEvent['category']['dot_classes'] ?? 'bg-slate-400' }}"></span>
                        <span class="text-sm font-semibold text-base-content">
                            {{ $selectedEvent['category']['label'] ?? 'Event' }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-base-content/70">
                                {{ __('calendar.event.date') }}
                            </p>
                            <p class="text-sm text-base-content">{{ $eventLabel }}</p>
                        </div>

                        @if ($eventEndDate)
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-base-content/70">
                                    {{ __('calendar.event.time') }}
                                </p>
                                <p class="text-sm text-base-content">
                                    {{ $eventDate->format('g:i A') }} – {{ $eventEndDate->format('g:i A') }}
                                </p>
                            </div>
                        @else
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-base-content/70">
                                    {{ __('calendar.event.time') }}
                                </p>
                                <p class="text-sm text-base-content">{{ $eventDate->format('g:i A') }}</p>
                            </div>
                        @endif

                        @if ($selectedEvent['description'])
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-base-content/70">
                                    {{ __('calendar.event.description') }}
                                </p>
                                <p class="text-sm text-base-content">{{ $selectedEvent['description'] }}</p>
                            </div>
                        @endif

                        @if ($selectedEvent['location'])
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-base-content/70">
                                    {{ __('calendar.event.location') }}
                                </p>
                                <p class="text-sm text-base-content">{{ $selectedEvent['location'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <x-slot:actions>
                    <x-button :label="__('calendar.actions.close')" @click="$wire.showEventModal = false" class="btn-ghost" />
                </x-slot:actions>
            </x-modal>
        @endif

        @if ($selectedDay)
            @php
                $dayLabel =
                    $calendarType === 'jalali'
                        ? \Morilog\Jalali\Jalalian::fromCarbon($selectedDay)->format('%A %d %B %Y')
                        : $selectedDay->translatedFormat('l, F j, Y');
            @endphp
            <x-modal wire:model="showDayModal" :title="$dayLabel" separator with-close-button close-on-escape
                class="backdrop-blur">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-base-content/20">
                        <span class="text-sm font-semibold text-base-content/70">
                            {{ $selectedDayEvents->count() }}
                            {{ \Illuminate\Support\Str::plural('event', $selectedDayEvents->count()) }}
                        </span>
                        <x-button wire:click="$toggle('showTaskDrawer'); $wire.showDayModal = false;" icon="o-plus"
                            :label="__('calendar.actions.create_task')" class="btn-sm btn-primary" />
                    </div>

                    <div class="space-y-3 max-h-[60vh] overflow-y-auto">
                        @forelse ($selectedDayEvents as $event)
                            @include($eventView, ['event' => $event, 'compact' => false])
                        @empty
                            <div class="p-8 text-center rounded-lg border border-base-content/20 bg-base-200">
                                <p class="text-sm text-base-content/70">{{ __('calendar.empty.no_events') }}</p>
                                <x-button wire:click="$toggle('showTaskDrawer'); $wire.showDayModal = false;"
                                    icon="o-plus" :label="__('calendar.actions.create_task')" class="mt-4 btn-primary btn-sm" />
                            </div>
                        @endforelse
                    </div>
                </div>

                <x-slot:actions>
                    <x-button :label="__('calendar.actions.close')" @click="$wire.showDayModal = false" class="btn-ghost" />
                </x-slot:actions>
            </x-modal>
        @endif

        <div class="p-6 ring-1 shadow-xl bg-base-100 border-base-content/20">
            <div class="flex flex-wrap gap-4 justify-between items-center pb-6 border-b border-base-content/20">
                <div class="flex gap-3 items-center">
                    <x-button type="button" wire:click="goToPreviousMonth" :icon="config('app.locale') === 'fa' ? 'o-chevron-right' : 'o-chevron-left'" spinner
                        wire:loading.attr="disabled" wire:target="goToPreviousMonth" />
                    <x-button type="button" wire:click="goToCurrentMonth" icon="o-calendar" label="Today" spinner
                        wire:loading.attr="disabled" wire:target="goToCurrentMonth" />
                    <x-button type="button" wire:click="goToNextMonth" :icon="config('app.locale') === 'fa' ?'o-chevron-left': 'o-chevron-right'" spinner
                        wire:loading.attr="disabled" wire:target="goToNextMonth" />
                </div>

                <div class="flex flex-wrap gap-2 items-center text-sm font-medium text-base-content/70">
                    @foreach ($viewModeOptions as $mode => $label)
                        <x-button type="button" wire:click="setViewMode('{{ $mode }}')" :label="$label"
                            spinner wire:loading.attr="disabled" wire:target="setViewMode('{{ $mode }}')"
                            class="{{ $viewMode === $mode ? 'btn-primary' : 'btn-ghost' }}" />
                    @endforeach
                </div>
            </div>

            <div class="mt-6 space-y-6">
                @if ($viewMode === 'month')
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full">
                            <div class="grid grid-cols-7 gap-2">
                                @foreach ($monthGrid->first() as $day)
                                    @include($dayOfWeekView, [
                                        'day' => $day,
                                        'calendarType' => $calendarType,
                                    ])
                                @endforeach
                            </div>

                            <div class="mt-2 space-y-2">
                                @foreach ($monthGrid as $weekIndex => $week)
                                    <div class="grid grid-cols-7 gap-2" wire:key="week-{{ $weekIndex }}">
                                        @foreach ($week as $day)
                                            @include($dayView, [
                                                'componentId' => $componentId,
                                                'day' => $day,
                                                'dayInMonth' => $day->isSameMonth($startsAt),
                                                'isToday' => $day->isToday(),
                                                'events' => $getEventsForDay($day, $events),
                                                'calendarType' => $calendarType,
                                                'visibleEventsPerDay' => $visibleEventsPerDay ?? 3,
                                                'compactEvents' => true,
                                            ])
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if ($viewMode === 'week')
                    <div class="space-y-2">
                        <div class="grid grid-cols-7 gap-2">
                            @foreach ($weekDays as $day)
                                @include($dayOfWeekView, ['day' => $day, 'calendarType' => $calendarType])
                            @endforeach
                        </div>
                        <div class="grid grid-cols-7 gap-2">
                            @foreach ($weekDays as $day)
                                @include($dayView, [
                                    'componentId' => $componentId,
                                    'day' => $day,
                                    'dayInMonth' => true,
                                    'isToday' => $day->isToday(),
                                    'events' => $getEventsForDay($day, $events),
                                    'calendarType' => $calendarType,
                                    'visibleEventsPerDay' => 6,
                                    'compactEvents' => false,
                                ])
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($viewMode === 'day')
                    @php
                        $dayLabel =
                            $calendarType === 'jalali'
                                ? \Morilog\Jalali\Jalalian::fromCarbon($currentCursor)->format('%A %d %B %Y')
                                : $currentCursor->translatedFormat('l, F j, Y');
                    @endphp
                    <div class="p-4 border border-base-content/20 bg-base-200">
                        <div
                            class="flex flex-wrap gap-3 justify-between items-center pb-3 border-b border-base-content/20">
                            <h3 class="text-lg font-semibold text-base-content">{{ $dayLabel }}</h3>
                            <span class="text-xs font-semibold tracking-wide uppercase text-base-content/70">
                                {{ $dayEvents->count() }}
                                {{ \Illuminate\Support\Str::plural('event', $dayEvents->count()) }}
                            </span>
                        </div>
                        <div class="mt-3 space-y-2">
                            @forelse ($dayEvents as $event)
                                @include($eventView, ['event' => $event, 'compact' => false])
                            @empty
                                <p class="text-sm text-base-content/70">{{ __('calendar.empty.no_events') }}</p>
                            @endforelse
                        </div>
                    </div>
                @endif

                @if ($viewMode === 'list')
                    <div class="space-y-4">
                        @forelse ($listEventGroups as $date => $group)
                            @php
                                $dateCarbon = \Carbon\Carbon::parse($date);
                                $dateLabel =
                                    $calendarType === 'jalali'
                                        ? \Morilog\Jalali\Jalalian::fromCarbon($dateCarbon)->format('%A %d %B %Y')
                                        : $dateCarbon->translatedFormat('l, F j, Y');
                            @endphp
                            <div class="p-4 border bg-base-100 border-base-content/20">
                                <p class="text-sm font-semibold text-base-content">{{ $dateLabel }}</p>
                                <div class="mt-3 space-y-2">
                                    @foreach ($group as $event)
                                        @include($eventView, ['event' => $event, 'compact' => false])
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-base-content/70">{{ __('calendar.empty.no_events') }}</p>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>

        <div>
            @includeIf($afterCalendarView)
        </div>
    </div>
</div>
