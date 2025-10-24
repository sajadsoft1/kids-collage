<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\CourseTemplate;

use App\Enums\CategoryTypeEnum;
use App\Helpers\Constants;
use App\Helpers\PowerGridHelper;
use App\Models\Category;
use App\Models\CourseTemplate;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class CourseTemplateTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName     = 'index_courseTemplate_datatable';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        $setup = [
            PowerGrid::header()
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

    public function datasource(): Builder
    {
        return CourseTemplate::withCount('sessionTemplates');
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
            ->add('image', fn ($row) => PowerGridHelper::fieldImage($row, 'image', Constants::RESOLUTION_854_480, 11, 6))
            ->add('title', fn ($row) => PowerGridHelper::fieldTitle($row))
            ->add('formatted_type', fn ($row) => $row->type->title())
            ->add('category_formatted', fn ($row) => $row->category?->title ?? '---')
            ->add('view_count_formated', fn ($row) => "<strong style='color: " . ($row->view_count === 0 ? 'blue' : 'red') . "'>{$row->view_count}</strong>")
            ->add('updated_at_formatted', fn ($row) => PowerGridHelper::fieldUpdatedAtFormated($row))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnImage(),
            PowerGridHelper::columnTitle(),
            Column::make(trans('datatable.type'), 'formatted_type'),
            Column::make(trans('datatable.category_title'), 'category_formatted'),
            Column::make(trans('validation.attributes.session_count'), 'session_templates_count')
                ->sortable(),
            PowerGridHelper::columnViewCount('view_count_formated')->hidden(true, false),
            PowerGridHelper::columnUpdatedAT(),
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

            Filter::select('category_formatted', 'category_id')
                ->dataSource(Category::where('type', CategoryTypeEnum::COURSE->value)->get()->map(function ($category) {
                    return [
                        'value' => $category->id,
                        'label' => $category->title,
                    ];
                })->toArray())->optionLabel('label')->optionValue('value'),
        ];
    }

    public function actions(CourseTemplate $row): array
    {
        return [
            PowerGridHelper::btnSeo($row),
            PowerGridHelper::btnTranslate($row),
            Button::add('run')
                ->slot("<i class='fa fa-play'></i>")
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs text-success'])
                ->route('admin.course.run', ['courseTemplate' => $row->id], '_self')
                ->navigate()
                ->tooltip(trans('coursetemplate.page.run_the_course_template')),

            Button::add('courses_list')
                ->slot("<i class='fa fa-list'></i>")
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs text-info'])
                ->route('admin.course.index', ['courseTemplate' => $row->id], '_self')
                ->navigate()
                ->tooltip(trans('courseTemplate.page.course_list')),

            Button::add('courses_session_list')
                ->slot("<i class='fa fa-list-tree'></i>")
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs text-info'])
                ->route('admin.course-session-template.index', ['courseTemplate' => $row->id], '_self')
                ->navigate()
                ->tooltip(trans('courseTemplate.page.course_session_template_list')),

            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.course-template.create'),
        ]);
    }
}
