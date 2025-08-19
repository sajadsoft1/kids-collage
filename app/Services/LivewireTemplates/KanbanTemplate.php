<?php

declare(strict_types=1);

namespace App\Services\LivewireTemplates;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class KanbanTemplate
 * @property bool   $sortable
 * @property bool   $sortableBetweenStatuses
 * @property string $statusBoardView
 * @property string $statusView
 * @property string $recordView
 * @property string $sortableView
 * @property string $beforeStatusBoardView
 * @property string $afterStatusBoardView
 * @property string $ghostClass
 * @property bool   $recordClickEnabled
 */
class KanbanTemplate extends Component
{
    public $sortable;
    public $sortableBetweenStatuses;

    public $statusBoardView;
    public $statusView;
    public $recordView;
    public $sortableView;
    public $beforeStatusBoardView;
    public $afterStatusBoardView;

    public $ghostClass;

    public $recordClickEnabled;

    public function mount(
        $sortable = false,
        $sortableBetweenStatuses = false,
        $statusBoardView = null,
        $statusView = null,
        $recordView = null,
        $sortableView = null,
        $beforeStatusBoardView = null,
        $afterStatusBoardView = null,
        $ghostClass = null,
        $recordClickEnabled = false,
        $extras = []
    ): void {
        $this->sortable                = $sortable ?? false;
        $this->sortableBetweenStatuses = $sortableBetweenStatuses ?? false;

        $this->statusBoardView       = $statusBoardView ?? 'templates.kanban.kanban-board';
        $this->statusView            = $statusView ?? 'templates.kanban.status';
        $this->recordView            = $recordView ?? 'templates.kanban.record';
        $this->sortableView          = $sortableView ?? 'templates.kanban.sortable';
        $this->beforeStatusBoardView = $beforeStatusBoardView ?? null;
        $this->afterStatusBoardView  = $afterStatusBoardView ?? null;

        $this->ghostClass = $ghostClass ?? 'bg-indigo-100';

        $this->recordClickEnabled = $recordClickEnabled ?? false;

        $this->afterMount($extras);
    }

    public function afterMount($extras = []): void {}

    public function statuses(): Collection
    {
        return collect();
    }

    public function records(): Collection
    {
        return collect();
    }

    public function isRecordInStatus($record, $status): bool
    {
        return $record['status'] == $status['id'];
    }

    public function onStatusSorted($recordId, $statusId, $orderedIds): void {}

    public function onStatusChanged($recordId, $statusId, $fromOrderedIds, $toOrderedIds): void {}

    public function onRecordClick($recordId): void {}

    public function styles(): array
    {
        return [
            'wrapper'       => 'w-full h-full flex space-x-4 overflow-x-auto',
            'statusWrapper' => 'h-full flex-1',
            'status'        => 'bg-blue-200 rounded px-2 flex flex-col h-full',
            'statusHeader'  => 'p-2 text-sm text-gray-700',
            'statusFooter'  => '',
            'statusRecords' => 'space-y-2 p-2 flex-1 overflow-y-auto',
            'record'        => 'shadow bg-white p-2 rounded border',
            'recordContent' => 'w-full',
        ];
    }

    public function render(): View
    {
        $statuses = $this->statuses();

        $records = $this->records();

        $styles = $this->styles();

        $statuses = $statuses
            ->map(function ($status) use ($records) {
                $status['group']           = $this->getId();
                $status['statusRecordsId'] = "{$this->getId()}-{$status['id']}";
                $status['records']         = $records
                    ->filter(function ($record) use ($status) {
                        return $this->isRecordInStatus($record, $status);
                    });

                return $status;
            });

        return view($this->statusBoardView)
            ->with([
                'records'  => $records,
                'statuses' => $statuses,
                'styles'   => $styles,
            ]);
    }
}
