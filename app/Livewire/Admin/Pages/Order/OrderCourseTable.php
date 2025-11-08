<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Order;

use App\Enums\OrderStatusEnum;
use App\Enums\UserTypeEnum;
use App\Helpers\PowerGridHelper;
use App\Helpers\StringHelper;
use App\Models\Order;
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

final class OrderCourseTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName     = 'index_order_course_datatable';
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
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'user_formated', 'actions');
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
            ['label' => trans('general.page.index.title', ['model' => trans('order.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.order.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('order.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Order::query()
            ->when(
                auth()->user()->type === UserTypeEnum::PARENT,
                function ($q) {
                    $children = auth()->user()->children->pluck('id')->toArray();
                    $q->whereIn('user_id', [...$children, auth()->id()]);
                }
            )
            ->when(
                auth()->user()->type === UserTypeEnum::TEACHER,
                function ($q) {
                    $q->where('user_id', auth()->id());
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
            ->add('user_formated', fn ($row) => view('admin.datatable-shared.user-info', [
                'row' => $row->user,
            ]))
            ->add('price_formated', fn ($row) => StringHelper::toCurrency($row->total_amount))
            ->add('status_formated', fn ($row) => $row->status->title())
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('validation.attributes.username'), 'user_formated', 'user_id'),
            Column::make(trans('validation.attributes.price'), 'price_formated', 'total_price')->sortable(),
            Column::make(trans('validation.attributes.status'), 'status_formated', 'status')->sortable(),
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
            Filter::select('status', 'status')
                ->dataSource(OrderStatusEnum::courseOptions())
                ->optionLabel('label')
                ->optionValue('value'),
        ];
    }

    public function actions(Order $row): array
    {
        return [
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.order.create'),
        ]);
    }
}
