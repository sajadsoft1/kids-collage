<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BooleanEnum;
use App\Traits\HasUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string      $front
 * @property string      $back
 * @property BooleanEnum $favorite
 *
 * @property-read int $leitner_box
 * @property-read bool $is_due
 * @property-read bool $is_new
 * @property-read bool $is_finished
 * @property-read \Carbon\Carbon|null $next_review
 */
class FlashCard extends Model
{
    use HasFactory;
    use HasUser;

    protected $fillable = [
        'user_id',
        'favorite',
        'front',
        'back',
    ];

    protected $casts = [
        'favorite' => BooleanEnum::class,
    ];

    // ══════════════════════════════════════════════════════════════════════════
    // RELATIONSHIPS
    // ══════════════════════════════════════════════════════════════════════════

    /** Get all Leitner progress records for this card (all users) */
    public function leitnerLogs(): HasMany
    {
        return $this->hasMany(LeitnerBox::class);
    }

    /** Get Leitner progress for a specific user */
    public function leitnerProgress(): HasOne
    {
        return $this->hasOne(LeitnerBox::class);
    }

    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }
    // ══════════════════════════════════════════════════════════════════════════
    // SCOPES
    // ══════════════════════════════════════════════════════════════════════════

    /** Scope to filter favorites */
    public function scopeFavorites(Builder $query): Builder
    {
        return $query->where('favorite', BooleanEnum::ENABLE);
    }

    /** Scope to search in front and back content */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('front', 'like', "%{$term}%")
                ->orWhere('back', 'like', "%{$term}%");
        });
    }

    /** Scope to get cards that are due for a user */
    public function scopeDueForUser(Builder $query, int $userId): Builder
    {
        return $query->whereHas('leitnerLogs', function ($q) use ($userId) {
            $q->where('user_id', $userId)
                ->where('next_review_at', '<=', now());
        });
    }

    /** Scope to get cards that are new for a user (no Leitner record) */
    public function scopeNewForUser(Builder $query, int $userId): Builder
    {
        return $query->whereDoesntHave('leitnerLogs', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /** Scope to get cards available for study today (new or due) */
    public function scopeAvailableForStudy(Builder $query, int $userId): Builder
    {
        return $query->where(function ($q) use ($userId) {
            // New cards (no Leitner record for this user)
            $q->whereDoesntHave('leitnerLogs', function ($subQ) use ($userId) {
                $subQ->where('user_id', $userId);
            })
            // OR due cards
                ->orWhereHas('leitnerLogs', function ($subQ) use ($userId) {
                    $subQ->where('user_id', $userId)
                        ->where('next_review_at', '<=', now());
                });
        });
    }

    /** Scope to eager load Leitner progress for a specific user */
    public function scopeWithLeitnerForUser(Builder $query, int $userId): Builder
    {
        return $query->with(['leitnerProgress' => function ($q) use ($userId) {
            $q->where('user_id', $userId);
        }]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // INSTANCE METHODS
    // ══════════════════════════════════════════════════════════════════════════

    /** Get Leitner progress for a specific user */
    public function getLeitnerForUser(int $userId): ?LeitnerBox
    {
        return $this->leitnerLogs()->where('user_id', $userId)->first();
    }

    /** Get or create Leitner progress for a user */
    public function getOrCreateLeitnerForUser(int $userId): LeitnerBox
    {
        return LeitnerBox::getOrCreateForCard($this->id, $userId);
    }

    /** Check if this card is new for a user (no Leitner record) */
    public function isNewForUser(int $userId): bool
    {
        return ! $this->leitnerLogs()->where('user_id', $userId)->exists();
    }

    /** Check if this card is due for review for a user */
    public function isDueForUser(int $userId): bool
    {
        $leitner = $this->getLeitnerForUser($userId);

        return $leitner && $leitner->isDue();
    }

    /** Check if this card is finished/mastered for a user */
    public function isFinishedForUser(int $userId): bool
    {
        $leitner = $this->getLeitnerForUser($userId);

        return $leitner && $leitner->isFinished();
    }

    /** Check if this card is available for study today for a user */
    public function isAvailableForStudy(int $userId): bool
    {
        return $this->isNewForUser($userId) || $this->isDueForUser($userId);
    }

    /** Get the current Leitner box for a user */
    public function getLeitnerBoxForUser(int $userId): int
    {
        $leitner = $this->getLeitnerForUser($userId);

        return $leitner?->box ?? 0;
    }

    /** Get next review date for a user */
    public function getNextReviewForUser(int $userId): ?\Carbon\Carbon
    {
        $leitner = $this->getLeitnerForUser($userId);

        return $leitner?->next_review_at;
    }

    /** Mark this card as known for a user */
    public function markKnownForUser(int $userId): LeitnerBox
    {
        $leitner = $this->getOrCreateLeitnerForUser($userId);

        return $leitner->markKnown();
    }

    /** Mark this card as unknown for a user */
    public function markUnknownForUser(int $userId): LeitnerBox
    {
        $leitner = $this->getOrCreateLeitnerForUser($userId);

        return $leitner->markUnknown();
    }

    /** Reset Leitner progress for a user */
    public function resetProgressForUser(int $userId): bool
    {
        return $this->leitnerLogs()
            ->where('user_id', $userId)
            ->delete() > 0;
    }

    /** Toggle favorite status */
    public function toggleFavorite(): self
    {
        $this->update([
            'favorite' => $this->favorite === BooleanEnum::ENABLE
                ? BooleanEnum::DISABLE
                : BooleanEnum::ENABLE,
        ]);

        return $this;
    }
}
