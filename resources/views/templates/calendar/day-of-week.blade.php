@php
    $label = $calendarType === 'jalali' ? \Morilog\Jalali\Jalalian::fromCarbon($day)->format('%A') : $day->format('l');
@endphp
<div class="flex flex-col px-3 py-3 text-center border border-base-content/20 bg-base-200 text-base-content/70">
    <p class="font-bold tracking-wide uppercase text-base-content">
        {{ $label }}
    </p>
</div>
