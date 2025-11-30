<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\FlashCard;

use App\Enums\BooleanEnum;
use App\Enums\UserTypeEnum;
use App\Models\FlashCard;
use App\Models\LeitnerBox;
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
        $user = Auth::user();

        return FlashCard::query()
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
            )
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('title', 'like', "%{$this->search}%")
                        ->orWhere('front', 'like', "%{$this->search}%")
                        ->orWhere('back', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filter === 'favorites', fn ($q) => $q->where('favorite', BooleanEnum::ENABLE))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (FlashCard $card) use ($user) {
                $leitner = LeitnerBox::where('flash_card_id', $card->id)
                    ->where('user_id', $user->id)
                    ->first();

                $card->leitner_box = $leitner?->box ?? 0;
                $card->is_due = $leitner && $leitner->next_review_at && $leitner->next_review_at->isPast();
                $card->is_new = ! $leitner;
                $card->is_finished = $leitner?->finished === BooleanEnum::ENABLE;
                $card->next_review = $leitner?->next_review_at;

                return $card;
            })
            ->when($this->filter === 'due', fn ($c) => $c->filter(fn ($card) => $card->is_due))
            ->when($this->filter === 'new', fn ($c) => $c->filter(fn ($card) => $card->is_new))
            ->values();
    }

    /** Get cards for study mode (due cards first, then new cards) */
    #[Computed]
    public function studyCards(): Collection
    {
        $cards = $this->flashCards;

        // Priority: Due cards first, then new cards, then by box level (ascending)
        return $cards->sortBy([
            fn ($a, $b) => ($b->is_due <=> $a->is_due),
            fn ($a, $b) => ($b->is_new <=> $a->is_new),
            fn ($a, $b) => ($a->leitner_box <=> $b->leitner_box),
        ])->values();
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
        $cards = $this->flashCards;

        return [
            'total' => $cards->count(),
            'favorites' => $cards->where('favorite', BooleanEnum::ENABLE)->count(),
            'due' => $cards->filter(fn ($c) => $c->is_due)->count(),
            'new' => $cards->filter(fn ($c) => $c->is_new)->count(),
            'mastered' => $cards->filter(fn ($c) => $c->is_finished)->count(),
            'in_progress' => $cards->filter(fn ($c) => ! $c->is_new && ! $c->is_finished)->count(),
        ];
    }

    /** Get box distribution for progress visualization */
    #[Computed]
    public function boxDistribution(): array
    {
        $cards = $this->flashCards;
        $distribution = [];

        for ($i = 0; $i <= 5; $i++) {
            $distribution[$i] = $cards->filter(fn ($c) => $c->leitner_box === $i)->count();
        }

        return $distribution;
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

        $user = Auth::user();
        $leitner = LeitnerBox::firstOrCreate(
            ['flash_card_id' => $card->id, 'user_id' => $user->id],
            ['box' => 0]
        );

        $newBox = min($leitner->box + 1, 5);
        $leitner->update([
            'box' => $newBox,
            'last_review_at' => now(),
            'next_review_at' => $this->calculateNextReview($newBox),
            'finished' => $newBox >= 5 ? BooleanEnum::ENABLE : BooleanEnum::DISABLE,
        ]);

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

        $user = Auth::user();
        $leitner = LeitnerBox::firstOrCreate(
            ['flash_card_id' => $card->id, 'user_id' => $user->id],
            ['box' => 0]
        );

        $leitner->update([
            'box' => 1,
            'last_review_at' => now(),
            'next_review_at' => $this->calculateNextReview(1),
            'finished' => BooleanEnum::DISABLE,
        ]);

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

        $card->update([
            'favorite' => $card->favorite === BooleanEnum::ENABLE
                ? BooleanEnum::DISABLE
                : BooleanEnum::ENABLE,
        ]);

        $this->dispatch('favorite-toggled');
    }

    /** Reset Leitner progress for a card */
    public function resetProgress(int $cardId): void
    {
        LeitnerBox::where('flash_card_id', $cardId)
            ->where('user_id', Auth::id())
            ->delete();

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

    /** Calculate next review date based on Leitner box */
    private function calculateNextReview(int $box): \Carbon\Carbon
    {
        $intervals = [
            1 => 1,    // Box 1: 1 day
            2 => 3,    // Box 2: 3 days
            3 => 7,    // Box 3: 1 week
            4 => 14,   // Box 4: 2 weeks
            5 => 30,   // Box 5: 1 month (mastered)
        ];

        return now()->addDays($intervals[$box] ?? 1);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // RENDER
    // ══════════════════════════════════════════════════════════════════════════

    public function render()
    {
        return view('livewire.admin.pages.flash-card.flash-card-grid');
    }
}
