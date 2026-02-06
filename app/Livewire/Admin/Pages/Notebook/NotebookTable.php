<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Notebook;

use App\Enums\BooleanEnum;
use App\Enums\UserTypeEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Notebook;
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

final class NotebookTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName = 'index_notebook_datatable';
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
            ['label' => trans('general.page.index.title', ['model' => trans('notebook.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.notebook.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('notebook.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Notebook::query()->when(
            auth()->user()->type === UserTypeEnum::PARENT,
            function ($q) {
                $children = auth()->user()->children->pluck('id')->toArray();
                $q->whereIn('user_id', [...$children, auth()->id()]);
            }
        )
            ->when(
                auth()->user()->type === UserTypeEnum::EMPLOYEE,
                function ($q) {
                    $q->where('user_id', auth()->id());
                }
            )
            ->when(
                auth()->user()->type === UserTypeEnum::USER,
                function ($q) {
                    $q->where('user_id', auth()->id());
                }
            );
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
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('datatable.title'), 'title'),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('published_formated', 'published')
                ->datasource(BooleanEnum::cases()),

            PowerGridHelper::filterDatepickerJalali('created_at_formatted', 'created_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),
        ];
    }

    public function actions(Notebook $row): array
    {
        return [
            PowerGridHelper::btnToggle($row),
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.notebook.create'),
        ]);
    }
}
