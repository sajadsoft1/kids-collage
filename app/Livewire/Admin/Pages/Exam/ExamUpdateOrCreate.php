<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use App\Actions\Exam\StoreExamAction;
use App\Actions\Exam\UpdateExamAction;
use App\Enums\CategoryTypeEnum;
use App\Enums\ExamStatusEnum;
use App\Enums\ExamTypeEnum;
use App\Enums\QuestionTypeEnum;
use App\Enums\ShowResultsEnum;
use App\Models\Category;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class ExamUpdateOrCreate extends Component
{
    use Toast;

    public Exam $model;

    /** @var array<string, mixed> */
    public array $form = [];

    /** @var array{groups: array<int, mixed>, group_logic: string} */
    public array $ruleBuilderState = [
        'groups' => [],
        'group_logic' => 'or',
    ];

    /** @var array<int, string> */
    public array $tags = [];

    public string $selectedTab = 'basic';

    public bool $questionSelectorValid = true;

    protected ?array $categoryCache = null;

    protected ?array $questionStatsCache = null;

    public function mount(Exam $exam): void
    {
        $this->model = $exam;
        $this->form = $this->defaultForm();

        if ($this->model->exists) {
            $this->fillFormFromModel();
            $this->tags = $this->model->tags->pluck('name')->toArray();
            $this->ruleBuilderState = $this->model->getRules() ?? $this->ruleBuilderState;
        }
    }

    #[On('rulesUpdated')]
    public function handleRulesUpdated(array $rules): void
    {
        $this->ruleBuilderState = $rules;
    }

    #[On('questionSelectorValidationChanged')]
    public function handleQuestionSelectorValidationChanged(bool $isValid): void
    {
        $this->questionSelectorValid = $isValid;
    }

    public function updatedFormTotalScore(): void
    {
        // Dispatch event to update question selector when total_score changes
        if ($this->model->exists) {
            $this->dispatch('totalScoreUpdated', totalScore: $this->form['total_score']);
        }
    }

    protected function rules(): array
    {
        $statusValues = array_map(fn (ExamStatusEnum $status) => $status->value, ExamStatusEnum::cases());
        $resultValues = array_map(fn (ShowResultsEnum $enum) => $enum->value, ShowResultsEnum::cases());

        return [
            'form.title' => ['required', 'string', 'max:500'],
            'form.description' => ['nullable', 'string'],
            'form.category_id' => ['nullable', 'exists:categories,id'],
            'form.total_score' => [
                'nullable',
                'numeric',
                'min:0',
                Rule::requiredIf(fn () => $this->form['type'] === ExamTypeEnum::SCORED->value),
            ],
            'form.duration' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'form.pass_score' => [
                'nullable',
                'numeric',
                'min:0',
                Rule::requiredIf(fn () => $this->form['type'] === ExamTypeEnum::SCORED->value),
                'lte:form.total_score',
            ],
            'form.max_attempts' => ['nullable', 'integer', 'min:1', 'max:50'],
            'form.shuffle_questions' => ['boolean'],
            'form.show_results' => ['required', Rule::in($resultValues)],
            'form.allow_review' => ['boolean'],
            'form.starts_at' => ['nullable', 'date'],
            'form.ends_at' => ['nullable', 'date', 'after:form.starts_at'],
            'form.status' => ['required', Rule::in($statusValues)],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'ruleBuilderState' => ['nullable', 'array'],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        // Check if question selector validation passed
        if ( ! $this->questionSelectorValid && $this->model->exists && $this->model->type === ExamTypeEnum::SCORED) {
            $this->error(
                title: trans('exam.validation.total_weight_mismatch'),
                timeout: 5000
            );

            return;
        }

        $payload = $this->preparePayload();

        try {
            if ($this->model->exists) {
                UpdateExamAction::run($this->model, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('exam.model')]),
                    redirectTo: route('admin.exam.index')
                );
            } else {
                StoreExamAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('exam.model')]),
                    redirectTo: route('admin.exam.index')
                );
            }
        } catch (Throwable $e) {
            $this->error($e->getMessage(), timeout: 5000);
        }
    }

    protected function preparePayload(): array
    {
        $data = $this->form;

        if ($data['type'] === ExamTypeEnum::SURVEY->value) {
            $data['total_score'] = null;
            $data['pass_score'] = null;
        }

        $data['duration'] = $data['duration'] !== null ? (int) $data['duration'] : null;
        $data['max_attempts'] = $data['max_attempts'] !== null ? (int) $data['max_attempts'] : null;
        $data['total_score'] = $data['total_score'] !== null ? (float) $data['total_score'] : null;
        $data['pass_score'] = $data['pass_score'] !== null ? (float) $data['pass_score'] : null;
        $data['shuffle_questions'] = (bool) $data['shuffle_questions'];
        $data['allow_review'] = (bool) $data['allow_review'];
        $data['starts_at'] = $this->parseDate($data['starts_at']);
        $data['ends_at'] = $this->parseDate($data['ends_at']);
        $data['rules'] = ! empty(Arr::get($this->ruleBuilderState, 'groups', []))
            ? $this->ruleBuilderState
            : null;
        $data['tags'] = is_array($this->tags) ? $this->tags : [];

        if ( ! $this->model->exists) {
            $data['created_by'] = Auth::id();
        }

        return $data;
    }

    protected function fillFormFromModel(): void
    {
        $this->form = [
            'title' => $this->model->title,
            'description' => (string) $this->model->description,
            'category_id' => $this->model->category_id,
            'type' => $this->model->type instanceof ExamTypeEnum ? $this->model->type->value : (string) $this->model->type,
            'total_score' => $this->model->total_score,
            'duration' => $this->model->duration,
            'pass_score' => $this->model->pass_score,
            'max_attempts' => $this->model->max_attempts,
            'shuffle_questions' => (bool) $this->model->shuffle_questions,
            'show_results' => (string) $this->model->show_results,
            'allow_review' => (bool) $this->model->allow_review,
            'starts_at' => $this->model->starts_at?->format('Y-m-d\\TH:i'),
            'ends_at' => $this->model->ends_at?->format('Y-m-d\\TH:i'),
            'status' => $this->model->status instanceof ExamStatusEnum ? $this->model->status->value : (string) $this->model->status,
        ];
    }

    protected function defaultForm(): array
    {
        return [
            'title' => '',
            'description' => '',
            'category_id' => null,
            'type' => ExamTypeEnum::SCORED->value,
            'total_score' => 100,
            'duration' => 60,
            'pass_score' => 60,
            'max_attempts' => 1,
            'shuffle_questions' => false,
            'show_results' => ShowResultsEnum::AFTER_SUBMIT->value,
            'allow_review' => true,
            'starts_at' => null,
            'ends_at' => null,
            'status' => ExamStatusEnum::DRAFT->value,
        ];
    }

    protected function parseDate(?string $value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        return Carbon::createFromFormat('Y-m-d\\TH:i', $value);
    }

    protected function enumOptions(array $cases): array
    {
        return collect($cases)->map(fn ($case) => [
            'value' => $case->value,
            'label' => $case->title(),
            'description' => method_exists($case, 'description') ? $case->description() : '',
        ])->toArray();
    }

    protected function buildQuestionStats(): array
    {
        if ($this->questionStatsCache !== null) {
            return $this->questionStatsCache;
        }

        if ( ! $this->model->exists) {
            return $this->questionStatsCache = [
                'total' => 0,
                'weight' => 0,
                'manual' => 0,
                'breakdown' => [],
            ];
        }

        $this->model->loadMissing('questions');

        $questions = $this->model->questions;

        if ($questions->isEmpty()) {
            return $this->questionStatsCache = [
                'total' => 0,
                'weight' => 0,
                'manual' => 0,
                'breakdown' => [],
            ];
        }

        $manualCount = $questions->filter(function ($question) {
            $type = $question->type;

            return $type instanceof QuestionTypeEnum && $type->requiresManualGrading();
        })->count();

        return $this->questionStatsCache = [
            'total' => $questions->count(),
            'weight' => (float) $questions->sum(fn ($question) => (float) ($question->pivot->weight ?? 0)),
            'manual' => $manualCount,
            'breakdown' => $questions
                ->groupBy(fn ($question) => $question->type?->value ?? 'unknown')
                ->map(fn ($group, $type) => [
                    'label' => $group->first()->type?->title() ?? ucfirst(str_replace('_', ' ', $type)),
                    'count' => $group->count(),
                ])
                ->values()
                ->all(),
        ];
    }

    protected function categoryOptions(): array
    {
        if ($this->categoryCache !== null) {
            return $this->categoryCache;
        }

        return $this->categoryCache = Category::query()
            ->where('type', CategoryTypeEnum::EXAM->value)
            ->orderBy('id')
            ->get(['id'])
            ->map(fn ($category) => [
                'value' => $category->id,
                'label' => $category->title,
            ])
            ->toArray();
    }

    public function render(): View
    {
        return view('livewire.admin.pages.exam.exam-update-or-create', [
            'edit_mode' => $this->model->exists,
            'exam' => $this->model,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.exam.index'), 'label' => trans('general.page.index.title', ['model' => trans('exam.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('exam.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.exam.index'), 'icon' => 's-arrow-left'],
            ],
            'categories' => $this->categoryOptions(),
            'types' => $this->enumOptions(ExamTypeEnum::cases()),
            'statuses' => $this->enumOptions(ExamStatusEnum::cases()),
            'showResults' => $this->enumOptions(ShowResultsEnum::cases()),
            'questionStats' => $this->buildQuestionStats(),
        ]);
    }
}
