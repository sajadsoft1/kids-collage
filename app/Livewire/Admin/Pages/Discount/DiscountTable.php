<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Discount;

use App\Enums\BooleanEnum;
use App\Enums\DiscountTypeEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Discount;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class DiscountTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName     = 'index_discount_datatable';
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
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'code', 'actions');
        }

        return $setup;
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('discount.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.discount.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('discount.model')])],
        ];
    }

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'page'   => ['except' => 1],
            ...$this->powerGridQueryString(),
        ];
    }

    public function datasource(): Builder
    {
        return Discount::query()->with('user');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('code')
            ->add('type', fn ($row) => $row->type->title())
            ->add('value_formatted', fn ($row) => $row->getFormattedValue())
            ->add('user_name', fn ($row) => $row->user?->full_name ?? 'All Users')
            ->add('status', fn ($row) => $row->getStatusText())
            ->add('used_count', fn ($row) => $row->used_count . ($row->usage_limit ? ' / ' . $row->usage_limit : ''))
            ->add('is_active_formatted', function ($row) {
                return $row->is_active
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-error">Inactive</span>';
            })
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),

            \PowerComponents\LivewirePowerGrid\Column::make('Code', 'code')
                ->sortable()
                ->searchable(),

            \PowerComponents\LivewirePowerGrid\Column::make('Type', 'type')
                ->sortable(),

            \PowerComponents\LivewirePowerGrid\Column::make('Value', 'value_formatted'),

            \PowerComponents\LivewirePowerGrid\Column::make('User', 'user_name')
                ->sortable(),

            \PowerComponents\LivewirePowerGrid\Column::make('Status', 'status'),

            \PowerComponents\LivewirePowerGrid\Column::make('Usage', 'used_count')
                ->sortable(),

            \PowerComponents\LivewirePowerGrid\Column::make('Active', 'is_active_formatted', 'is_active')
                ->sortable(),

            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('type', 'type')
                ->datasource(DiscountTypeEnum::cases()),

            Filter::enumSelect('is_active_formatted', 'is_active')
                ->datasource(BooleanEnum::cases()),

            Filter::datepicker('created_at_formatted', 'created_at')
                ->params([
                    'maxDate' => now(),
                ]),
        ];
    }

    public function actions(Discount $row): array
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
            'link' => route('admin.discount.create'),
        ]);
    }
}
