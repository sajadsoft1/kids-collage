<?php

declare(strict_types=1);

namespace App\Services\FlashCard;

use App\Enums\BooleanEnum;
use App\Enums\UserTypeEnum;
use App\Models\FlashCard;
use App\Models\LeitnerBox;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Service class for Flash Card and Leitner system operations
 *
 * This service provides a centralized location for all flash card
 * and Leitner system logic, making it reusable across Livewire
 * components, API controllers, and other parts of the application.
 */
class FlashCardService
{
    // ══════════════════════════════════════════════════════════════════════════
    // CARD RETRIEVAL METHODS
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Get all flash cards for a user with their Leitner progress
     *
     * @param  User                  $user    The user to get cards for
     * @param  array                 $filters Optional filters: search, filter (all, favorites, due, new)
     * @return Collection<FlashCard>
     */
    public function getCardsForUser(User $user, array $filters = []): Collection
    {
        $query = FlashCard::query();

        // Apply user-based filtering
        $query = $this->applyUserFilter($query, $user);

        // Apply search filter
        if ( ! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Apply favorites filter
        if (($filters['filter'] ?? '') === 'favorites') {
            $query->favorites();
        }

        // Order by creation date
        $query->orderBy('created_at', 'desc');

        // Get cards and map Leitner progress
        return $query->get()->map(fn (FlashCard $card) => $this->mapLeitnerProgress($card, $user->id));
    }

    /**
     * Get cards available for study TODAY based on Leitner system
     *
     * @param  User                  $user    The user to get study cards for
     * @param  array                 $filters Optional filters
     * @return Collection<FlashCard>
     */
    public function getStudyCardsForUser(User $user, array $filters = []): Collection
    {
        $cards = $this->getCardsForUser($user, $filters);

        // Filter only cards that can be studied today
        $availableCards = $cards->filter(function ($card) {
            return $card->is_new || $card->is_due;
        });

        // Apply additional filter if needed
        if (($filters['filter'] ?? '') === 'due') {
            $availableCards = $availableCards->filter(fn ($card) => $card->is_due);
        } elseif (($filters['filter'] ?? '') === 'new') {
            $availableCards = $availableCards->filter(fn ($card) => $card->is_new);
        }

        // Priority: Due cards first, then new cards, then by box level (ascending)
        return $availableCards->sortBy([
            fn ($a, $b) => ($b->is_due <=> $a->is_due),
            fn ($a, $b) => ($b->is_new <=> $a->is_new),
            fn ($a, $b) => ($a->leitner_box <=> $b->leitner_box),
        ])->values();
    }

    /**
     * Get cards scheduled for future review
     *
     * @param  User                  $user    The user to get scheduled cards for
     * @param  array                 $filters Optional filters
     * @return Collection<FlashCard>
     */
    public function getScheduledCardsForUser(User $user, array $filters = []): Collection
    {
        $cards = $this->getCardsForUser($user, $filters);

        return $cards->filter(function ($card) {
            if ($card->is_new || $card->is_due) {
                return false;
            }

            return $card->next_review && $card->next_review->isFuture();
        })->sortBy('next_review')->values();
    }

    // ══════════════════════════════════════════════════════════════════════════
    // STATISTICS METHODS
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Get statistics for a user's flash cards
     *
     * @param  User               $user    The user to get stats for
     * @param  array              $filters Optional filters
     * @return array<string, int>
     */
    public function getStatsForUser(User $user, array $filters = []): array
    {
        $cards = $this->getCardsForUser($user, $filters);
        $studyCards = $this->getStudyCardsForUser($user, $filters);
        $scheduledCards = $this->getScheduledCardsForUser($user, $filters);

        return [
            'total' => $cards->count(),
            'favorites' => $cards->where('favorite', BooleanEnum::ENABLE)->count(),
            'due' => $cards->filter(fn ($c) => $c->is_due)->count(),
            'new' => $cards->filter(fn ($c) => $c->is_new)->count(),
            'mastered' => $cards->filter(fn ($c) => $c->is_finished)->count(),
            'in_progress' => $cards->filter(fn ($c) => ! $c->is_new && ! $c->is_finished)->count(),
            'available_today' => $studyCards->count(),
            'scheduled' => $scheduledCards->count(),
        ];
    }

    /**
     * Get box distribution for progress visualization
     *
     * @param  User            $user    The user to get distribution for
     * @param  array           $filters Optional filters
     * @return array<int, int>
     */
    public function getBoxDistributionForUser(User $user, array $filters = []): array
    {
        $cards = $this->getCardsForUser($user, $filters);
        $distribution = [];

        for ($i = 0; $i <= LeitnerBox::MAX_BOX; $i++) {
            $distribution[$i] = $cards->filter(fn ($c) => $c->leitner_box === $i)->count();
        }

        return $distribution;
    }

    // ══════════════════════════════════════════════════════════════════════════
    // REVIEW METHODS
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Mark a card as known (move up in Leitner box)
     *
     * @param  FlashCard  $card   The card to mark
     * @param  int        $userId The user ID
     * @return LeitnerBox The updated Leitner record
     */
    public function markCardKnown(FlashCard $card, int $userId): LeitnerBox
    {
        return $card->markKnownForUser($userId);
    }

    /**
     * Mark a card as unknown (move to box 1)
     *
     * @param  FlashCard  $card   The card to mark
     * @param  int        $userId The user ID
     * @return LeitnerBox The updated Leitner record
     */
    public function markCardUnknown(FlashCard $card, int $userId): LeitnerBox
    {
        return $card->markUnknownForUser($userId);
    }

    /**
     * Reset progress for a card
     *
     * @param  FlashCard $card   The card to reset
     * @param  int       $userId The user ID
     * @return bool      Whether the reset was successful
     */
    public function resetCardProgress(FlashCard $card, int $userId): bool
    {
        return $card->resetProgressForUser($userId);
    }

    /**
     * Toggle favorite status for a card
     *
     * @param  FlashCard $card The card to toggle
     * @return FlashCard The updated card
     */
    public function toggleFavorite(FlashCard $card): FlashCard
    {
        return $card->toggleFavorite();
    }

    // ══════════════════════════════════════════════════════════════════════════
    // STATIC/UTILITY METHODS
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Get Leitner box intervals
     *
     * @return array<int, array{days: int, label: string}>
     */
    public static function getLeitnerIntervals(): array
    {
        return LeitnerBox::getIntervals();
    }

    /**
     * Get Leitner interval constants
     *
     * @return array<int, int>
     */
    public static function getLeitnerIntervalDays(): array
    {
        return LeitnerBox::INTERVALS;
    }

    // ══════════════════════════════════════════════════════════════════════════
    // PRIVATE HELPER METHODS
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Apply user-based filtering to query
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyUserFilter($query, User $user)
    {
        return $query
            ->when(
                $user->type === UserTypeEnum::PARENT,
                function ($q) use ($user) {
                    $children = $user->children->pluck('id')->toArray();
                    $q->whereIn('user_id', [...$children, $user->id]);
                }
            )
            ->when(
                in_array($user->type, [UserTypeEnum::EMPLOYEE, UserTypeEnum::USER]),
                fn ($q) => $q->where('user_id', $user->id)
            );
    }

    /**
     * Map Leitner progress attributes to a flash card
     *
     * @param  FlashCard $card   The card to map progress to
     * @param  int       $userId The user ID
     * @return FlashCard The card with Leitner attributes
     */
    private function mapLeitnerProgress(FlashCard $card, int $userId): FlashCard
    {
        $leitner = $card->getLeitnerForUser($userId);

        $card->leitner_box = $leitner?->box ?? 0;
        $card->is_due = $leitner && $leitner->isDue();
        $card->is_new = ! $leitner;
        $card->is_finished = $leitner?->isFinished() ?? false;
        $card->next_review = $leitner?->next_review_at;

        return $card;
    }
}
