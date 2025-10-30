<?php

declare(strict_types=1);

namespace App\QuestionTypes;

use App\Models\Question;
use App\QuestionTypes\Contracts\QuestionTypeInterface;

abstract class AbstractQuestionType implements QuestionTypeInterface
{
    protected Question $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    /** تنظیمات پیش‌فرض */
    abstract public function defaultConfig(): array;

    /** قوانین اعتبارسنجی */
    abstract public function validationRules(): array;

    /** پیام‌های اعتبارسنجی */
    public function validationMessages(): array
    {
        return [];
    }

    /** اعتبارسنجی سفارشی بعد از validation اولیه */
    public function afterValidation(array $data): array
    {
        return $data;
    }

    /** اعتبارسنجی پاسخ */
    abstract public function validateAnswer(mixed $answer): bool;

    /** محاسبه نمره */
    abstract public function calculateScore(mixed $answer): float;

    /** کامپوننت ساخت */
    abstract public function builderComponent(): string;

    /** کامپوننت نمایش */
    abstract public function displayComponent(): string;

    /** دریافت پاسخ صحیح */
    abstract public function getCorrectAnswer(): mixed;

    /** پشتیبانی از نمره جزئی */
    public function supportsPartialCredit(): bool
    {
        return false;
    }

    /** نیاز به بررسی دستی */
    public function requiresManualReview(): bool
    {
        return false;
    }

    /** پاک‌سازی پاسخ */
    public function sanitizeAnswer(mixed $answer): array
    {
        return is_array($answer) ? $answer : ['value' => $answer];
    }

    /** دریافت وزن سوال (از pivot یا default) */
    protected function getWeight(): float
    {
        return $this->question->getAttribute('weight') ?? $this->question->default_score;
    }

    /** دریافت config (با override از exam) */
    protected function getConfig(): array
    {
        return array_merge(
            $this->defaultConfig(),
            $this->question->config ?? []
        );
    }

    /** دریافت گزینه‌ها */
    protected function getOptions()
    {
        return $this->question->options;
    }

    /** مقایسه آرایه‌ها (برای پاسخ‌های چند گزینه‌ای) */
    protected function arrayEquals(array $a, array $b): bool
    {
        sort($a);
        sort($b);

        return $a === $b;
    }
}
