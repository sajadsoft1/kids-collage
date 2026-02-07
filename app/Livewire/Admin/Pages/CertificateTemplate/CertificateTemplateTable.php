<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\CertificateTemplate;

use App\Helpers\PowerGridHelper;
use App\Models\CertificateTemplate;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

/**
 * CertificateTemplateTable Component
 *
 * Displays a data table of certificate templates with filtering,
 * sorting, and CRUD actions (edit, delete).
 */
final class CertificateTemplateTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName = 'index_certificateTemplate_datatable';

    public string $sortDirection = 'desc';

    public function beforePowerGridSetUp(): void
    {
        $this->persistItems = ['columns', 'sort'];
    }

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'title', 'actions'];
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('certificateTemplate.page.index_title')],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.certificate-template.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('certificateTemplate.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return CertificateTemplate::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('title')
            ->add('slug')
            ->add('is_default', fn (CertificateTemplate $row) => $row->is_default ? trans('general.yes') : trans('general.no'))
            ->add('layout', fn (CertificateTemplate $row) => trans('certificateTemplate.layout.' . $row->layout))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('certificateTemplate.page.title'), 'title', 'title')
                ->sortable()
                ->searchable(),
            Column::make(trans('certificateTemplate.page.slug'), 'slug', 'slug')
                ->sortable()
                ->searchable(),
            Column::make(trans('certificateTemplate.page.is_default'), 'is_default')->sortable(),
            Column::make(trans('certificateTemplate.page.layout'), 'layout', 'layout')->sortable(),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::boolean('is_default', 'is_default'),
            Filter::select('layout', 'layout')
                ->dataSource(collect(CertificateTemplate::layoutOptions())->map(fn ($label, $value) => ['id' => $value, 'name' => $label])->values()->toArray())
                ->optionLabel('name')
                ->optionValue('id'),
            PowerGridHelper::filterDatepickerJalali('created_at_formatted', 'created_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),
        ];
    }

    public function actions(CertificateTemplate $row): array
    {
        return [
            PowerGridHelper::btnEdit($row, 'certificate-template'),
            PowerGridHelper::btnDelete($row, 'certificate-template'),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.certificate-template.index'),
        ]);
    }
}
