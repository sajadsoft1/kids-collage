<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Term;

use App\Enums\TermStatus;
use App\Helpers\PowerGridHelper;
use App\Models\Term;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class TermTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName = 'index_term_datatable';
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
            ['label' => trans('general.page.index.title', ['model' => trans('term.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.term.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('term.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Term::query();
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
            ->add('status_formatted', fn ($row) => $row->status->title())
            ->add('start_date_formatted', fn ($row) => $row->start_date->format('Y-m-d'))
            ->add('end_date_formatted', fn ($row) => $row->end_date->format('Y-m-d'))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
            Column::make(trans('datatable.status'), 'status_formatted'),
            Column::make(trans('datatable.start_date'), 'start_date_formatted'),
            Column::make(trans('datatable.end_date'), 'end_date_formatted'),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('status_formatted', 'status')
                ->dataSource(TermStatus::cases()),

            Filter::datepicker('start_date_formatted', 'start_date')
                ->params([
                    'maxDate' => now(),
                ]),
            Filter::datepicker('end_date_formatted', 'end_date')
                ->params([
                    'maxDate' => now(),
                ]),
        ];
    }

    public function actions(Term $row): array
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
            'link' => route('admin.term.create'),
        ]);
    }
}
