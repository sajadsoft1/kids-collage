<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Attendance;

use App\Helpers\PowerGridHelper;
use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\Enrollment;
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

final class AttendanceTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName     = 'index_attendance_datatable';
    public string $sortDirection = 'desc';

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
                ->fixedColumns('id', 'enrollment_id', 'session_id', 'actions');
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
            ['label' => trans('general.page.index.title', ['model' => trans('attendance.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            [
                'link'   => route('admin.attendance.create'),
                'icon'   => 's-plus',
                'label'  => trans(
                    'general.page.create.title',
                    ['model' => trans('attendance.model')]
                ),
                'access' => auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(Attendance::class, 'Store')),
            ],
        ];
    }

    public function datasource(): Builder
    {
        return Attendance::query()
            ->with(['enrollment.user', 'session.course']);
    }

    public function relationSearch(): array
    {
        return [
            'enrollment.user' => [
                'name',
                'email',
            ],
            'session.course'  => [
                'title',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('enrollment_formatted', fn ($row) => $row->enrollment?->user?->name ?? '---')
            ->add('session_formatted', fn ($row) => $row->session?->course?->title ?? '---')
            ->add('present_formatted', fn ($row) => $row->present ? 'حاضر' : 'غایب')
            ->add('arrival_time_formatted', fn ($row) => $row->arrival_time?->format('Y-m-d H:i') ?? '---')
            ->add('leave_time_formatted', fn ($row) => $row->leave_time?->format('Y-m-d H:i') ?? '---')
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row))
            ->add('updated_at_formatted', fn ($row) => PowerGridHelper::fieldUpdatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('datatable.enrollment'), 'enrollment_formatted'),
            Column::make(trans('datatable.session'), 'session_formatted'),
            Column::make(trans('datatable.present'), 'present_formatted'),
            Column::make(trans('datatable.arrival_time'), 'arrival_time_formatted'),
            Column::make(trans('datatable.leave_time'), 'leave_time_formatted'),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnUpdatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::boolean('present_formatted', 'present'),

            Filter::datepicker('created_at_formatted', 'created_at')
                ->params([
                    'maxDate' => now(),
                ]),

            Filter::select('enrollment_formatted', 'enrollment_id')
                ->dataSource(Enrollment::with('user')->get()->map(function ($enrollment) {
                    return [
                        'value' => $enrollment->id,
                        'label' => $enrollment->user->name,
                    ];
                })->toArray())->optionLabel('label')->optionValue('value'),

            Filter::select('session_formatted', 'session_id')
                ->dataSource(CourseSession::with('course')->get()->map(function ($session) {
                    return [
                        'value' => $session->id,
                        'label' => $session->course->title,
                    ];
                })->toArray())->optionLabel('label')->optionValue('value'),
        ];
    }

    public function actions(Attendance $row): array
    {
        return [
            PowerGridHelper::btnEdit($row),
            PowerGridHelper::btnDelete($row),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.attendance.create'),
        ]);
    }
}
