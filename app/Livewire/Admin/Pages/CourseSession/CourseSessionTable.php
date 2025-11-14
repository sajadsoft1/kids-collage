<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\CourseSession;

use App\Helpers\PowerGridHelper;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\CourseTemplate;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class CourseSessionTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public CourseTemplate $courseTemplate;
    public Course $course;

    public string $tableName = 'index_courseSession_datatable';
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
            ['label' => trans('general.page.index.title', ['model' => trans('courseSession.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            //            ['link' => route('admin.courseSession.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('courseSession.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return CourseSession::query();
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
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
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
        ];
    }

    public function actions(CourseSession $row): array
    {
        return [
            //            PowerGridHelper::btnToggle($row),
            Button::add('edit')
                ->slot("<i class='fa fa-pencil'></i>")
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs text-info'])
                ->route('admin.course-session.edit', ['courseTemplate' => $row->course->course_template_id, 'course' => $row->course_id, 'courseSession' => $row->id], '_self')
                ->navigate()
                ->tooltip(trans('datatable.buttons.edit')),

            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => '#',
        ]);
    }
}
