<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Room;

use App\Helpers\PowerGridHelper;
use App\Models\Room;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use Str;

final class RoomTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName = 'index_room_datatable';
    public string $sortDirection = 'desc';

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'name', 'actions'];
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('room.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.room.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('room.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Room::query();
    }

    public function relationSearch(): array
    {
        return [
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name', fn ($row) => $row->name)
            ->add('location', fn ($row) => Str::limit($row->location, 50))
            ->add('capacity', fn ($row) => $row->capacity)
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('datatable.name'), 'name')->searchable(),
            Column::make(trans('datatable.location'), 'location'),
            Column::make(trans('datatable.capacity'), 'capacity')->sortable(),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            PowerGridHelper::filterDatepickerJalali('created_at_formatted', 'created_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),
        ];
    }

    public function actions(Room $row): array
    {
        return [
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.room.create'),
        ]);
    }
}
