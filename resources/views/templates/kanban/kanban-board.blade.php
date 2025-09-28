<div class="flex flex-col flex-1">
    <div>
        @includeIf($beforeStatusBoardView)
    </div>

    <div class="{{ $styles['wrapper'] }}">
        @foreach ($statuses as $status)
            @include($statusView, [
                'status' => $status,
            ])
        @endforeach
    </div>

    <div>
        @includeIf($afterStatusBoardView)
    </div>

    <div wire:ignore>
        @includeWhen($sortable, 'templates.kanban.sortable', [
            'sortable' => $sortable,
            'sortableBetweenStatuses' => $sortableBetweenStatuses,
        ])
    </div>
</div>
