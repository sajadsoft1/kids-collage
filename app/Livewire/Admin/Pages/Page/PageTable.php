<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Page;

use App\Enums\PageTypeEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Page;
use App\Services\Permissions\PermissionsService;
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

final class PageTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName     = 'index_page_datatable';
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

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'page'   => ['except' => 1],
            ...$this->powerGridQueryString(),
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('page.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            [
                'link'   => route('admin.page.create'),
                'icon'   => 's-plus',
                'label'  => trans(
                    'general.page.create.title',
                    ['model' => trans('page.model')]
                ),
                'access' => auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(Page::class, 'Store')),
            ],
        ];
    }

    public function datasource(): Builder
    {
        return Page::query();
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
            ->add('type_formatted', fn ($row) => $row->type->title())
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle()->searchable(),
            Column::make(trans('datatable.type'), 'type_formatted', 'type'),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('created_at_formatted', 'created_at')
                ->params([
                    'maxDate' => now(),
                ]),

            Filter::enumSelect('type_formatted', 'type')
                ->dataSource(PageTypeEnum::cases()),
        ];
    }

    public function actions(Page $row): array
    {
        return [
            PowerGridHelper::btnTranslate($row),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.page.create'),
        ]);
    }
}
