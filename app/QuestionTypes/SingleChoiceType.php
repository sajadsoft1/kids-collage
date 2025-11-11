<?php

declare(strict_types=1);

namespace App\QuestionTypes;

use Illuminate\Validation\ValidationException;

class SingleChoiceType extends AbstractQuestionType
{
    public function defaultConfig(): array
    {
        return [
            'min_options'        => 2,
            'max_options'        => 10,
            'shuffle_options'    => false,
            'show_explanation'   => false,
            'has_correct_answer' => true,
        ];
    }

    public function validationRules(): array
    {
        $config = $this->getConfig();

        return [
            'title'                     => ['required', 'string', 'max:2000'],
            'body'                      => ['nullable', 'string', 'max:10000'],
            'explanation'               => ['nullable', 'string'],
            'default_score'             => ['required', 'numeric', 'min:0'],

            'options'                   => [
                'required',
                'array',
                "min:{$config['min_options']}",
                "max:{$config['max_options']}",
            ],
            'options.*.content'         => ['required', 'string', 'max:1000'],
            'options.*.is_correct'      => ['required', 'boolean'],
            'options.*.order'           => ['nullable', 'integer'],

            'config.shuffle_options'    => ['nullable', 'boolean'],
            'config.show_explanation'   => ['nullable', 'boolean'],
            'config.has_correct_answer' => ['nullable', 'boolean'],
        ];
    }

    public function validationMessages(): array
    {
        return [
            'options.required'           => 'حداقل :min گزینه باید وجود داشته باشد',
            'options.*.content.required' => 'متن گزینه الزامی است',
        ];
    }

    public function afterValidation(array $data): array
    {
        if (($this->question->is_survey_question ?? false) || ! ($data['config']['has_correct_answer'] ?? true)) {
            return $data;
        }

        // بررسی اینکه فقط یک گزینه صحیح وجود داشته باشد
        $correctCount = collect($data['options'])
            ->where('is_correct', true)
            ->count();

        if ($correctCount !== 1) {
            throw ValidationException::withMessages([
                'options' => 'دقیقا یک گزینه باید به عنوان پاسخ صحیح انتخاب شود',
            ]);
        }

        return $data;
    }

    public function validateAnswer(mixed $answer): bool
    {
        // پاسخ باید ID یک گزینه باشد
        if ( ! is_numeric($answer)) {
            return false;
        }

        return $this->getOptions()
            ->where('id', $answer)
            ->isNotEmpty();
    }

    public function calculateScore(mixed $answer): float
    {
        $selectedOption = $this->getOptions()->find($answer);

        if ( ! $selectedOption || ! $selectedOption->is_correct) {
            return 0;
        }

        return $this->getWeight();
    }

    public function builderComponent(): string
    {
        return 'question-builder.single-choice';
    }

    public function displayComponent(): string
    {
        return 'question-display.single-choice';
    }

    public function getCorrectAnswer(): mixed
    {
        return $this->getOptions()
            ->where('is_correct', true)
            ->first()
            ?->id;
    }
}
