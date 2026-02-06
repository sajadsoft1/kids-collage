<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Attendance;

use App\Helpers\PowerGridHelper;
use App\Models\Course;
use App\Models\CourseSession;
use App\Traits\HasLearningModal;
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

/**
 * Attendance summary by session.
 * One row per course session: course, date/time, present count, absent count, percentage, link to mark attendance.
 */
final class AttendanceBySessionTable extends PowerGridComponent
{
    use HasLearningModal;
    use PowerGridHelperTrait;

    public string $tableName = 'attendance_by_session_datatable';

    public string $sortDirection = 'desc';

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'course_formatted', 'session_formatted', 'actions'];
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['link' => route('admin.attendance.index'), 'label' => trans('general.page.index.title', ['model' => trans('attendance.model')])],
            ['label' => trans('attendance.page.by_session')],
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
                'link' => route('admin.attendance.by-student'),
                'icon' => 's-user',
                'label' => trans('attendance.page.by_student'),
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
            'by_session' => [
                'title' => trans('attendance.learning.by_session.title'),
                'content' => trans('attendance.learning.by_session.content'),
                'icon' => 'o-calendar-days',
            ],
        ];
    }

    public function datasource(): Builder
    {
        return CourseSession::query()
            ->with(['course.template', 'sessionTemplate'])
            ->withCount(['attendances', 'presentAttendances']);
    }

    public function relationSearch(): array
    {
        return [
            'course.template' => ['title'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('course_formatted', fn ($row) => $row->course?->template?->title ?? '---')
            ->add('session_formatted', function ($row) {
                return $row->sessionTemplate?->title ?? '';
            })
            ->add('date_formatted', fn ($row) => $row->date ? jdate($row->date)->format('%A, %d %B %Y') : '')
            ->add('start_time_formatted', fn ($row) => $row->start_time ? jdate($row->start_time)->format('H:i') : '')
            ->add('end_time_formatted', fn ($row) => $row->end_time ? jdate($row->end_time)->format('H:i') : '')
            ->add('room_formatted', fn ($row) => $row->room?->name ?? '---')
            ->add('attendances_count')
            ->add('present_attendances_count')
            ->add('absent_count_formatted', fn ($row) => (int) $row->attendances_count - (int) $row->present_attendances_count)
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
            Column::make(trans('course.model'), 'course_formatted'),
            Column::make(trans('courseSession.model'), 'session_formatted'),
            Column::make(trans('validation.attributes.date'), 'date_formatted'),
            Column::make(trans('validation.attributes.start_time'), 'start_time_formatted'),
            Column::make(trans('validation.attributes.end_time'), 'end_time_formatted'),
            Column::make(trans('validation.attributes.room_id'), 'room_formatted'),
            Column::make(trans('attendance.page.present_count'), 'present_attendances_count')->sortable(),
            Column::make(trans('attendance.page.absent_count'), 'absent_count_formatted'),
            Column::make(trans('attendance.page.attendance_percent'), 'attendance_percent_formatted'),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            PowerGridHelper::filterDatepickerJalali('session_formatted', 'date', [
                'maxDate' => now()->endOfYear()->format('Y-m-d'),
            ]),
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
        ];
    }

    public function actions(CourseSession $row): array
    {
        $course = $row->course;
        if ( ! $course) {
            return [];
        }

        return [
            Button::add('mark-attendance')
                ->slot(PowerGridHelper::iconEdit())
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs'])
                ->route('admin.course.show', [
                    'courseTemplate' => $course->course_template_id,
                    'course' => $course->id,
                    'session_id' => $row->id,
                ], '_self')
                ->tooltip(trans('attendance.page.mark_attendance')),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.attendance.index'),
        ]);
    }
}
