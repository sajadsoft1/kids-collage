<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\CourseSessionTemplate;

use App\Enums\SessionType;
use App\Helpers\PowerGridHelper;
use App\Models\CourseSessionTemplate;
use App\Models\CourseTemplate;
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

final class CourseSessionTemplateTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public CourseTemplate $courseTemplate;

    public string $tableName     = 'index_courseSessionTemplate_datatable';
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
            ['link'  => route('admin.course-template.index'), 'label' => trans('general.page.index.title', ['model' => trans('coursetemplate.model')])],
            ['label' => trans('general.page.index.title', ['model' => trans('courseSessionTemplate.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.course-session-template.create', ['courseTemplate' => $this->courseTemplate->id]), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('courseSessionTemplate.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return CourseSessionTemplate::where('course_template_id', $this->courseTemplate->id)
            ->orderBy('order');
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
            ->add('type_formated', fn ($row) => view('admin.datatable-shared.badge', [
                'value' => $row->type->title(),
                'color' => $row->type->color(),
            ]));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
            Column::make(trans('validation.attributes.type'), 'type_formated', 'type')->sortable(),
            Column::make(trans('validation.attributes.duration_minutes'), 'duration_minutes')->sortable(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('type_formatted', 'type')
                ->datasource(SessionType::cases()),
        ];
    }

    public function actions(CourseSessionTemplate $row): array
    {
        return [
            PowerGridHelper::btnTranslate($row),
            Button::add('edit')
                ->slot("<i class='fa fa-pencil'></i>")
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs text-info'])
                ->route('admin.course-session-template.edit', ['courseTemplate' => $this->courseTemplate->id, 'courseSessionTemplate' => $row->id], '_self')
                ->navigate()
                ->tooltip(trans('datatable.buttons.edit')),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.course-session-template.create', ['courseTemplate' => $this->courseTemplate->id]),
        ]);
    }
}
