<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Enums\BooleanEnum;
use App\Enums\CourseType;
use App\Helpers\Constants;
use App\Helpers\PowerGridHelper;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use App\Services\Permissions\PermissionsService;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;
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

    public string $tableName     = 'index_course_datatable';
    public string $sortDirection = 'desc';

    /** Livewire events for course lifecycle buttons */
    protected $listeners = ['course-publish' => 'publishCourse', 'course-start' => 'startCourse', 'course-finish' => 'finishCourse'];

    public function setUp(): array
    {
        $this->persist(['columns'], prefix: auth()->id ?? '');
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

    protected function queryString(): array
    {
        return [
            'search' => ['except' => ''],
            'page'   => ['except' => 1],
            ...$this->powerGridQueryString(),
        ];
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
                'access' => Auth::user()?->hasAnyPermission(PermissionsService::generatePermissionsByModel(Course::class, 'Store')) ?? false,
            ],
        ];
    }

    public function datasource(): Builder
    {
        return Course::query()
            ->with(['teacher', 'category', 'user']);
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
            'user'                  => [
                'name',
                'email',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('image', fn ($row) => PowerGridHelper::fieldImage($row, 'image', Constants::RESOLUTION_854_480, 11, 6))
            ->add('title', fn ($row) => PowerGridHelper::fieldTitle($row))
            ->add('teacher_formatted', fn ($row) => $row->teacher?->name ?? '---')
            ->add('category_formatted', fn ($row) => $row->category?->title ?? '---')
            ->add('price_formatted', fn ($row) => number_format($row->price) . ' تومان')
            ->add('type_formatted', fn ($row) => $row->type->value)
            ->add('start_date_formatted', fn ($row) => $row->start_date?->format('Y-m-d') ?? '---')
            ->add('end_date_formatted', fn ($row) => $row->end_date?->format('Y-m-d') ?? '---')
            ->add('published_formated', fn ($row) => PowerGridHelper::fieldPublishedAtFormated($row))
            ->add('view_count_formated', fn ($row) => "<strong style='color: " . ($row->view_count === 0 ? 'blue' : 'red') . "'>" . $row->view_count . '</strong>')
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row))
            ->add('updated_at_formatted', fn ($row) => PowerGridHelper::fieldUpdatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnImage(),
            PowerGridHelper::columnTitle(),
            Column::make(trans('datatable.teacher'), 'teacher_formatted'),
            Column::make(trans('datatable.category'), 'category_formatted'),
            Column::make(trans('datatable.price'), 'price_formatted'),
            Column::make(trans('datatable.type'), 'type_formatted'),
            Column::make(trans('datatable.start_date'), 'start_date_formatted'),
            Column::make(trans('datatable.end_date'), 'end_date_formatted'),
            PowerGridHelper::columnPublished(),
            PowerGridHelper::columnViewCount('view_count_formated')->hidden(true, false),
            PowerGridHelper::columnUpdatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('published_formated', 'published')
                ->datasource(BooleanEnum::cases()),

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
                ->datasource(CourseType::cases()),
        ];
    }

    public function actions(Course $row): array
    {
        return [
            PowerGridHelper::btnSeo($row),
            PowerGridHelper::btnTranslate($row),
            Button::add('publish')
                ->slot('<x-mary-icon name="o-rocket-launch" class="w-4 h-4" />')
                ->class('btn btn-square md:btn-sm btn-xs')
                ->dispatch('course-publish', ['id' => $row->id])
                ->can($row->status->value === \App\Enums\CourseStatus::DRAFT->value),
            Button::add('start')
                ->slot('<x-mary-icon name="o-play" class="w-4 h-4" />')
                ->class('btn btn-square md:btn-sm btn-xs')
                ->dispatch('course-start', ['id' => $row->id])
                ->can($row->status->value === \App\Enums\CourseStatus::SCHEDULED->value),
            Button::add('finish')
                ->slot('<x-mary-icon name="o-flag" class="w-4 h-4" />')
                ->class('btn btn-square md:btn-sm btn-xs')
                ->dispatch('course-finish', ['id' => $row->id])
                ->can($row->status->value === \App\Enums\CourseStatus::ACTIVE->value),
            Button::add('sessions')
                ->slot(PowerGridHelper::iconShow())
                ->attributes([
                    'class' => 'btn btn-square md:btn-sm btn-xs',
                ])
                ->route('admin.session.index', ['course' => $row->id])
                ->tooltip(trans('datatable.buttons.sessions')),
            PowerGridHelper::btnToggle($row),
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

    public function publishCourse(int $id): void
    {
        $course = Course::findOrFail($id);
        $course->publish();
        $this->notification()->success(trans('general.success'), trans('course.published'));
    }

    public function startCourse(int $id): void
    {
        $course = Course::findOrFail($id);
        $course->start();
        $this->notification()->success(trans('general.success'), trans('course.started'));
    }

    public function finishCourse(int $id): void
    {
        $course = Course::findOrFail($id);
        $course->finish();
        $this->notification()->success(trans('general.success'), trans('course.finished'));
    }
}
