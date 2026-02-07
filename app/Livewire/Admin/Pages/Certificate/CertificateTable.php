<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Certificate;

use App\Helpers\PowerGridHelper;
use App\Models\Certificate;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

/**
 * CertificateTable Component
 *
 * Displays a data table of issued certificates with verify and download actions.
 */
final class CertificateTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName = 'index_certificate_datatable';

    public string $sortDirection = 'desc';

    public function beforePowerGridSetUp(): void
    {
        $this->persistItems = ['columns', 'sort'];
    }

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'student_name', 'actions'];
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('_menu.issued_certificates')],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [];
    }

    public function datasource(): Builder
    {
        return Certificate::query()
            ->with(['enrollment.user', 'enrollment.course.template']);
    }

    public function relationSearch(): array
    {
        return [
            'enrollment.user' => ['name'],
            'enrollment.course' => [],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('student_name', fn (Certificate $row) => $row->student_name)
            ->add('course_title', fn (Certificate $row) => $row->course_title)
            ->add('issue_date_formatted', fn (Certificate $row) => $row->formatted_issue_date)
            ->add('grade')
            ->add('certificate_number', fn (Certificate $row) => $row->certificate_number)
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('certificateTemplate.placeholders.student_name'), 'student_name', 'student_name')
                ->sortable()
                ->searchable(),
            Column::make(trans('certificateTemplate.placeholders.course_title'), 'course_title', 'course_title')
                ->sortable()
                ->searchable(),
            Column::make(trans('certificateTemplate.placeholders.issue_date'), 'issue_date_formatted', 'issue_date')
                ->sortable(),
            Column::make(trans('certificateTemplate.placeholders.grade'), 'grade')->sortable(),
            Column::make(trans('certificateTemplate.placeholders.certificate_number'), 'certificate_number')
                ->sortable(),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('grade', 'grade')
                ->dataSource([
                    ['id' => 'A', 'name' => 'A'],
                    ['id' => 'B', 'name' => 'B'],
                    ['id' => 'C', 'name' => 'C'],
                    ['id' => 'D', 'name' => 'D'],
                    ['id' => 'F', 'name' => 'F'],
                ])
                ->optionLabel('name')
                ->optionValue('id'),
            PowerGridHelper::filterDatepickerJalali('issue_date_formatted', 'issue_date', [
                'maxDate' => now()->format('Y-m-d'),
            ]),
            PowerGridHelper::filterDatepickerJalali('created_at_formatted', 'created_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),
        ];
    }

    public function actions(Certificate $row): array
    {
        return [
            Button::add('verify')
                ->slot('<i class="fa fa-check-circle text-info"></i>')
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs'])
                ->route('certificates.verify', ['id' => $row->id, 'hash' => $row->signature_hash], '_blank')
                ->tooltip(trans('certificateTemplate.verification')),
            Button::add('download')
                ->slot('<i class="fa fa-download text-success"></i>')
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs'])
                ->route('certificates.download', ['id' => $row->id, 'hash' => $row->signature_hash], '_blank')
                ->tooltip(trans('general.download') ?: 'Download'),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.certificate.index'),
        ]);
    }
}
