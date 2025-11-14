<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Survey;

use App\Actions\Question\StoreQuestionAction;
use App\Actions\Survey\StoreSurveyAction;
use App\Actions\Survey\UpdateSurveyAction;
use App\Enums\ExamStatusEnum;
use App\Enums\ExamTypeEnum;
use App\Enums\QuestionTypeEnum;
use App\Enums\UserTypeEnum;
use App\Models\Exam;
use App\Traits\CrudHelperTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class SurveyUpdateOrCreate extends Component
{
    use CrudHelperTrait,Toast;

    public Exam $model;
    public string $title = '';
    public string $description = '';
    public string $selectedTab = 'basic';
    public ?string $starts_at = null;
    public ?string $ends_at = null;
    public string $status = 'draft';
    public array $rules = [
        'groups' => [],
        'group_logic' => 'or',
    ];
    public array $questions = [];

    public function mount(Exam $exam): void
    {
        $this->model = $exam;
        $this->model->type = ExamTypeEnum::SURVEY;

        if ($this->model->id) {
            $this->title = $this->model->title;
            $this->description = $this->model->description ?? '';
            $this->starts_at = $this->model->starts_at?->format('Y-m-d');
            $this->ends_at = $this->model->ends_at?->format('Y-m-d');
            $this->status = $this->model->status->value;
            $this->rules = $this->model->getRules() ?? [
                'groups' => [],
                'group_logic' => 'or',
            ];

            // Load existing survey questions
            $this->questions = $this->model->questions()
                ->where('is_survey_question', true)
                ->orderBy('exam_question.order')
                ->get()
                ->map(function ($question, $index) {
                    return [
                        'id' => $question->id,
                        'type' => $question->type->value,
                        'title' => $question->title,
                        'body' => $question->body ?? '',
                        'explanation' => $question->explanation ?? '',
                        'options' => $question->options->map(fn ($opt) => [
                            'id' => $opt->id,
                            'content' => $opt->content,
                            'type' => $opt->type,
                            'is_correct' => $opt->is_correct,
                            'order' => $opt->order,
                            'metadata' => $opt->metadata ?? [],
                        ])->toArray(),
                        'config' => $question->config ?? [],
                        'correct_answer' => $question->correct_answer ?? [],
                        'order' => $index + 1,
                    ];
                })
                ->toArray();
        } else {
            $this->getDemoSurveyData();
        }
    }

    private function getDemoSurveyData(): void
    {
        $this->title = '';
        $this->description = '';
        $this->starts_at = now()->format('Y-m-d');
        $this->ends_at = now()->addDays(7)->format('Y-m-d');
        $this->status = ExamStatusEnum::DRAFT->value;

        $this->rules = [
            'group_logic' => 'or',
            'groups' => [
                [
                    'logic' => 'and',
                    'conditions' => [
                        [
                            'field' => 'user_type',
                            'operator' => 'equals',
                            'value' => UserTypeEnum::USER->value,
                        ],
                    ],
                ],
            ],
        ];

        $this->questions = [
            [
                'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
                'title' => 'آیا از کیفیت آموزش رضایت دارید؟',
                'body' => 'این نظرسنجی صرفا برای بهبود آموزش و ارزیابی کیفیت آموزش است.',
                'explanation' => 'لطفا برای پاسخ به این سوال تمام طول دوره را ارزیابی کنید',
                'options' => [
                    [
                        'content' => 'بله',
                        'type' => 'text',
                        'is_correct' => false,
                        'order' => 1,
                        'metadata' => [],
                    ],
                    [
                        'content' => 'خیر',
                        'type' => 'text',
                        'is_correct' => false,
                        'order' => 2,
                        'metadata' => [],
                    ],
                ],
                'config' => [
                    'shuffle_options' => true,
                    'show_explanation' => true,
                ],
                'correct_answer' => [],
            ],
        ];
    }

    public function addQuestion(): void
    {
        $this->questions[] = [
            'id' => null,
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => '',
            'body' => '',
            'explanation' => '',
            'options' => [],
            'config' => [
                'has_correct_answer' => false,
            ],
            'correct_answer' => [],
            'order' => count($this->questions) + 1,
        ];
    }

    public function removeQuestion(int $index): void
    {
        if (isset($this->questions[$index])) {
            unset($this->questions[$index]);
            $this->questions = array_values($this->questions);
            // Update order
            foreach ($this->questions as $i => $question) {
                $this->questions[$i]['order'] = $i + 1;
            }
        }
    }

    public function moveQuestion(int $index, string $direction): void
    {
        if ($direction === 'up' && $index > 0) {
            $temp = $this->questions[$index];
            $this->questions[$index] = $this->questions[$index - 1];
            $this->questions[$index - 1] = $temp;
            $this->questions[$index]['order'] = $index + 1;
            $this->questions[$index - 1]['order'] = $index;
        } elseif ($direction === 'down' && $index < count($this->questions) - 1) {
            $temp = $this->questions[$index];
            $this->questions[$index] = $this->questions[$index + 1];
            $this->questions[$index + 1] = $temp;
            $this->questions[$index]['order'] = $index + 1;
            $this->questions[$index + 1]['order'] = $index + 2;
        }
    }

    #[On('rulesUpdated')]
    public function handleRulesUpdated($rules): void
    {
        $this->rules = $rules;
    }

    #[On('optionsUpdated')]
    public function handleOptionsUpdated($data): void
    {
        // Handle options update from QuestionBuilder components
        // The data should include questionIndex to identify which question
        if (isset($data['index'], $this->questions[$data['index']])) {
            $this->questions[$data['index']]['options'] = $data['options'] ?? $data;
        }
    }

    #[On('configUpdated')]
    public function handleConfigUpdated($data): void
    {
        // Handle config update from QuestionBuilder components
        if (isset($data['index'], $this->questions[$data['index']])) {
            $this->questions[$data['index']]['config'] = $data['config'] ?? $data;
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'status' => ['required', 'string', 'in:' . implode(',', array_column(ExamStatusEnum::cases(), 'value'))],
            'rules' => 'required|array',
            'rules.groups' => 'required|array',
            'rules.group_logic' => 'required|string',
            'rules.groups.*.conditions' => 'required|array',
            'rules.groups.*.conditions.*.field' => 'required|string',
            'rules.groups.*.conditions.*.operator' => 'required|string',
            'rules.groups.*.conditions.*.value' => 'required|string',
            'rules.groups.*.logic' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.type' => ['required', 'string', 'in:' . implode(',', [QuestionTypeEnum::SINGLE_CHOICE->value, QuestionTypeEnum::MULTIPLE_CHOICE->value])],
            'questions.*.title' => 'required|string',
            'questions.*.body' => 'nullable|string',
            'questions.*.explanation' => 'nullable|string',
            'questions.*.options' => 'nullable|array',
            'questions.*.config' => 'nullable|array',
            'questions.*.correct_answer' => 'nullable|array',
        ];
    }

    public function submit(): void
    {
        $payload = $this->validate();
        $payload['rules'] = $this->rules;
        $payload['type'] = ExamTypeEnum::SURVEY->value;

        if ( ! $this->model->id) {
            $payload['created_by'] = Auth::id();
        }

        // Validate that at least one question exists
        if (empty($this->questions)) {
            $this->error('لطفاً حداقل یک سوال اضافه کنید.');

            return;
        }

        // Validate each question has a title and type
        foreach ($this->questions as $index => $question) {
            if (empty($question['title']) || empty($question['type'])) {
                $this->error('سوال شماره ' . ($index + 1) . ' باید عنوان و نوع داشته باشد.');

                return;
            }
        }

        $exam = null;
        if ($this->model->id) {
            $exam = UpdateSurveyAction::run($this->model, $payload);
            // Save questions separately for updates
            $this->saveQuestions($exam);
        } else {
            // Include questions in payload for new surveys
            $payload['questions'] = $this->questions;
            $exam = StoreSurveyAction::run($payload);
        }

        $this->success(
            title: $this->model->id
                ? trans('general.model_has_updated_successfully', ['model' => trans('_menu.survey_management')])
                : trans('general.model_has_stored_successfully', ['model' => trans('_menu.survey_management')]),
            redirectTo: route('admin.survey.index')
        );
    }

    protected function saveQuestions(Exam $exam): void
    {
        // Remove old survey questions
        $exam->questions()
            ->where('is_survey_question', true)
            ->get()
            ->each(function ($question) {
                $question->options()->delete();
                $question->delete();
            });

        // Detach all questions
        $exam->questions()->detach();

        // Create and attach new questions
        foreach ($this->questions as $index => $questionData) {
            $questionPayload = [
                'type' => $questionData['type'],
                'title' => $questionData['title'],
                'body' => $questionData['body'] ?? null,
                'explanation' => $questionData['explanation'] ?? null,
                'default_score' => 1,
                'config' => $questionData['config'] ?? [],
                'correct_answer' => $questionData['correct_answer'] ?? [],
                'is_survey_question' => true,
                'is_active' => true,
                'is_public' => false,
                'created_by' => Auth::id(),
            ];

            $question = StoreQuestionAction::run($questionPayload);

            // Save options if question needs them
            if ( ! empty($questionData['options'])) {
                foreach ($questionData['options'] as $optionData) {
                    $question->options()->create([
                        'content' => $optionData['content'] ?? '',
                        'type' => $optionData['type'] ?? 'text',
                        'is_correct' => $optionData['is_correct'] ?? false,
                        'order' => $optionData['order'] ?? 0,
                        'metadata' => $optionData['metadata'] ?? [],
                    ]);
                }
            }

            // Attach to exam
            $exam->questions()->attach($question->id, [
                'weight' => 1,
                'order' => $questionData['order'] ?? $index + 1,
                'is_required' => true,
                'config_override' => null,
            ]);
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.survey.survey-update-or-create', [
            'edit_mode' => $this->model->id,
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.survey.index'), 'label' => trans('_menu.survey_management')],
                ['label' => $this->model->id ? 'ویرایش' : 'ایجاد'],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.survey.index'), 'icon' => 's-arrow-left'],
            ],
            'questionTypes' => QuestionTypeEnum::surveyTypesFormatedCases(),
        ]);
    }
}
