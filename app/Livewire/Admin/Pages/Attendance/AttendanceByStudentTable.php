<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Attendance;

use App\Helpers\PowerGridHelper;
use App\Models\Course;
use App\Models\Enrollment;
use App\Traits\HasLearningModal;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

/**
 * Attendance summary by student (enrollment).
 * One row per enrollment: student, course, total sessions, present count, percentage.
 */
final class AttendanceByStudentTable extends PowerGridComponent
{
    use HasLearningModal;
    use PowerGridHelperTrait;

    public string $tableName = 'attendance_by_student_datatable';

    public string $sortDirection = 'desc';

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'student_formatted', 'course_formatted', 'actions'];
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['link' => route('admin.attendance.index'), 'label' => trans('general.page.index.title', ['model' => trans('attendance.model')])],
            ['label' => trans('attendance.page.by_student')],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return $this->withLearningModalActions([
            [
                'link' => route('admin.attendance.index'),
                'icon' => 's-arrow-left',
                'label' => trans('attendance.page.all_records'),
            ],
            [
                'link' => route('admin.attendance.by-session'),
                'icon' => 's-calendar',
                'label' => trans('attendance.page.by_session'),
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
            'by_student' => [
                'title' => trans('attendance.learning.by_student.title'),
                'content' => trans('attendance.learning.by_student.content'),
                'icon' => 'o-user',
            ],
        ];
    }

    public function datasource(): Builder
    {
        return Enrollment::query()
            ->with(['user', 'course.template'])
            ->withCount(['attendances', 'presentAttendances']);
    }

    public function relationSearch(): array
    {
        return [
            'user' => ['name', 'email'],
            'course.template' => ['title'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('student_formatted', fn ($row) => $row->user?->full_name ?? '---')
            ->add('course_formatted', fn ($row) => $row->course?->template?->title ?? '---')
            ->add('attendances_count')
            ->add('present_attendances_count')
            ->add('attendance_percent_formatted', function ($row) {
                $total = (int) $row->attendances_count;
                if ($total === 0) {
                    return '0%';
                }
                $present = (int) $row->present_attendances_count;

                return round(($present / $total) * 100, 1) . '%';
            });
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('datatable.enrollment'), 'student_formatted'),
            Column::make(trans('course.model'), 'course_formatted'),
            Column::make(trans('attendance.page.total_sessions'), 'attendances_count')->sortable(),
            Column::make(trans('attendance.page.present_count'), 'present_attendances_count')->sortable(),
            Column::make(trans('attendance.page.attendance_percent'), 'attendance_percent_formatted'),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('course_formatted', 'course_id')
                ->dataSource(
                    Course::query()
                        ->with('template')
                        ->get()
                        ->map(fn (Course $c) => [
                            'value' => $c->id,
                            'label' => $c->template?->title ?? (string) $c->id,
                        ])
                        ->values()
                        ->all()
                )
                ->optionLabel('label')
                ->optionValue('value'),

            Filter::select('student_formatted', 'user_id')
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
                ->builder(function (Builder $query, $value): void {
                    if (filled($value)) {
                        $query->where('user_id', (int) $value);
                    }
                }),
        ];
    }

    /** Clear user filter when course filter changes so options stay in sync. */
    public function updatedFiltersSelectCourseId(mixed $value): void
    {
        if (isset($this->filters['select']['user_id'])) {
            $this->filters['select']['user_id'] = null;
        }
    }

    public function actions(Enrollment $row): array
    {
        return [
            Button::add('view-records')
                ->slot(PowerGridHelper::iconShow())
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs'])
                ->route('admin.attendance.index', [
                    'course_id' => $row->course_id,
                    'user_id' => $row->user_id,
                ], '_self')
                ->tooltip(trans('attendance.page.view_records')),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.attendance.index'),
        ]);
    }
}
