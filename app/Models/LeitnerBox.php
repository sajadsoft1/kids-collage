<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Traits\HasUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeitnerBox extends Model
{
    use HasUser;

    /**
     * Standard Leitner intervals (powers of 2)
     * Box 1: 1 day, Box 2: 2 days, Box 3: 4 days, Box 4: 8 days, Box 5: 16 days
     */
    public const array INTERVALS = [
        1 => 1,    // Every day
        2 => 2,    // Every 2 days
        3 => 4,    // Every 4 days
        4 => 8,    // Every 8 days
        5 => 16,   // Every 16 days (mastered)
    ];

    public const int MAX_BOX = 5;

    protected $fillable = [
        'user_id',
        'flash_card_id',
        'box',
        'next_review_at',
        'last_review_at',
        'finished',
    ];

    protected $casts = [
        'next_review_at' => 'datetime',
        'last_review_at' => 'datetime',
        'finished' => BooleanEnum::class,
    ];

    // ══════════════════════════════════════════════════════════════════════════
    // RELATIONSHIPS
    // ══════════════════════════════════════════════════════════════════════════

    public function flashcard(): BelongsTo
    {
        return $this->belongsTo(FlashCard::class);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // SCOPES
    // ══════════════════════════════════════════════════════════════════════════

    /** Scope to get records that are due for review (next_review_at has passed) */
    public function scopeDue(Builder $query): Builder
    {
        return $query->where('next_review_at', '<=', now());
    }

    /** Scope to get records that are scheduled for future review */
    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('next_review_at', '>', now());
    }

    /** Scope to get finished/mastered records */
    public function scopeFinished(Builder $query): Builder
    {
        return $query->where('finished', BooleanEnum::ENABLE);
    }

    /** Scope to get in-progress records (not finished) */
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('finished', BooleanEnum::DISABLE);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ACCESSORS
    // ══════════════════════════════════════════════════════════════════════════

    /** Check if this card is due for review */
    public function isDue(): bool
    {
        return $this->next_review_at && $this->next_review_at->isPast();
    }

    /** Check if this card is finished/mastered */
    public function isFinished(): bool
    {
        return $this->finished === BooleanEnum::ENABLE;
    }

    /** Get days until next review */
    public function getDaysUntilReview(): int
    {
        if ( ! $this->next_review_at) {
            return 0;
        }

        return max(0, now()->startOfDay()->diffInDays($this->next_review_at->startOfDay(), false));
    }

    // ══════════════════════════════════════════════════════════════════════════
    // INSTANCE METHODS
    // ══════════════════════════════════════════════════════════════════════════

    /** Mark the card as known (move up in Leitner box) */
    public function markKnown(): self
    {
        $newBox = min($this->box + 1, self::MAX_BOX);

        $this->update([
            'box' => $newBox,
            'last_review_at' => now(),
            'next_review_at' => self::calculateNextReview($newBox),
            'finished' => $newBox >= self::MAX_BOX ? BooleanEnum::ENABLE : BooleanEnum::DISABLE,
        ]);

        return $this;
    }

    /** Mark the card as unknown (move to box 1) */
    public function markUnknown(): self
    {
        $this->update([
            'box' => 1,
            'last_review_at' => now(),
            'next_review_at' => self::calculateNextReview(1),
            'finished' => BooleanEnum::DISABLE,
        ]);

        return $this;
    }

    /** Reset progress (delete record or move to box 0) */
    public function resetProgress(): bool
    {
        return $this->delete();
    }

    // ══════════════════════════════════════════════════════════════════════════
    // STATIC METHODS
    // ══════════════════════════════════════════════════════════════════════════

    /** Get Leitner box intervals for display */
    public static function getIntervals(): array
    {
        return [
            1 => ['days' => 1, 'label' => __('flashCard.leitner.intervals.box1')],
            2 => ['days' => 2, 'label' => __('flashCard.leitner.intervals.box2')],
            3 => ['days' => 4, 'label' => __('flashCard.leitner.intervals.box3')],
            4 => ['days' => 8, 'label' => __('flashCard.leitner.intervals.box4')],
            5 => ['days' => 16, 'label' => __('flashCard.leitner.intervals.box5')],
        ];
    }

    /** Calculate next review date based on Leitner box */
    public static function calculateNextReview(int $box): Carbon
    {
        return now()->addDays(self::INTERVALS[$box] ?? 1);
    }

    /** Get or create a LeitnerBox record for a flash card and user */
    public static function getOrCreateForCard(int $flashCardId, int $userId): self
    {
        return self::firstOrCreate(
            ['flash_card_id' => $flashCardId, 'user_id' => $userId],
            [
                'box' => 0,
                'next_review_at' => now(),
                'last_review_at' => now(),
                'finished' => BooleanEnum::DISABLE,
            ]
        );
    }
}
