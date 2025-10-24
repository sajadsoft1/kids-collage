<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Resource;

use App\Enums\ResourceType;
use App\Helpers\PowerGridHelper;
use App\Models\Resource;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class ResourceTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName     = 'index_resource_datatable';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        $setup = [
            PowerGrid::header()
                ->includeViewOnTop('components.admin.shared.bread-crumbs')
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];

        if ((new Agent)->isMobile()) {
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'title', 'actions');
        }

        return $setup;
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('resource.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.resource.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('resource.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Resource::query();
    }

    public function relationSearch(): array
    {
        return [
            'translations' => [
                'value',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('title', fn ($row) => PowerGridHelper::fieldTitle($row))
            ->add('type_formated', fn ($row) => $row->type->title())
            ->add('attached_to', fn ($row) => $row->courseSessionTemplates->count() . ' session(s)')
            ->add('is_public', fn ($row) => $row->is_public ? 'Public' : 'Private')
            ->add('order', fn ($row) => $row->order)
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
            Column::make('Type', 'type_formated', 'type')
                ->sortable()
                ->searchable(),
            Column::make('Attached To', 'attached_to'),
            Column::make('Visibility', 'is_public')
                ->sortable(),
            Column::make('Order', 'order')
                ->sortable(),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::select('type', 'type')
            //     ->dataSource(ResourceType::options())
            //     ->optionValue('value')
            //     ->optionLabel('label'),

            Filter::select('is_public', 'is_public')
                ->dataSource([
                    ['value' => '1', 'label' => 'Public'],
                    ['value' => '0', 'label' => 'Private'],
                ])
                ->optionValue('value')
                ->optionLabel('label'),
            Filter::datepicker('created_at_formatted', 'created_at')
                ->params([
                    'maxDate' => now(),
                ]),
        ];
    }

    public function actions(Resource $row): array
    {
        return [
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.resource.create'),
        ]);
    }
}
