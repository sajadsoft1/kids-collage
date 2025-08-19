{{-- Injected variables $status, $styles --}}
<x-card class="{{ $styles['statusWrapper'] }}" :title="$status['title']" id="{{ $status['id'] }}">
    <div class="{{ $styles['status'] }}">

        <div id="{{ $status['statusRecordsId'] }}" class="overflow-y-auto max-h-[calc(100vh-10rem)]"
            data-status-id="{{ $status['id'] }}" class="{{ $styles['statusRecords'] }}">

            @foreach ($status['records'] as $record)
                @include($recordView, [
                    'record' => $record,
                ])
            @endforeach

        </div>
    </div>
</x-card>
