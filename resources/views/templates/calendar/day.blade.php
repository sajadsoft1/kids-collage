@php
    $jalali = $calendarType === 'jalali' ? \Morilog\Jalali\Jalalian::fromCarbon($day) : null;
    $displayNumber = $jalali ? trim($jalali->format('%e')) : $day->format('j');
    $dayLabel = $jalali ? $jalali->format('%a') : $day->format('D');
    $visibleCount = $visibleEventsPerDay ?? 3;
    $visibleEvents = $events->take($visibleCount);
    $remainingCount = max($events->count() - $visibleEvents->count(), 0);
    $compactEvents = $compactEvents ?? false;
@endphp
<div ondragenter="onLivewireCalendarEventDragEnter(event, '{{ $componentId }}', '{{ $day }}', '{{ $dragAndDropClasses }}');"
    ondragleave="onLivewireCalendarEventDragLeave(event, '{{ $componentId }}', '{{ $day }}', '{{ $dragAndDropClasses }}');"
    ondragover="onLivewireCalendarEventDragOver(event);"
    ondrop="onLivewireCalendarEventDrop(event, '{{ $componentId }}', '{{ $day }}', {{ $day->year }}, {{ $day->month }}, {{ $day->day }}, '{{ $dragAndDropClasses }}');"
    class="min-h-[11rem]">
    <div class="w-full h-full" id="{{ $componentId }}-{{ $day }}">
        <div @if ($dayClickEnabled) wire:click="onDayClick({{ $day->year }}, {{ $day->month }}, {{ $day->day }})" @endif
            class="flex h-full flex-col gap-3 border border-base-content p-3 transition {{ $dayInMonth ? 'bg-base-content/10' : 'bg-base-300 text-base-content/70' }} {{ $isToday ? 'ring-2 ring-primary' : 'hover:border-base-content/20' }}">
            <div class="flex justify-between items-center">
                <div class="flex gap-2 items-baseline">
                    <span class="text-xl font-semibold text-base-content">{{ $displayNumber }}</span>
                    <span class="text-xs tracking-wide uppercase text-base-content/70">{{ $dayLabel }}</span>
                </div>
                @if ($events->isNotEmpty())
                    <span class="px-2 py-0.5 text-[11px] font-semibold text-base-content/70">
                        {{ $events->count() }} {{ \Illuminate\Support\Str::plural('event', $events->count()) }}
                    </span>
                @endif
            </div>

            <div class="flex-1 space-y-2">
                @foreach ($visibleEvents as $event)
                    <div @if ($dragAndDropEnabled) draggable="true" @endif
                        ondragstart="onLivewireCalendarEventDragStart(event, '{{ $event['id'] }}')">
                        @include($eventView, [
                            'event' => $event,
                            'compact' => $compactEvents,
                        ])
                    </div>
                @endforeach

                @if ($remainingCount > 0)
                    <button type="button"
                        class="px-3 py-2 w-full text-xs font-semibold border border-dashed border-base-content/20 text-base-content/70">
                        {{ $remainingCount }} more
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
