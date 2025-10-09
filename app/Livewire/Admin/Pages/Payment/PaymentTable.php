<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Payment;

use App\Helpers\PowerGridHelper;
use App\Helpers\StringHelper;
use App\Models\Payment;
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

final class PaymentTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName     = 'index_payment_datatable';
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
            ['label' => trans('general.page.index.title', ['model' => trans('payment.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.payment.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('payment.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Payment::query();
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
            ->add('order_number', fn ($row) => $row->order->order_number)
            ->add('type_formated', fn ($row) => $row->type->title())
            ->add('status_formated', fn ($row) => view('admin.datatable-shared.badge', [
                'value' => $row->status->title(),
                'color' => $row->status->color(),
            ]))
            ->add('amount_formated', fn ($row) => StringHelper::toCurrency($row->amount))
            ->add('scheduled_date_formatted', fn ($row) => jdate($row->scheduled_date)->format('%A, %d %B %Y'));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('validation.attributes.username'), 'user_formated', 'user_id'),
            Column::make(trans('validation.attributes.order_id'), 'order_number', 'order_id'),
            Column::make(trans('validation.attributes.type'), 'type_formated', 'type')->sortable(),
            Column::make(trans('validation.attributes.status'), 'status_formated', 'status')->sortable(),
            Column::make(trans('validation.attributes.amount'), 'amount_formated', 'amount')->sortable(),
            Column::make(trans('validation.attributes.scheduled_date'), 'scheduled_date_formatted', 'scheduled_date')->sortable(),
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
        ];
    }

    public function actions(Payment $row): array
    {
        return [
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.payment.create'),
        ]);
    }
}
