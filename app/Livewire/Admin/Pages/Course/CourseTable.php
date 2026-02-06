<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Enums\CourseTypeEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseTemplate;
use App\Models\User;
use App\Services\Permissions\PermissionsService;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class CourseTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public CourseTemplate $courseTemplate;

    public string $tableName = 'index_course_datatable';
    public string $sortDirection = 'desc';

    /** Livewire events for course lifecycle buttons */
    protected $listeners = ['course-publish' => 'publishCourse', 'course-finish' => 'finishCourse'];

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'title', 'teacher', 'actions'];
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['link' => route('admin.course-template.index'), 'label' => trans('general.page.index.title', ['model' => trans('coursetemplate.model')])],
            ['label' => trans('general.page.index.title', ['model' => trans('course.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            [
                'link' => route('admin.course.run', ['courseTemplate' => $this->courseTemplate->id]),
                'icon' => 's-plus',
                'label' => trans(
                    'general.page.create.title',
                    ['model' => trans('course.model')]
                ),
                'access' => Auth::user()?->hasAnyPermission(PermissionsService::generatePermissionsByModel(Course::class, 'Store')) ?? false,
            ],
        ];
    }

    public function datasource(): Builder
    {
        return Course::where('course_template_id', $this->courseTemplate->id)
            ->with(['teacher', 'template.category', 'term']);
    }

    public function relationSearch(): array
    {
        return [
            'translations' => [
                'value',
            ],
            'category.translations' => [
                'value',
            ],
            'teacher' => [
                'name',
                'email',
            ],
            'user' => [
                'name',
                'email',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('title', fn ($row) => PowerGridHelper::fieldTitle($row->template))
            ->add('teacher_formatted', fn ($row) => $row->teacher?->full_name ?? '---')
            ->add('category_formatted', fn ($row) => $row->template->category?->title ?? '---')
            ->add('price_formatted', fn ($row) => number_format($row->price) . systemCurrency())
            ->add('type_formatted', fn ($row) => $row->template->type->title())
            ->add('status_formatted', fn ($row) => $row->status->title())
            ->add('start_date_formatted', fn ($row) => $row->sessions()->min('date') ?? '---')
            ->add('end_date_formatted', fn ($row) => $row->sessions()->max('date') ?? '---')
            ->add('view_count_formated', fn ($row) => "<strong style='color: " . ($row->template->view_count === 0 ? 'blue' : 'red') . "'>" . $row->template->view_count . '</strong>')
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
            Column::make(trans('datatable.status'), 'status_formatted'),
            Column::make(trans('datatable.start_date'), 'start_date_formatted'),
            Column::make(trans('datatable.end_date'), 'end_date_formatted'),
            PowerGridHelper::columnViewCount('view_count_formated')->hidden(true, false),
            PowerGridHelper::columnUpdatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            PowerGridHelper::filterDatepickerJalali('created_at_formatted', 'created_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),

            Filter::select('teacher_formatted', 'teacher_id')
                ->dataSource(User::whereHas('roles', function (Builder $query) {
                    $query->where('name', 'teacher');
                })->get()->map(function ($user) {
                    return [
                        'value' => $user->id,
                        'label' => $user->full_name,
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
            Button::add('finish')
                ->slot('<i class="fa fa-stop"></i>')
                ->class('btn btn-square md:btn-sm btn-xs')
                ->dispatch('course-finish', ['id' => $row->id]),

            Button::add('sessions')
                ->slot("<i class='fa fa-list-tree'></i>")
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs text-info'])
                ->route('admin.course-session.index', ['courseTemplate' => $this->courseTemplate->id, 'course' => $row->id])
                ->tooltip(trans('course.page.session_list')),

            Button::add('edit')
                ->slot("<i class='fa fa-pencil'></i>")
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs text-info'])
                ->route('admin.course.edit', ['courseTemplate' => $this->courseTemplate->id, 'course' => $row->id], '_self')
                ->navigate()
                ->tooltip(trans('datatable.buttons.edit')),

            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.course.run', ['courseTemplate' => $this->courseTemplate->id]),
        ]);
    }

    public function publishCourse(int $id): void
    {
        $course = Course::findOrFail($id);
        $course->publish();
        $this->notification()->success(trans('general.success'), trans('course.published'));
    }

    public function finishCourse(int $id): void
    {
        $course = Course::findOrFail($id);
        $course->finish();
        $this->notification()->success(trans('general.success'), trans('course.finished'));
    }
}
