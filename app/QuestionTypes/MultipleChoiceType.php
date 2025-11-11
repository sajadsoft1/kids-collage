<?php

declare(strict_types=1);

namespace App\QuestionTypes;

use Illuminate\Validation\ValidationException;

class MultipleChoiceType extends AbstractQuestionType
{
    public function defaultConfig(): array
    {
        return [
            'min_options' => 2,
            'max_options' => 10,
            'min_correct' => 1,
            'max_correct' => 5,
            'shuffle_options' => false,
            'scoring_type' => 'all_or_nothing', // all_or_nothing, partial
            'has_correct_answer' => true,
        ];
    }

    public function validationRules(): array
    {
        $config = $this->getConfig();

        return [
            'title' => ['required', 'string', 'max:2000'],
            'body' => ['nullable', 'string', 'max:10000'],
            'explanation' => ['nullable', 'string'],
            'default_score' => ['required', 'numeric', 'min:0'],

            'options' => [
                'required',
                'array',
                "min:{$config['min_options']}",
                "max:{$config['max_options']}",
            ],
            'options.*.content' => ['required', 'string', 'max:1000'],
            'options.*.is_correct' => ['required', 'boolean'],

            'config.scoring_type' => ['nullable', 'in:all_or_nothing,partial'],
            'config.shuffle_options' => ['nullable', 'boolean'],
            'config.has_correct_answer' => ['nullable', 'boolean'],
        ];
    }

    public function afterValidation(array $data): array
    {
        if (($this->question->is_survey_question ?? false) || ! ($data['config']['has_correct_answer'] ?? true)) {
            return $data;
        }

        $config = $this->getConfig();

        $correctCount = collect($data['options'])
            ->where('is_correct', true)
            ->count();

        if ($correctCount < $config['min_correct']) {
            throw ValidationException::withMessages([
                'options' => "حداقل {$config['min_correct']} گزینه صحیح باید وجود داشته باشد",
            ]);
        }

        if ($correctCount > $config['max_correct']) {
            throw ValidationException::withMessages([
                'options' => "حداکثر {$config['max_correct']} گزینه صحیح مجاز است",
            ]);
        }

        return $data;
    }

    public function validateAnswer(mixed $answer): bool
    {
        if ( ! is_array($answer) || empty($answer)) {
            return false;
        }

        $optionIds = $this->getOptions()->pluck('id')->toArray();

        // بررسی اینکه همه IDها معتبر باشند
        foreach ($answer as $id) {
            if ( ! in_array($id, $optionIds)) {
                return false;
            }
        }

        return true;
    }

    public function calculateScore(mixed $answer): float
    {
        $correctIds = $this->getOptions()
            ->where('is_correct', true)
            ->pluck('id')
            ->toArray();

        $config = $this->getConfig();
        $weight = $this->getWeight();

        if ($config['scoring_type'] === 'all_or_nothing') {
            // همه یا هیچ
            return $this->arrayEquals($answer, $correctIds) ? $weight : 0;
        }

        // نمره‌دهی جزئی
        $selectedCorrect = count(array_intersect($answer, $correctIds));
        $selectedIncorrect = count(array_diff($answer, $correctIds));
        $totalCorrect = count($correctIds);

        // فرمول: (صحیح انتخاب شده - غلط انتخاب شده) / کل صحیح
        $score = ($selectedCorrect - $selectedIncorrect) / $totalCorrect;
        $score = max(0, $score); // حداقل 0

        return round($score * $weight, 2);
    }

    public function builderComponent(): string
    {
        return 'question-builder.multiple-choice';
    }

    public function displayComponent(): string
    {
        return 'question-display.multiple-choice';
    }

    public function getCorrectAnswer(): mixed
    {
        return $this->getOptions()
            ->where('is_correct', true)
            ->pluck('id')
            ->toArray();
    }

    public function supportsPartialCredit(): bool
    {
        return $this->getConfig()['scoring_type'] === 'partial';
    }
}
