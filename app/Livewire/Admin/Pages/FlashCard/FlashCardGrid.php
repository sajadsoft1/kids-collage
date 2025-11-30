<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\FlashCard;

use App\Models\FlashCard;
use App\Services\FlashCard\FlashCardService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('مطالعه فلش کارت‌ها')]
class FlashCardGrid extends Component
{
    // ══════════════════════════════════════════════════════════════════════════
    // PROPERTIES
    // ══════════════════════════════════════════════════════════════════════════

    #[Url]
    public string $search = '';

    #[Url]
    public string $filter = 'all'; // all, favorites, due, new

    public string $mode = 'grid'; // grid, study

    public int $currentIndex = 0;

    public bool $isFlipped = false;

    public ?int $studyingCardId = null;

    // ══════════════════════════════════════════════════════════════════════════
    // SERVICE
    // ══════════════════════════════════════════════════════════════════════════

    private function service(): FlashCardService
    {
        return app(FlashCardService::class);
    }

    private function getFilters(): array
    {
        return [
            'search' => $this->search,
            'filter' => $this->filter,
        ];
    }

    // ══════════════════════════════════════════════════════════════════════════
    // COMPUTED PROPERTIES
    // ══════════════════════════════════════════════════════════════════════════

    #[Computed]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['link' => route('admin.flash-card.index'), 'label' => __('flashCard.page.index.title')],
            ['label' => __('flashCard.page.study.title')],
        ];
    }

    #[Computed]
    public function breadcrumbsActions(): array
    {
        return [
            [
                'link' => route('admin.flash-card.index'),
                'icon' => 'o-table-cells',
                'label' => __('flashCard.page.index.table_view'),
            ],
            [
                'link' => route('admin.flash-card.create'),
                'icon' => 's-plus',
                'label' => __('general.page.create.title', ['model' => __('flashCard.model')]),
            ],
        ];
    }

    /** Get all flash cards for the current user with Leitner progress */
    #[Computed]
    public function flashCards(): Collection
    {
        return $this->service()->getCardsForUser(Auth::user(), $this->getFilters());
    }

    /** Get cards available for study TODAY based on Leitner system */
    #[Computed]
    public function studyCards(): Collection
    {
        return $this->service()->getStudyCardsForUser(Auth::user(), $this->getFilters());
    }

    /** Get cards that are NOT available for study today (scheduled for future) */
    #[Computed]
    public function scheduledCards(): Collection
    {
        return $this->service()->getScheduledCardsForUser(Auth::user(), $this->getFilters());
    }

    /** Get current card in study mode */
    #[Computed]
    public function currentCard(): ?FlashCard
    {
        if ($this->studyingCardId) {
            return $this->studyCards->firstWhere('id', $this->studyingCardId);
        }

        return $this->studyCards->get($this->currentIndex);
    }

    /** Get statistics for the dashboard */
    #[Computed]
    public function stats(): array
    {
        return $this->service()->getStatsForUser(Auth::user(), $this->getFilters());
    }

    /** Get box distribution for progress visualization */
    #[Computed]
    public function boxDistribution(): array
    {
        return $this->service()->getBoxDistributionForUser(Auth::user(), $this->getFilters());
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ACTIONS
    // ══════════════════════════════════════════════════════════════════════════

    /** Switch between grid and study mode */
    public function switchMode(string $mode): void
    {
        $this->mode = $mode;
        $this->currentIndex = 0;
        $this->isFlipped = false;
        $this->studyingCardId = null;
    }

    /** Start studying a specific card */
    public function startStudy(?int $cardId = null): void
    {
        $this->mode = 'study';
        $this->isFlipped = false;

        if ($cardId) {
            $this->studyingCardId = $cardId;
            $index = $this->studyCards->search(fn ($c) => $c->id === $cardId);
            $this->currentIndex = $index !== false ? $index : 0;
        }
    }

    /** Flip the current card */
    #[On('flip-card')]
    public function flipCard(): void
    {
        $this->isFlipped = ! $this->isFlipped;
    }

    /** Navigate to next card */
    #[On('next-card')]
    public function nextCard(): void
    {
        if ($this->currentIndex < $this->studyCards->count() - 1) {
            $this->currentIndex++;
            $this->isFlipped = false;
            $this->studyingCardId = $this->studyCards->get($this->currentIndex)?->id;
        }
    }

    /** Navigate to previous card */
    #[On('prev-card')]
    public function prevCard(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
            $this->isFlipped = false;
            $this->studyingCardId = $this->studyCards->get($this->currentIndex)?->id;
        }
    }

    /** Go to specific card index */
    public function goToCard(int $index): void
    {
        if ($index >= 0 && $index < $this->studyCards->count()) {
            $this->currentIndex = $index;
            $this->isFlipped = false;
            $this->studyingCardId = $this->studyCards->get($index)?->id;
        }
    }

    /** Mark card as known (move up in Leitner box) */
    #[On('mark-known')]
    public function markKnown(): void
    {
        $card = $this->currentCard;
        if ( ! $card) {
            return;
        }

        $this->service()->markCardKnown($card, Auth::id());

        $this->dispatch('card-reviewed', known: true);
        $this->advanceToNextCard();
    }

    /** Mark card as unknown (move to box 1) */
    #[On('mark-unknown')]
    public function markUnknown(): void
    {
        $card = $this->currentCard;
        if ( ! $card) {
            return;
        }

        $this->service()->markCardUnknown($card, Auth::id());

        $this->dispatch('card-reviewed', known: false);
        $this->advanceToNextCard();
    }

    /** Toggle favorite status */
    public function toggleFavorite(int $cardId): void
    {
        $card = FlashCard::find($cardId);
        if ( ! $card) {
            return;
        }

        $this->service()->toggleFavorite($card);

        $this->dispatch('favorite-toggled');
    }

    /** Reset Leitner progress for a card */
    public function resetProgress(int $cardId): void
    {
        $card = FlashCard::find($cardId);
        if ( ! $card) {
            return;
        }

        $this->service()->resetCardProgress($card, Auth::id());

        $this->dispatch('progress-reset');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // PRIVATE METHODS
    // ══════════════════════════════════════════════════════════════════════════

    /** Advance to the next card after review */
    private function advanceToNextCard(): void
    {
        $this->isFlipped = false;

        // Refresh the computed property
        unset($this->flashCards, $this->studyCards);

        if ($this->currentIndex >= $this->studyCards->count() - 1) {
            // End of cards, check if there are more due
            $this->currentIndex = 0;
        } else {
            $this->currentIndex++;
        }

        $this->studyingCardId = $this->studyCards->get($this->currentIndex)?->id;
    }

    // ══════════════════════════════════════════════════════════════════════════
    // RENDER
    // ══════════════════════════════════════════════════════════════════════════

    public function render()
    {
        return view('livewire.admin.pages.flash-card.flash-card-grid');
    }
}
