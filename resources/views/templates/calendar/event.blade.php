@php
    $start =
        $event['date'] instanceof \Carbon\CarbonInterface
            ? $event['date']->clone()
            : \Carbon\Carbon::parse($event['date']);
    $endAt = $event['end'] ?? null;
    $end = $endAt instanceof \Carbon\CarbonInterface ? $endAt : ($endAt ? \Carbon\Carbon::parse($endAt) : null);
    $categoryStyles = $event['category']['event_classes'] ?? 'bg-slate-50 border border-slate-200 text-slate-900';
    $dot = $event['category']['dot_classes'] ?? 'bg-slate-400';
    $compact = $compact ?? false;
@endphp
@if ($compact)
    <div
        class="flex items-center gap-2 border border-slate-200 bg-slate-50 px-2 py-1 text-xs font-semibold text-slate-700">
        <span class="h-2 w-2 bg-slate-400 {{ $dot }}"></span>
        <span class="truncate">{{ $event['title'] }}</span>
    </div>
@else
    <div @if ($eventClickEnabled) wire:click.stop="onEventClick('{{ $event['id'] }}')" @endif
        class="border px-3 py-2 text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow {{ $categoryStyles }} {{ $eventClickEnabled ? 'cursor-pointer' : '' }}">
        <div class="flex items-center justify-between text-[11px] font-semibold uppercase tracking-wide text-slate-500">
            <span>
                {{ $start->format('g:i A') }}
                @if ($end)
                    – {{ $end->format('g:i A') }}
                @endif
            </span>
            <span class="inline-flex items-center gap-1">
                <span class="h-1.5 w-1.5 rounded-full {{ $dot }}"></span>
                {{ $event['category']['label'] ?? 'Event' }}
            </span>
        </div>
        <p class="text-sm font-semibold text-slate-900">
            {{ $event['title'] }}
        </p>
        <p class="text-xs text-slate-500">
            {{ $event['description'] ?? 'No description' }}
        </p>
    </div>
@endif
