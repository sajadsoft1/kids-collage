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
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class DiscountTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName = 'index_discount_datatable';
    public string $sortDirection = 'desc';

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'code', 'actions'];
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
            ->add('type_formated', fn ($row) => $row->type->title())
            ->add('value_formatted', fn ($row) => $row->getFormattedValue())
            ->add('user_name', fn ($row) => $row->user?->full_name ?? trans('discount.page.no_restrictions'))
            ->add('status', fn ($row) => view('admin.datatable-shared.badge', [
                'color' => $row->getStatusText()['color'],
                'value' => $row->getStatusText()['label'],
            ]))
            ->add('used_count', fn ($row) => $row->used_count . ($row->usage_limit ? ' / ' . $row->usage_limit : ''))
            ->add('is_active_formatted', fn ($row) => view('admin.datatable-shared.badge', [
                'color' => $row->is_active->color(),
                'value' => $row->is_active->title(),
            ]));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),

            Column::make(trans('datatable.code'), 'code')
                ->sortable()
                ->searchable(),

            Column::make(trans('discount.page.fields.type'), 'type_formated', 'type')
                ->sortable(),

            Column::make(trans('discount.page.fields.value'), 'value_formatted'),

            Column::make(trans('user.user'), 'user_name')
                ->sortable(),

            Column::make(trans('validation.attributes.status'), 'status'),

            Column::make(trans('discount.page.fields.usage_limit'), 'used_count')
                ->sortable(),

            Column::make(trans('course.enum.status.active'), 'is_active_formatted', 'is_active')
                ->sortable(),

            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            //            Filter::enumSelect('type', 'type')
            //                ->datasource(DiscountTypeEnum::cases()),
            //
            //            Filter::enumSelect('is_active_formatted', 'is_active')
            //                ->datasource(BooleanEnum::cases()),

            Filter::datepicker('created_at_formatted', 'created_at')
                ->params([
                    'maxDate' => now(),
                ]),
        ];
    }

    public function actions(Discount $row): array
    {
        return [
            PowerGridHelper::btnToggle($row, 'is_active'),
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
