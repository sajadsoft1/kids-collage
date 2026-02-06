<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use App\Enums\AttemptStatusEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Exam;
use App\Models\ExamAttempt;
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

final class ExamAttemptTable extends PowerGridComponent
{
    use PowerGridHelperTrait;

    public string $tableName = 'index_exam_attempt_datatable';

    public string $sortDirection = 'desc';

    public Exam $exam;

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'exam_title', 'user_name', 'actions'];
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => __('exam_attempt.index_title')],
        ];
    }

    public function datasource(): Builder
    {
        return ExamAttempt::query()
            ->where('exam_id', $this->exam->id)
            ->with(['exam', 'user'])
            ->withCount('answers');
    }

    public function relationSearch(): array
    {
        return [
            'exam' => ['title'],
            'user' => ['name', 'email'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('exam_title', fn ($row) => $row->exam?->title ?? '---')
            ->add('user_name', fn ($row) => $row->user?->name ?? '---')
            ->add('status_badge', fn ($row) => $this->getStatusBadge($row->status))
            ->add('total_score_formatted', fn ($row) => $row->getRawOriginal('total_score') !== null ? number_format((float) $row->getRawOriginal('total_score'), 2) : '---')
            ->add('percentage_formatted', fn ($row) => $row->getRawOriginal('percentage') !== null ? number_format((float) $row->getRawOriginal('percentage'), 1) . '%' : '---')
            ->add('answers_count')
            ->add('started_at_formatted', fn ($row) => $row->started_at?->format('Y/m/d H:i') ?? '---')
            ->add('completed_at_formatted', fn ($row) => $row->completed_at?->format('Y/m/d H:i') ?? '---')
            ->add('duration', fn ($row) => $this->formatDuration($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),

            Column::make(__('exam_attempt.exam'), 'exam_title')
                ->searchable()
                ->sortable(),

            Column::make(__('exam_attempt.user'), 'user_name')
                ->searchable()
                ->sortable(),

            Column::make(__('exam_attempt.status'), 'status_badge', 'status')
                ->sortable(),

            Column::make(__('exam_attempt.total_score'), 'total_score_formatted', 'total_score')
                ->sortable(),

            Column::make(__('exam_attempt.percentage'), 'percentage_formatted', 'percentage')
                ->sortable(),

            Column::make(__('exam_attempt.answers_count'), 'answers_count')
                ->sortable(),

            Column::make(__('exam_attempt.started_at'), 'started_at_formatted', 'started_at')
                ->sortable(),

            Column::make(__('exam_attempt.completed_at'), 'completed_at_formatted', 'completed_at')
                ->sortable(),

            Column::make(__('exam_attempt.duration'), 'duration'),

            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('status', 'status')
                ->datasource(AttemptStatusEnum::cases()),

            PowerGridHelper::filterDatepickerJalali('started_at_formatted', 'started_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),

            PowerGridHelper::filterDatepickerJalali('completed_at_formatted', 'completed_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),
        ];
    }

    public function actions(ExamAttempt $row): array
    {
        $actions = [];

        // دکمه ادامه آزمون (فقط برای in_progress)
        if ($row->canContinue()) {
            $actions[] = Button::add('continue')
                ->slot('<i class="fa fa-play text-info"></i>')
                ->attributes([
                    'class' => 'btn btn-square md:btn-sm btn-xs',
                ])
                ->route('admin.exam.taker', ['exam' => $this->exam->id], '_self')
                ->navigate()
                ->tooltip(__('exam_attempt.continue'));
        }

        // دکمه مشاهده نتیجه (برای آزمون‌های تمام شده)
        if ($row->isFinished()) {
            $actions[] = Button::add('results')
                ->slot(PowerGridHelper::iconShow())
                ->attributes([
                    'class' => 'btn btn-square md:btn-sm btn-xs',
                ])
                ->route('admin.exam.attempt.results', ['exam' => $this->exam->id, 'attempt' => $row->id], '_self')
                ->navigate()
                ->tooltip(__('exam_attempt.view_results'));
        }

        return $actions;
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'message' => __('exam_attempt.no_attempts'),
        ]);
    }

    /** بج وضعیت آزمون */
    private function getStatusBadge(AttemptStatusEnum $status): string
    {
        return sprintf(
            '<span class="badge badge-sm %s">%s</span>',
            $this->getStatusClass($status),
            $status->title()
        );
    }

    private function getStatusClass(AttemptStatusEnum $status): string
    {
        return match ($status) {
            AttemptStatusEnum::IN_PROGRESS => 'badge-info',
            AttemptStatusEnum::COMPLETED => 'badge-success',
            AttemptStatusEnum::ABANDONED => 'badge-warning',
            AttemptStatusEnum::EXPIRED => 'badge-error',
        };
    }

    /** فرمت مدت زمان آزمون */
    private function formatDuration(ExamAttempt $row): string
    {
        if ( ! $row->started_at) {
            return '---';
        }

        $endTime = $row->completed_at ?? now();
        $seconds = $row->started_at->diffInSeconds($endTime);

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        }

        return sprintf('%02d:%02d', $minutes, $secs);
    }
}
