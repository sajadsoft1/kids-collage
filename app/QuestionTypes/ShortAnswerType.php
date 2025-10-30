<?php

declare(strict_types=1);

namespace App\QuestionTypes;

class ShortAnswerType extends AbstractQuestionType
{
    public function defaultConfig(): array
    {
        return [
            'max_length'      => 500,
            'case_sensitive'  => false,
            'trim_whitespace' => true,
            'auto_grade'      => false, // آیا خودکار نمره‌دهی شود؟
        ];
    }

    public function validationRules(): array
    {
        return [
            'title'                               => ['required', 'string', 'max:2000'],
            'body'                                => ['nullable', 'string'],
            'default_score'                       => ['required', 'numeric', 'min:0'],
            'correct_answer'                      => ['required', 'array'],
            'correct_answer.acceptable_answers'   => ['required', 'array', 'min:1'],
            'correct_answer.acceptable_answers.*' => ['required', 'string'],
        ];
    }

    public function validateAnswer(mixed $answer): bool
    {
        $config = $this->getConfig();

        return is_string($answer)
            && strlen($answer) > 0
            && strlen($answer) <= $config['max_length'];
    }

    public function calculateScore(mixed $answer): float
    {
        $config = $this->getConfig();

        // اگر نمره‌دهی دستی است
        if ( ! $config['auto_grade']) {
            return 0; // باید دستی نمره داده شود
        }

        $acceptableAnswers = $this->question->correct_answer['acceptable_answers'] ?? [];

        $userAnswer = $answer;
        if ($config['trim_whitespace']) {
            $userAnswer = trim($userAnswer);
        }
        if ( ! $config['case_sensitive']) {
            $userAnswer = mb_strtolower($userAnswer);
        }

        foreach ($acceptableAnswers as $acceptable) {
            if ($config['trim_whitespace']) {
                $acceptable = trim($acceptable);
            }
            if ( ! $config['case_sensitive']) {
                $acceptable = mb_strtolower($acceptable);
            }

            if ($userAnswer === $acceptable) {
                return $this->getWeight();
            }
        }

        return 0;
    }

    public function builderComponent(): string
    {
        return 'question-builder.short-answer';
    }

    public function displayComponent(): string
    {
        return 'question-display.short-answer';
    }

    public function getCorrectAnswer(): mixed
    {
        return $this->question->correct_answer['acceptable_answers'] ?? [];
    }

    public function requiresManualReview(): bool
    {
        return ! $this->getConfig()['auto_grade'];
    }
}
