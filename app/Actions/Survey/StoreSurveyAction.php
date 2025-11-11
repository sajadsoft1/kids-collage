<?php

declare(strict_types=1);

namespace App\Actions\Survey;

use App\Actions\Question\StoreQuestionAction;
use App\Actions\Translation\SyncTranslationAction;
use App\Enums\QuestionTypeEnum;
use App\Models\Exam;
use App\Models\Question;
use App\QuestionTypes\AbstractQuestionType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreSurveyAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly StoreQuestionAction $storeQuestionAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     type:string,
     *     starts_at:string,
     *     ends_at:string,
     *     status:string,
     *     rules?: array,
     *     questions?: array
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Exam
    {
        return DB::transaction(function () use ($payload) {
            $rules     = $payload['rules'] ?? null;
            $questions = $payload['questions'] ?? [];

            unset($payload['rules'], $payload['questions']);

            $payload['created_by'] ??= Auth::id();

            $model = Exam::create($payload);

            // $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            // Set rules if provided
            if ($rules !== null) {
                $model->setRules($rules);
                $model->save();
            }

            // Validate and store questions
            if ( ! empty($questions)) {
                $this->validateAndStoreQuestions($model, $questions);
            }

            return $model->refresh();
        });
    }

    /**
     * Validate and store questions with their options
     *
     * @param array<int, array{
     *     type: string,
     *     title: string,
     *     body?: string,
     *     explanation?: string,
     *     options?: array,
     *     config?: array,
     *     correct_answer?: array
     * }> $questions
     * @throws Throwable
     */
    protected function validateAndStoreQuestions(Exam $exam, array $questions): void
    {
        foreach ($questions as $index => $questionData) {
            // Validate question type exists
            $questionType = QuestionTypeEnum::tryFrom($questionData['type'] ?? '');

            if ( ! $questionType) {
                throw new InvalidArgumentException("Invalid question type at index {$index}");
            }

            // Get QuestionType handler class
            $handlerClass = $questionType->handler();
            $tempQuestion = new Question([
                'type' => $questionData['type'],
                'config' => $questionData['config'] ?? [],
                'is_survey_question' => true,
                'default_score' => 0,
            ]);

            /** @var AbstractQuestionType $questionTypeHandler */
            $questionTypeHandler = new $handlerClass($tempQuestion);

            // Prepare question data with default values
            $questionPayload = [
                'type' => $questionData['type'],
                'title' => $questionData['title'],
                'body' => $questionData['body'] ?? null,
                'explanation' => $questionData['explanation'] ?? null,
                'default_score' => 0,
                'config' => array_merge(
                    $questionTypeHandler->defaultConfig(),
                    $questionData['config'] ?? []
                ),
                'options' => $questionData['options'] ?? [],
                'correct_answer' => $questionData['correct_answer'] ?? [],
                'is_survey_question' => true,
                'is_active' => true,
                'is_public' => false,
                'created_by' => Auth::id(),
            ];

            // Validate using QuestionType validation rules
            $validationRules    = $questionTypeHandler->validationRules();
            $validationMessages = $questionTypeHandler->validationMessages();

            $validator = Validator::make($questionPayload, $validationRules, $validationMessages);

            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }

            $validatedData = $validator->validated();

            // Run afterValidation hook
            $validatedData = $questionTypeHandler->afterValidation($validatedData);

            // Merge system fields that are not in validation rules
            $validatedData = array_merge($validatedData, [
                'type' => $questionData['type'],
                'default_score' => 0,
                'is_survey_question' => true,
                'is_active' => true,
                'is_public' => false,
                'created_by' => Auth::id(),
            ]);

            // Extract options before storing question (options are stored separately)
            $options = isset($validatedData['options']) && is_array($validatedData['options'])
                ? $validatedData['options']
                : [];
            unset($validatedData['options']);

            // Store question
            $question = $this->storeQuestionAction->handle($validatedData);

            // Store options if provided
            if ( ! empty($options)) {
                $this->storeQuestionOptions($question, $options);
            }

            // Attach question to exam
            $exam->questions()->attach($question->id, [
                'weight' => 1,
                'order' => $index + 1,
                'is_required' => true,
                'config_override' => null,
            ]);
        }
    }

    /**
     * Store question options
     *
     * @param array<int, array{
     *     content: string,
     *     is_correct: bool,
     *     order: int,
     *     type?: string,
     *     metadata?: array
     * }> $options
     */
    protected function storeQuestionOptions(Question $question, array $options): void
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
}
