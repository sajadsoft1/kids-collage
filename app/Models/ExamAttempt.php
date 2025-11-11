<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AttemptStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id',
        'user_id',
        'started_at',
        'completed_at',
        'total_score',
        'percentage',
        'status',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'status' => AttemptStatusEnum::class,
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'metadata' => 'array',
    ];

    protected $attributes = [
        'status' => 'in_progress',
        'metadata' => '{}',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', AttemptStatusEnum::IN_PROGRESS);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', AttemptStatusEnum::COMPLETED);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function getAnswer(Question $question): ?UserAnswer
    {
        return $this->answers()
            ->where('question_id', $question->id)
            ->first();
    }

    public function hasAnswered(Question $question): bool
    {
        return $this->answers()
            ->where('question_id', $question->id)
            ->exists();
    }

    public function recordAnswer(
        Question $question,
        mixed $answerData,
        ?int $timeSpent = null
    ): UserAnswer {
        $score              = null;
        $isCorrect          = null;
        $isPartiallyCorrect = null;

        $weight = $this->exam->getQuestionWeight($question);

        if ($this->exam->isScored()) {
            $handler   = $question->typeHandler();
            $score     = $question->scoreAnswer($answerData, $weight);
            $isCorrect = $score >= $weight;

            if ($handler->supportsPartialCredit() && $score > 0 && $score < $weight) {
                $isPartiallyCorrect = true;
            }
        }

        return $this->answers()->updateOrCreate(
            ['question_id' => $question->id],
            [
                'answer_data' => $answerData,
                'score' => $score,
                'max_score' => $weight,
                'is_correct' => $isCorrect,
                'is_partially_correct' => $isPartiallyCorrect,
                'time_spent' => $timeSpent,
                'answered_at' => now(),
            ]
        );
    }

    public function complete(): void
    {
        $totalScore = $this->exam->calculateAttemptScore($this);
        $percentage = $this->exam->total_score > 0
            ? ($totalScore / $this->exam->total_score) * 100
            : null;

        $this->update([
            'completed_at' => now(),
            'status' => AttemptStatusEnum::COMPLETED,
            'total_score' => $totalScore,
            'percentage' => $percentage,
        ]);
    }

    public function abandon(): void
    {
        $this->update(['status' => AttemptStatusEnum::ABANDONED]);
    }

    public function expire(): void
    {
        $this->update([
            'status' => AttemptStatusEnum::EXPIRED,
            'completed_at' => now(),
        ]);
    }

    public function getProgressPercentage(): float
    {
        $totalQuestions    = $this->exam->questions()->count();
        $answeredQuestions = $this->answers()->count();

        return $totalQuestions > 0
            ? round(($answeredQuestions / $totalQuestions) * 100, 2)
            : 0;
    }

    public function getAnsweredQuestionsCount(): int
    {
        return $this->answers()->count();
    }

    public function getTotalQuestionsCount(): int
    {
        return $this->exam->questions()->count();
    }

    public function getRemainingQuestionsCount(): int
    {
        return $this->getTotalQuestionsCount() - $this->getAnsweredQuestionsCount();
    }

    public function getRemainingTime(): ?int
    {
        if ( ! $this->exam->duration) {
            return null;
        }

        $endTime = $this->started_at->addMinutes($this->exam->duration);
        $now     = now();

        if ($now->greaterThan($endTime)) {
            return 0;
        }

        return (int) $now->diffInSeconds($endTime);
    }

    public function isExpired(): bool
    {
        $remainingTime = $this->getRemainingTime();

        return $remainingTime !== null && $remainingTime <= 0;
    }

    public function getElapsedTime(): int
    {
        $endTime = $this->completed_at ?? now();

        return (int) $this->started_at->diffInSeconds($endTime);
    }

    public function isPassed(): ?bool
    {
        if ( ! $this->exam->isScored() || ! $this->exam->pass_score) {
            return null;
        }

        return $this->total_score >= $this->exam->pass_score;
    }

    public function getResults(): array
    {
        $answers = $this->answers;

        return [
            'total_questions' => $this->getTotalQuestionsCount(),
            'answered_questions' => $this->getAnsweredQuestionsCount(),
            'correct_answers' => $answers->where('is_correct', true)->count(),
            'incorrect_answers' => $answers->where('is_correct', false)->count(),
            'partially_correct' => $answers->where('is_partially_correct', true)->count(),
            'total_score' => $this->total_score,
            'max_score' => $this->exam->total_score,
            'percentage' => $this->percentage,
            'passed' => $this->isPassed(),
            'time_spent' => $this->getElapsedTime(),
        ];
    }

    public function canContinue(): bool
    {
        return $this->status === AttemptStatusEnum::IN_PROGRESS && ! $this->isExpired();
    }

    public function isFinished(): bool
    {
        return $this->status->isFinished();
    }
}
