<?php

declare(strict_types=1);

namespace App\QuestionTypes\Contracts;

interface QuestionTypeInterface
{
    /** تنظیمات پیش‌فرض برای این نوع سوال */
    public function defaultConfig(): array;

    /** قوانین اعتبارسنجی برای ساخت/ویرایش سوال */
    public function validationRules(): array;

    /** پیام‌های سفارشی اعتبارسنجی */
    public function validationMessages(): array;

    /** اعتبارسنجی پاسخ کاربر */
    public function validateAnswer(mixed $answer): bool;

    /** محاسبه نمره */
    public function calculateScore(mixed $answer): float;

    /** کامپوننت Livewire برای ساخت سوال (ادمین) */
    public function builderComponent(): string;

    /** کامپوننت Livewire برای نمایش سوال (کاربر) */
    public function displayComponent(): string;

    /** دریافت پاسخ صحیح */
    public function getCorrectAnswer(): mixed;

    /** آیا از نمره‌دهی جزئی پشتیبانی می‌کند؟ */
    public function supportsPartialCredit(): bool;

    /** آیا نیاز به بررسی دستی دارد؟ */
    public function requiresManualReview(): bool;

    /** پاک‌سازی پاسخ قبل از ذخیره */
    public function sanitizeAnswer(mixed $answer): array;
}
