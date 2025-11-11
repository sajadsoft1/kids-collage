<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Enrollment;

use App\Helpers\PowerGridHelper;
use App\Models\Enrollment;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class EnrollmentTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName     = 'index_enrollment_datatable';
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
            ['label' => trans('general.page.index.title', ['model' => trans('enrollment.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.order.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('enrollment.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Enrollment::query();
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
            ->add('course_formated', fn ($row) => view('admin.datatable-shared.course-info', [
                'row' => $row->course,
            ]))
            ->add('status_formated', fn ($row) => view('admin.datatable-shared.badge', [
                'value' => $row->status->title(),
                'color' => $row->status->color(),
            ]))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('validation.attributes.username'), 'user_formated', 'user_id'),
            Column::make(trans('validation.attributes.course_id'), 'course_formated', 'course_id'),
            Column::make(trans('validation.attributes.status'), 'status_formated', 'status')->sortable(),
            PowerGridHelper::columnCreatedAT(),
            // PowerGridHelper::columnAction(),
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

    // public function actions(Enrollment $row): array
    // {
    //     return [
    //         PowerGridHelper::btnEdit($row->order()),
    //         PowerGridHelper::btnDelete($row),
    //     ];
    // }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.order.create'),
        ]);
    }
}
