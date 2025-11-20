<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Payment;

use App\Enums\UserTypeEnum;
use App\Helpers\PowerGridHelper;
use App\Helpers\StringHelper;
use App\Models\Payment;
use App\Services\Permissions\PermissionsService;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PaymentTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName = 'index_payment_datatable';
    public string $sortDirection = 'desc';

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'title', 'actions'];
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
            [
                'link' => route('admin.payment.create'),
                'icon' => 's-plus',
                'label' => trans('general.page.create.title', ['model' => trans('payment.model')]),
                'access' => auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(Payment::class, 'Store')),
            ],
        ];
    }

    public function datasource(): Builder
    {
        return Payment::query()
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
            Column::make(trans('validation.attributes.username'), 'user_formated', 'user_id')
                ->hidden(in_array(auth()->user()->type, [UserTypeEnum::USER, UserTypeEnum::PARENT])),
            Column::make(trans('validation.attributes.order_id'), 'order_number', 'order_id'),
            Column::make(trans('validation.attributes.type'), 'type_formated', 'type')->sortable(),
            Column::make(trans('validation.attributes.status'), 'status_formated', 'status')->sortable(),
            Column::make(trans('validation.attributes.amount'), 'amount_formated', 'amount')->sortable(),
            Column::make(trans('validation.attributes.scheduled_date'), 'scheduled_date_formatted', 'scheduled_date')->sortable(),
            PowerGridHelper::columnAction()
                ->hidden(in_array(auth()->user()->type, [UserTypeEnum::USER, UserTypeEnum::PARENT])),
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
