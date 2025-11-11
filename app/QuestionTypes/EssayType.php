<?php

declare(strict_types=1);

namespace App\QuestionTypes;

class EssayType extends AbstractQuestionType
{
    public function defaultConfig(): array
    {
        return [
            'min_words' => 50,
            'max_words' => 1000,
            'rich_text' => true,
        ];
    }

    public function validationRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:2000'],
            'body' => ['nullable', 'string'],
            'default_score' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function validateAnswer(mixed $answer): bool
    {
        if ( ! is_string($answer) || empty(trim($answer))) {
            return false;
        }

        $config = $this->getConfig();
        $wordCount = str_word_count(strip_tags($answer));

        return $wordCount >= $config['min_words']
            && $wordCount <= $config['max_words'];
    }

    public function calculateScore(mixed $answer): float
    {
        // سوالات تشریحی نیاز به نمره‌دهی دستی دارند
        return 0;
    }

    public function builderComponent(): string
    {
        return 'question-builder.essay';
    }

    public function displayComponent(): string
    {
        return 'question-display.essay';
    }

    public function getCorrectAnswer(): mixed
    {
        return null; // پاسخ مشخصی ندارد
    }

    public function requiresManualReview(): bool
    {
        return true;
    }
}
