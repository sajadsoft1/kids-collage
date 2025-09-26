<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Enums\CourseTypeEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use App\Services\Permissions\PermissionsService;
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

final class CourseTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName     = 'index_course_datatable';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        $setup = [
            PowerGrid::header()
                ->includeViewOnTop('components.admin.shared.bread-crumbs')
                ->showToggleColumns()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];

        if ((new Agent)->isMobile()) {
            $setup[] = PowerGrid::responsive()
                ->fixedColumns('id', 'title', 'teacher', 'actions');
        }

        return $setup;
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('course.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            [
                'link'   => route('admin.course.create'),
                'icon'   => 's-plus',
                'label'  => trans(
                    'general.page.create.title',
                    ['model' => trans('course.model')]
                ),
                'access' => auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(Course::class, 'Store')),
            ],
        ];
    }

    public function datasource(): Builder
    {
        return Course::query()
            ->with(['teacher', 'category']);
    }

    public function relationSearch(): array
    {
        return [
            'translations'          => [
                'value',
            ],
            'category.translations' => [
                'value',
            ],
            'teacher'               => [
                'name',
                'email',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('title', fn ($row) => PowerGridHelper::fieldTitle($row))
            ->add('teacher_formatted', fn ($row) => $row->teacher?->name ?? '---')
            ->add('category_formatted', fn ($row) => $row->category?->title ?? '---')
            ->add('price_formatted', fn ($row) => number_format($row->price) . ' تومان')
            ->add('type_formatted', fn ($row) => $row->type->value)
            ->add('start_date_formatted', fn ($row) => $row->start_date?->format('Y-m-d') ?? '---')
            ->add('end_date_formatted', fn ($row) => $row->end_date?->format('Y-m-d') ?? '---')
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row))
            ->add('updated_at_formatted', fn ($row) => PowerGridHelper::fieldUpdatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
            Column::make(trans('datatable.teacher'), 'teacher_formatted'),
            Column::make(trans('datatable.category'), 'category_formatted'),
            Column::make(trans('datatable.price'), 'price_formatted'),
            Column::make(trans('datatable.type'), 'type_formatted'),
            Column::make(trans('datatable.start_date'), 'start_date_formatted'),
            Column::make(trans('datatable.end_date'), 'end_date_formatted'),
            PowerGridHelper::columnCreatedAT(),
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

            Filter::select('teacher_formatted', 'teacher_id')
                ->dataSource(User::whereHas('roles', function (Builder $query) {
                    $query->where('name', 'teacher');
                })->get()->map(function ($user) {
                    return [
                        'value' => $user->id,
                        'label' => $user->name,
                    ];
                })->toArray())->optionLabel('label')->optionValue('value'),

            Filter::select('category_formatted', 'category_id')
                ->dataSource(Category::where('type', 'course')->get()->map(function ($category) {
                    return [
                        'value' => $category->id,
                        'label' => $category->title,
                    ];
                })->toArray())->optionLabel('label')->optionValue('value'),

            Filter::enumSelect('type_formatted', 'type')
                ->datasource(CourseTypeEnum::cases()),
        ];
    }

    public function actions(Course $row): array
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
            'link' => route('admin.course.create'),
        ]);
    }
}
