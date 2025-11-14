<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use App\Enums\ExamStatusEnum;
use App\Enums\ExamTypeEnum;
use App\Helpers\PowerGridHelper;
use App\Models\Exam;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class ExamTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName = 'index_exam_datatable';
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
            ['label' => trans('general.page.index.title', ['model' => trans('exam.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.exam.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('exam.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Exam::query()->withCount(['questions', 'attempts']);
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
            ->add('type_label', fn ($row) => $row->type instanceof ExamTypeEnum ? $row->type->title() : (string) $row->type)
            ->add('status_label', fn ($row) => $row->status instanceof ExamStatusEnum ? $row->status->title() : (string) $row->status)
            ->add('total_score')
            ->add('duration')
            ->add('questions_count')
            ->add('attempts_count')
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle(),
            \PowerComponents\LivewirePowerGrid\Column::make(trans('exam.type'), 'type_label', 'type')
                ->searchable()
                ->sortable(),
            \PowerComponents\LivewirePowerGrid\Column::make(trans('exam.status'), 'status_label', 'status')
                ->sortable(),
            \PowerComponents\LivewirePowerGrid\Column::make(trans('exam.total_score'), 'total_score', 'total_score')
                ->sortable(),
            \PowerComponents\LivewirePowerGrid\Column::make(trans('exam.duration'), 'duration', 'duration')
                ->sortable(),
            \PowerComponents\LivewirePowerGrid\Column::make(trans('exam.questions_count'), 'questions_count')
                ->sortable(),
            \PowerComponents\LivewirePowerGrid\Column::make(trans('exam.attempts_count'), 'attempts_count')
                ->sortable(),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::enumSelect('type', 'type')
                ->datasource(ExamTypeEnum::cases()),
            Filter::enumSelect('status', 'status')
                ->datasource(ExamStatusEnum::cases()),
            Filter::datepicker('created_at_formatted', 'created_at')
                ->params([
                    'maxDate' => now(),
                ]),
        ];
    }

    public function actions(Exam $row): array
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
            'link' => route('admin.exam.create'),
        ]);
    }
}
