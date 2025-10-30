<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    protected $fillable = [
        'exam_attempt_id',
        'question_id',
        'answer_data',
        'score',
        'max_score',
        'is_correct',
        'is_partially_correct',
        'time_spent',
        'answered_at',
        'reviewed_at',
    ];

    protected $casts = [
        'answer_data'          => 'array',
        'score'                => 'decimal:2',
        'max_score'            => 'decimal:2',
        'is_correct'           => 'boolean',
        'is_partially_correct' => 'boolean',
        'time_spent'           => 'integer',
        'answered_at'          => 'datetime',
        'reviewed_at'          => 'datetime',
    ];

    // ============================================
    // Relations
    // ============================================

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    public function scopePartiallyCorrect($query)
    {
        return $query->where('is_partially_correct', true);
    }

    // ============================================
    // Helpers
    // ============================================

    public function getQuestionWeight(): float
    {
        return $this->max_score;
    }

    public function getScorePercentage(): float
    {
        if ($this->max_score <= 0) {
            return 0;
        }

        return round(($this->score / $this->max_score) * 100, 2);
    }

    public function markAsReviewed(): void
    {
        $this->update(['reviewed_at' => now()]);
    }

    public function isReviewed(): bool
    {
        return $this->reviewed_at !== null;
    }
}
