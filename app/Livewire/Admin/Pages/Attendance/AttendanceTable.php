<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Attendance;

use App\Actions\Attendance\BulkUpdateAttendanceAction;
use App\Helpers\PowerGridHelper;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\Enrollment;
use App\Services\Permissions\PermissionsService;
use App\Traits\HasLearningModal;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Mary\Traits\Toast;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class AttendanceTable extends PowerGridComponent
{
    use HasLearningModal;
    use PowerGridHelperTrait;
    use Toast;

    public string $tableName = 'index_attendance_datatable';
    public string $sortDirection = 'desc';

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'course_formatted', 'session_formatted', 'actions'];
        $this->showCheckBox('id');
    }

    public function mount(): void
    {
        parent::mount();

        if (request()->filled('course_id')) {
            data_set($this->filters, 'select.course_id', (int) request('course_id'));
            $this->addEnabledFilters('course_id', trans('course.model'));
        }
        if (request()->filled('user_id')) {
            data_set($this->filters, 'select.user_id', (int) request('user_id'));
            $this->addEnabledFilters('user_id', trans('datatable.enrollment'));
        }
    }

    protected function afterPowerGridSetUp(array &$setup): void
    {
        $setup[0] = $setup[0]->includeViewOnBottom('livewire.admin.pages.attendance.partials.bulk-actions');
    }

    /** Whether the user can perform bulk attendance update. */
    public function canBulkUpdateAttendance(): bool
    {
        return auth()->user()?->hasAnyPermission(
            PermissionsService::generatePermissionsByModel(Attendance::class, 'Update')
        ) ?? false;
    }

    /** Mark selected attendance records as present. */
    public function markBulkPresent(): void
    {
        if ( ! $this->canBulkUpdateAttendance() || empty($this->checkboxValues)) {
            return;
        }

        $count = BulkUpdateAttendanceAction::run($this->checkboxValues, true);
        $this->checkboxValues = [];
        $this->dispatch('pgBulkActions::clear', $this->tableName);
        $this->success(trans('attendance.notifications.students_marked_present', ['count' => $count]));
    }

    /** Mark selected attendance records as absent. */
    public function markBulkAbsent(): void
    {
        if ( ! $this->canBulkUpdateAttendance() || empty($this->checkboxValues)) {
            return;
        }

        $count = BulkUpdateAttendanceAction::run($this->checkboxValues, false);
        $this->checkboxValues = [];
        $this->dispatch('pgBulkActions::clear', $this->tableName);
        $this->success(trans('attendance.notifications.students_marked_absent', ['count' => $count]));
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
        return $this->withLearningModalActions([
            [
                'link' => route('admin.attendance.by-student'),
                'icon' => 's-user',
                'label' => trans('attendance.page.by_student'),
            ],
            [
                'link' => route('admin.attendance.by-session'),
                'icon' => 's-calendar',
                'label' => trans('attendance.page.by_session'),
            ],
            [
                'link' => route('admin.attendance.create'),
                'icon' => 's-plus',
                'label' => trans(
                    'general.page.create.title',
                    ['model' => trans('attendance.model')]
                ),
                'access' => auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(Attendance::class, 'Store')),
            ],
        ]);
    }

    /**
     * Learning modal sections for this page.
     *
     * @return array<int|string, array{title: string, content: string, icon?: string}>
     */
    public function getLearningSections(): array
    {
        return [
            'all_records' => [
                'title' => trans('attendance.learning.all_records.title'),
                'content' => trans('attendance.learning.all_records.content'),
                'icon' => 'o-list-bullet',
            ],
        ];
    }

    public function datasource(): Builder
    {
        $query = Attendance::with(['enrollment.user', 'session.course']);

        if (request()->filled('course_id')) {
            $query->whereHas('session', fn (Builder $q) => $q->where('course_id', (int) request('course_id')));
        }

        if (request()->filled('user_id')) {
            $query->whereHas('enrollment', fn (Builder $q) => $q->where('user_id', (int) request('user_id')));
        }

        return $query;
    }

    public function relationSearch(): array
    {
        return [
            'enrollment.user' => [
                'name',
                'family',
                'mobile',
                'email',
            ],
            'session.course' => [
                'title',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('enrollment_formatted', fn ($row) => $row->enrollment?->user?->full_name ?? '---')
            ->add('course_formatted', fn ($row) => $row->session?->course?->template?->title ?? '---')
            ->add('session_formatted', function ($row) {
                $start_time = $row->session?->start_time ? $row->session?->start_time->format('H:i') : '';
                $end_time = $row->session?->end_time ? $row->session?->end_time->format('H:i') : '';
                $template = $row->session?->sessionTemplate?->title ?? '';

                return $template ? trim("{$template} — {$start_time} - {$end_time}") : "{$start_time} - {$end_time}";
            })
            ->add('present_formatted', fn ($row) => $row->present->value ? 'حاضر' : 'غایب')
            ->add('arrival_time_formatted', fn ($row) => $row->arrival_time ? jdate($row->arrival_time)->format('%A, %d %B %Y ساعت H:i') : '---')
            ->add('leave_time_formatted', fn ($row) => $row->leave_time ? jdate($row->leave_time)->format('%A, %d %B %Y ساعت H:i') : '---')
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row))
            ->add('updated_at_formatted', fn ($row) => PowerGridHelper::fieldUpdatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('datatable.enrollment'), 'enrollment_formatted'),
            Column::make(trans('course.model'), 'course_formatted'),
            Column::make(trans('courseSession.model'), 'session_formatted'),
            Column::make(trans('datatable.present'), 'present_formatted'),
            Column::make(trans('datatable.arrival_time'), 'arrival_time_formatted'),
            Column::make(trans('datatable.leave_time'), 'leave_time_formatted'),
            PowerGridHelper::columnCreatedAT()->hidden(true, false),
            PowerGridHelper::columnUpdatedAT()->hidden(true, false),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::boolean('present_formatted', 'present'),

            PowerGridHelper::filterDatepickerJalali('created_at_formatted', 'created_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),

            Filter::select('course_formatted', 'course_id')
                ->dataSource(Course::with('template')->get()->map(function ($course) {
                    return [
                        'value' => $course->id,
                        'label' => $course->template?->title,
                    ];
                })->toArray())
                ->optionLabel('label')
                ->optionValue('value')
                ->builder(function ($query, $value): void {
                    if (filled($value)) {
                        $query->whereHas('session', fn (Builder $q) => $q->where('course_id', $value));
                    }
                }),

            Filter::select('enrollment_formatted', 'user_id')
                ->depends(['course_id'])
                ->dataSource(function (array|Collection $depends): array {
                    $courseId = $depends instanceof Collection
                        ? $depends->get('course_id')
                        : ($depends['course_id'] ?? null);
                    if ( ! filled($courseId)) {
                        return [];
                    }

                    return Enrollment::query()
                        ->where('course_id', (int) $courseId)
                        ->with('user')
                        ->get()
                        ->map(fn (Enrollment $e) => [
                            'value' => $e->user_id,
                            'label' => $e->user?->full_name ?? (string) $e->user_id,
                        ])
                        ->unique('value')
                        ->values()
                        ->toArray();
                })
                ->optionLabel('label')
                ->optionValue('value')
                ->builder(function ($query, $value): void {
                    if (filled($value)) {
                        $query->whereHas('enrollment', fn (Builder $q) => $q->where('user_id', $value));
                    }
                }),

            Filter::select('session_formatted', 'course_session_id')
                ->depends(['course_id'])
                ->dataSource(function (array|Collection $depends): array {
                    $courseId = $depends instanceof Collection
                        ? $depends->get('course_id')
                        : ($depends['course_id'] ?? null);
                    $query = CourseSession::with('sessionTemplate');
                    if (filled($courseId)) {
                        $query->where('course_id', $courseId);
                    }

                    return $query->get()->map(fn ($session) => [
                        'value' => $session->id,
                        'label' => $session->sessionTemplate?->title ?? (string) $session->id,
                    ])->toArray();
                })
                ->optionLabel('label')
                ->optionValue('value'),
        ];
    }

    /** Clear session and user filters when course filter changes so options stay in sync. */
    public function updatedFiltersSelectCourseId(mixed $value): void
    {
        if (isset($this->filters['select']['course_session_id'])) {
            $this->filters['select']['course_session_id'] = null;
        }
        if (isset($this->filters['select']['user_id'])) {
            $this->filters['select']['user_id'] = null;
        }
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
