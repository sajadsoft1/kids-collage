<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Question;

use App\Actions\Question\DeleteQuestionAction;
use App\Helpers\PowerGridHelper;
use App\Models\Question;
use App\Services\QuestionService;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class QuestionTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    public string $tableName = 'index_question_datatable';
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
            ['label' => trans('general.page.index.title', ['model' => trans('question.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.question.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('question.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Question::where('is_survey_question', false);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('title', fn ($row) => PowerGridHelper::fieldTitle($row))
            ->add('category_title', fn ($row) => $row->category?->title)
            ->add('subject_title', fn ($row) => $row->subject?->title)
            ->add('competency_title', fn ($row) => $row->competency?->title)
            ->add('type_formatted', fn ($row) => $row->type->title())
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            PowerGridHelper::columnTitle()->searchable(),
            Column::make(trans('validation.attributes.category_id'), 'category_title', 'category.title'),
            Column::make(trans('validation.attributes.subject_id'), 'subject_title', 'subject.title'),
            Column::make(trans('validation.attributes.competency_id'), 'competency_title', 'competency.title'),
            Column::make(trans('validation.attributes.type'), 'type_formatted', 'type'),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('created_at_formatted', 'created_at')->params(['maxDate' => now()]),
        ];
    }

    public function actions(Question $row): array
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
            'link' => route('admin.question.create'),
        ]);
    }

    #[On('force-delete')]
    public function forceDelete($rowId): void
    {
        $question = Question::findOrFail($rowId);
        $this->authorize('delete', $question);

        $this->dispatch('question-deleted');
        DeleteQuestionAction::run($question);
    }

    #[On('duplicate-question')]
    public function duplicateQuestion($questionId)
    {
        $question = Question::findOrFail($questionId);

        $this->authorize('create', Question::class);

        $newQuestion = app(QuestionService::class)->duplicate($question);

        $this->dispatch('question-duplicated', questionId: $newQuestion->id);
    }
}
