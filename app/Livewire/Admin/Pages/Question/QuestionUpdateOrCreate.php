<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Question;

use App\Actions\Question\StoreQuestionAction;
use App\Actions\Question\UpdateQuestionAction;
use App\Enums\CategoryTypeEnum;
use App\Enums\DifficultyEnum;
use App\Enums\QuestionTypeEnum;
use App\Models\Category;
use App\Models\Question;
use App\Models\QuestionCompetency;
use App\Models\QuestionSubject;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class QuestionUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;
    use WithFileUploads;

    public $isEditMode = false;

    // Form Fields
    public $type;
    public $category_id;
    public $subject_id;
    public $competency_id;
    public $title;
    public $body;
    public $explanation;
    public $difficulty;
    public $default_score = 1;
    public $tags = [];

    // Dynamic based on type
    public $options = [];
    public $config = [];
    public $correct_answer = null;
    public $metadata = [];
    public Question $model;

    public function mount(Question $question): void
    {
        $this->model = $question;
        if ($question && $question->exists) {
            $this->isEditMode = true;
            $this->fill([
                'type' => $question->type->value,
                'category_id' => $question->category_id,
                'subject_id' => $question->subject_id,
                'competency_id' => $question->competency_id,
                'title' => $question->title,
                'body' => $question->body,
                'explanation' => $question->explanation,
                'difficulty' => $question->difficulty?->value,
                'default_score' => $question->default_score,
                'options' => $question->options->map(fn ($opt) => [
                    'id' => $opt->id,
                    'content' => $opt->content,
                    'type' => $opt->type,
                    'is_correct' => $opt->is_correct,
                    'order' => $opt->order,
                    'metadata' => $opt->metadata,
                ])->toArray(),
                'config' => $question->config ?? [],
                'correct_answer' => $question->correct_answer ?? null,
                'metadata' => $question->metadata ?? [],
                'tags' => $question->tags->pluck('name')->toArray(),
            ]);
        }
    }

    protected function rules(): array
    {
        $baseRules = [
            'type' => 'required|string',
            'title' => 'required|string|max:2000',
            'body' => 'nullable|string',
            'explanation' => 'nullable|string',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'default_score' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'subject_id' => 'nullable|exists:question_subjects,id',
            'competency_id' => 'nullable|exists:question_competencies,id',
            'tags' => 'nullable|array',
        ];

        // Add dynamic rules based on question type
        if ($this->type) {
            $typeEnum = QuestionTypeEnum::from($this->type);
            $handler = $typeEnum->handler();

            // Create temporary question for validation
            $tempQuestion = new Question(['type' => $typeEnum, 'options' => $this->options, 'config' => $this->config]);
            $typeHandler = new $handler($tempQuestion);

            $typeRules = $typeHandler->validationRules();

            return array_merge($baseRules, $typeRules);
        }

        return $baseRules;
    }

    protected function messages(): array
    {
        $baseMessages = [];

        // Add dynamic messages based on question type
        if ($this->type) {
            $typeEnum = QuestionTypeEnum::from($this->type);
            $handler = $typeEnum->handler();

            // Create temporary question for validation
            $tempQuestion = new Question(['type' => $typeEnum, 'options' => $this->options, 'config' => $this->config]);
            $typeHandler = new $handler($tempQuestion);

            if (method_exists($typeHandler, 'validationMessages')) {
                $typeMessages = $typeHandler->validationMessages();

                return array_merge($baseMessages, $typeMessages);
            }
        }

        return $baseMessages;
    }

    #[On('optionsUpdated')]
    public function handleOptionsUpdated($data): void
    {
        // Handle options update from QuestionBuilder components
        // When questionIndex is undefined, it means it's for single question (not array)
        if ( ! isset($data['index'])) {
            $this->options = $data['options'] ?? $data;
        }
    }

    #[On('configUpdated')]
    public function handleConfigUpdated($data): void
    {
        // Handle config update from QuestionBuilder components
        if ( ! isset($data['index'])) {
            $this->config = $data['config'] ?? $data;
        }
    }

    #[On('correctAnswerUpdated')]
    public function handleCorrectAnswerUpdated($data): void
    {
        // Handle correct_answer update from QuestionBuilder components
        if ( ! isset($data['index'])) {
            $this->correct_answer = $data['correct_answer'] ?? $data;
        }
    }

    public function submit(): void
    {
        // Validate all fields including options, config, correct_answer
        $payload = $this->validate();

        // Extract options before storing question (options are stored separately)
        $options = $payload['options'] ?? $this->options;
        unset($payload['options']);

        // Extract tags before storing question (tags are stored separately)
        $tags = $payload['tags'] ?? $this->tags;
        unset($payload['tags']);

        if ($this->model->id) {
            $question = UpdateQuestionAction::run($this->model, $payload);

            // Update options
            $this->updateOptions($question, $options);

            // Update tags
            if ( ! empty($tags)) {
                $question->syncTags($tags);
            }

            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('question.model')]),
                redirectTo: route('admin.question.index')
            );
        } else {
            $question = StoreQuestionAction::run($payload);

            // Store options if provided
            if ( ! empty($options)) {
                $this->storeOptions($question, $options);
            }

            // Store tags if provided
            if ( ! empty($tags)) {
                $question->syncTags($tags);
            }

            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('question.model')]),
                redirectTo: route('admin.question.index')
            );
        }
    }

    protected function storeOptions(Question $question, array $options): void
    {
        if (empty($options) || ! is_array($options)) {
            return;
        }

        foreach ($options as $optionData) {
            if ( ! is_array($optionData)) {
                continue;
            }

            $question->options()->create([
                'content' => $optionData['content'] ?? '',
                'type' => $optionData['type'] ?? 'text',
                'is_correct' => $optionData['is_correct'] ?? false,
                'order' => $optionData['order'] ?? 0,
                'metadata' => $optionData['metadata'] ?? [],
            ]);
        }
    }

    protected function updateOptions(Question $question, array $options): void
    {
        // Delete old options
        $question->options()->delete();

        // Store new options
        if ( ! empty($options)) {
            $this->storeOptions($question, $options);
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.question.question-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.question.index'), 'label' => trans('general.page.index.title', ['model' => trans('question.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('question.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.question.index'), 'icon' => 's-arrow-left'],
            ],
            'types' => QuestionTypeEnum::formatedCases(),
            // 'types' => QuestionTypeEnum::availableTypes(),
            'difficulties' => DifficultyEnum::formatedCases(),
            'categories' => Category::where('type', CategoryTypeEnum::QUESTION->value)->get()->map(fn ($category) => ['value' => $category->id, 'label' => $category->title]),
            'subjects' => QuestionSubject::get()->map(fn ($subject) => ['value' => $subject->id, 'label' => $subject->title]),
            'competencies' => QuestionCompetency::all()->map(fn ($competency) => ['value' => $competency->id, 'label' => $competency->title]),
        ]);
    }
}
