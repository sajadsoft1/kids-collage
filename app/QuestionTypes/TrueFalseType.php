<?php

declare(strict_types=1);

namespace App\QuestionTypes;

class TrueFalseType extends AbstractQuestionType
{
    public function defaultConfig(): array
    {
        return [
            'true_label' => 'درست',
            'false_label' => 'غلط',
        ];
    }

    public function validationRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:2000'],
            'body' => ['nullable', 'string', 'max:10000'],
            'explanation' => ['nullable', 'string'],
            'default_score' => ['required', 'numeric', 'min:0'],
            'correct_answer' => ['nullable', 'array'],
            'correct_answer.value' => ['nullable', 'boolean'],
        ];
    }

    public function validateAnswer(mixed $answer): bool
    {
        return is_bool($answer) || in_array($answer, [0, 1, '0', '1', 'true', 'false']);
    }

    public function calculateScore(mixed $answer): float
    {
        $answer = filter_var($answer, FILTER_VALIDATE_BOOLEAN);
        $correctAnswer = $this->question->correct_answer['value'] ?? false;

        return $answer === $correctAnswer ? $this->getWeight() : 0;
    }

    public function builderComponent(): string
    {
        return 'question-builder.true-false';
    }

    public function displayComponent(): string
    {
        return 'question-display.true-false';
    }

    public function getCorrectAnswer(): mixed
    {
        return $this->question->correct_answer['value'] ?? false;
    }
}
