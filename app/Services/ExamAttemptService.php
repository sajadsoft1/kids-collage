<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AttemptStatusEnum;
use App\Events\ExamCompleted;
use App\Events\ExamStarted;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Models\User;
use Exception;

class ExamAttemptService
{
    /** شروع آزمون */
    public function start(Exam $exam, User $user): ExamAttempt
    {
        if ( ! $exam->canUserTakeExam($user)) {
            throw new Exception('شما مجاز به شرکت در این آزمون نیستید');
        }

        // بررسی آزمون در حال انجام
        $inProgressAttempt = $exam->getUserInProgressAttempt($user);
        if ($inProgressAttempt) {
            return $inProgressAttempt;
        }

        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'user_id' => $user->id,
            'started_at' => now(),
            'status' => AttemptStatusEnum::IN_PROGRESS,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        event(new ExamStarted($attempt));

        return $attempt;
    }

    /** ثبت پاسخ */
    public function submitAnswer(
        ExamAttempt $attempt,
        Question $question,
        mixed $answerData,
        ?int $timeSpent = null
    ): \App\Models\UserAnswer {
        if ( ! $attempt->canContinue()) {
            throw new Exception('این آزمون قابل ویرایش نیست');
        }

        // بررسی انقضا
        if ($attempt->isExpired()) {
            $attempt->expire();

            throw new Exception('زمان آزمون به پایان رسیده است');
        }

        // بررسی اینکه سوال جزء این آزمون است
        if ( ! $attempt->exam->hasQuestion($question)) {
            throw new Exception('این سوال جزء این آزمون نیست');
        }

        return $attempt->recordAnswer($question, $answerData, $timeSpent);
    }

    /** تکمیل آزمون */
    public function complete(ExamAttempt $attempt): ExamAttempt
    {
        if ( ! $attempt->canContinue()) {
            throw new Exception('این آزمون قبلا تکمیل شده است');
        }

        $attempt->complete();

        event(new ExamCompleted($attempt));

        return $attempt->fresh();
    }

    /** رها کردن آزمون */
    public function abandon(ExamAttempt $attempt): ExamAttempt
    {
        $attempt->abandon();

        return $attempt->fresh();
    }

    /** دریافت سوال بعدی */
    public function getNextQuestion(ExamAttempt $attempt): ?Question
    {
        $answeredQuestionIds = $attempt->answers()->pluck('question_id')->toArray();

        return $attempt->exam->questions()
            ->whereNotIn('questions.id', $answeredQuestionIds)
            ->first();
    }

    /** دریافت پیشرفت */
    public function getProgress(ExamAttempt $attempt): array
    {
        return [
            'total_questions' => $attempt->getTotalQuestionsCount(),
            'answered_questions' => $attempt->getAnsweredQuestionsCount(),
            'remaining_questions' => $attempt->getRemainingQuestionsCount(),
            'progress_percentage' => $attempt->getProgressPercentage(),
            'remaining_time' => $attempt->getRemainingTime(),
            'elapsed_time' => $attempt->getElapsedTime(),
        ];
    }

    /** دریافت نتایج */
    public function getResults(ExamAttempt $attempt): array
    {
        if ( ! $attempt->isFinished()) {
            throw new Exception('آزمون هنوز تکمیل نشده است');
        }

        return $attempt->getResults();
    }

    /** نمره‌دهی دستی به پاسخ */
    public function manualGrade(
        \App\Models\UserAnswer $answer,
        float $score,
        ?string $feedback = null
    ): \App\Models\UserAnswer {
        $answer->update([
            'score' => min($score, $answer->max_score),
            'is_correct' => $score >= $answer->max_score,
            'is_partially_correct' => $score > 0 && $score < $answer->max_score,
            'reviewed_at' => now(),
        ]);

        // به‌روزرسانی نمره کل attempt
        $attempt = $answer->attempt;
        $totalScore = $attempt->exam->calculateAttemptScore($attempt);
        $percentage = $attempt->exam->total_score > 0
            ? ($totalScore / $attempt->exam->total_score) * 100
            : null;

        $attempt->update([
            'total_score' => $totalScore,
            'percentage' => $percentage,
        ]);

        return $answer->fresh();
    }
}
