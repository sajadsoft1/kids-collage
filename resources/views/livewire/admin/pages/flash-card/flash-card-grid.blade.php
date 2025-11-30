<div class="min-h-screen" x-data="flashCardApp()" x-init="init()"
    @keydown.window="handleKeydown($event)">

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- BREADCRUMBS --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    @include('components.admin.shared.bread-crumbs')

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- STATISTICS SECTION --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-3 mb-6 md:grid-cols-3 lg:grid-cols-6">
        <x-stat title="{{ __('flashCard.stats.total') }}" value="{{ $this->stats['total'] }}"
            icon="o-rectangle-stack" color="text-primary"
            class="border border-primary/20 bg-gradient-to-br from-primary/5 to-primary/10" />

        <x-stat title="{{ __('flashCard.stats.due') }}" value="{{ $this->stats['due'] }}"
            icon="o-clock" color="text-warning"
            class="border border-warning/20 bg-gradient-to-br from-warning/5 to-warning/10 {{ $this->stats['due'] > 0 ? 'animate-pulse' : '' }}" />

        <x-stat title="{{ __('flashCard.stats.new') }}" value="{{ $this->stats['new'] }}"
            icon="o-sparkles" color="text-info"
            class="border border-info/20 bg-gradient-to-br from-info/5 to-info/10" />

        <x-stat title="{{ __('flashCard.stats.in_progress') }}" value="{{ $this->stats['in_progress'] }}"
            icon="o-arrow-path" color="text-secondary"
            class="border border-secondary/20 bg-gradient-to-br from-secondary/5 to-secondary/10" />

        <x-stat title="{{ __('flashCard.stats.mastered') }}" value="{{ $this->stats['mastered'] }}"
            icon="o-academic-cap" color="text-success"
            class="border border-success/20 bg-gradient-to-br from-success/5 to-success/10" />

        <x-stat title="{{ __('flashCard.stats.favorites') }}" value="{{ $this->stats['favorites'] }}"
            icon="o-heart" color="text-error"
            class="border border-error/20 bg-gradient-to-br from-error/5 to-error/10" />
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- LEITNER BOX PROGRESS --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    @if($this->stats['total'] > 0)
        <div class="card bg-base-100 shadow-md border border-base-200 mb-6">
            <div class="card-body p-4">
                <h3 class="text-sm font-semibold text-base-content/70 mb-3 flex items-center gap-2">
                    <x-icon name="o-chart-bar" class="w-4 h-4" />
                    {{ __('flashCard.leitner.progress') }}
                </h3>
                <div class="flex gap-2 items-end h-20">
                    @foreach($this->boxDistribution as $box => $count)
                        @php
                            $maxCount = max($this->boxDistribution) ?: 1;
                            $height = ($count / $maxCount) * 100;
                            $colors = [
                                0 => 'bg-base-300',
                                1 => 'bg-error',
                                2 => 'bg-warning',
                                3 => 'bg-info',
                                4 => 'bg-primary',
                                5 => 'bg-success',
                            ];
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <span class="text-xs font-medium text-base-content/60">{{ $count }}</span>
                            <div class="w-full rounded-t-lg transition-all duration-500 {{ $colors[$box] }}"
                                 style="height: {{ max($height, 10) }}%"></div>
                            <span class="text-xs text-base-content/50">
                                @if($box === 0)
                                    {{ __('flashCard.leitner.not_started') }}
                                @else
                                    {{ __('flashCard.leitner.box') }} {{ $box }}
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- TOOLBAR --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
        {{-- Search & Filter --}}
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center flex-1">
            <x-input wire:model.live.debounce.300ms="search" icon="o-magnifying-glass"
                placeholder="{{ __('flashCard.search_placeholder') }}"
                class="w-full sm:w-72" clearable />

            <x-select wire:model.live="filter" :options="[
                ['id' => 'all', 'name' => __('flashCard.filter.all')],
                ['id' => 'favorites', 'name' => __('flashCard.filter.favorites')],
                ['id' => 'due', 'name' => __('flashCard.filter.due')],
                ['id' => 'new', 'name' => __('flashCard.filter.new')],
            ]" class="w-full sm:w-40" />
        </div>

        {{-- Mode Toggle --}}
        <div class="join">
            <button wire:click="switchMode('grid')"
                class="btn join-item {{ $mode === 'grid' ? 'btn-primary' : 'btn-ghost' }}">
                <x-icon name="o-squares-2x2" class="w-5 h-5" />
                <span class="hidden sm:inline">{{ __('flashCard.mode.grid') }}</span>
            </button>
            <button wire:click="switchMode('study')"
                class="btn join-item {{ $mode === 'study' ? 'btn-primary' : 'btn-ghost' }}"
                @if($this->studyCards->isEmpty()) disabled @endif>
                <x-icon name="o-book-open" class="w-5 h-5" />
                <span class="hidden sm:inline">{{ __('flashCard.mode.study') }}</span>
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- GRID VIEW MODE --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    @if($mode === 'grid')
        @if($this->flashCards->isNotEmpty())
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($this->flashCards as $card)
                    <div wire:key="card-{{ $card->id }}"
                         class="group card bg-base-100 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-base-200 cursor-pointer"
                         wire:click="startStudy({{ $card->id }})">

                        {{-- Card Status Bar --}}
                        <div class="h-1.5 rounded-t-2xl transition-all duration-300
                            @if($card->is_finished) bg-success
                            @elseif($card->is_due) bg-warning
                            @elseif($card->is_new) bg-info
                            @else bg-primary/50
                            @endif">
                        </div>

                        <div class="card-body p-4">
                            {{-- Header --}}
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <h3 class="font-bold text-base line-clamp-2 group-hover:text-primary transition-colors">
                                    {{ $card->title }}
                                </h3>
                                <button wire:click.stop="toggleFavorite({{ $card->id }})"
                                    class="btn btn-ghost btn-xs btn-circle">
                                    <x-icon name="{{ $card->favorite === \App\Enums\BooleanEnum::ENABLE ? 's-heart' : 'o-heart' }}"
                                        class="w-4 h-4 {{ $card->favorite === \App\Enums\BooleanEnum::ENABLE ? 'text-error' : 'text-base-content/40' }}" />
                                </button>
                            </div>

                            {{-- Front Preview --}}
                            <div class="text-sm text-base-content/70 line-clamp-3 mb-3 min-h-[3.75rem]">
                                {!! Str::limit(strip_tags($card->front), 100) !!}
                            </div>

                            {{-- Badges --}}
                            <div class="flex flex-wrap gap-1.5">
                                {{-- Leitner Box --}}
                                @if($card->leitner_box > 0)
                                    <x-badge :value="__('flashCard.leitner.box') . ' ' . $card->leitner_box"
                                        class="badge-sm {{ $card->leitner_box >= 4 ? 'badge-success' : ($card->leitner_box >= 2 ? 'badge-primary' : 'badge-warning') }}" />
                                @endif

                                {{-- Status --}}
                                @if($card->is_finished)
                                    <x-badge :value="__('flashCard.status.mastered')" class="badge-sm badge-success" icon="o-academic-cap" />
                                @elseif($card->is_due)
                                    <x-badge :value="__('flashCard.status.due')" class="badge-sm badge-warning animate-pulse" icon="o-clock" />
                                @elseif($card->is_new)
                                    <x-badge :value="__('flashCard.status.new')" class="badge-sm badge-info" icon="o-sparkles" />
                                @endif

                                {{-- Languages --}}
                                @if($card->languages)
                                    @foreach($card->languages as $lang)
                                        <x-badge :value="__('general.languages.' . $lang)" class="badge-sm badge-ghost" />
                                    @endforeach
                                @endif
                            </div>

                            {{-- Next Review --}}
                            @if($card->next_review && !$card->is_finished)
                                <div class="mt-3 pt-3 border-t border-base-200 text-xs text-base-content/50 flex items-center gap-1">
                                    <x-icon name="o-calendar" class="w-3.5 h-3.5" />
                                    {{ __('flashCard.next_review') }}: {{ $card->next_review->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <x-card class="!p-8 md:!p-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-24 h-24 md:w-32 md:h-32 mb-4 md:mb-6 rounded-full bg-gradient-to-br from-primary/20 to-base-300 flex items-center justify-center">
                        <x-icon name="o-rectangle-stack" class="w-12 h-12 md:w-16 md:h-16 text-primary/50" />
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-base-content/70 mb-2">
                        {{ __('flashCard.empty.title') }}
                    </h3>
                    <p class="text-sm md:text-base text-base-content/50 max-w-md mb-6">
                        {{ __('flashCard.empty.description') }}
                    </p>
                    <x-button :link="route('admin.flash-card.create')" class="btn-primary" icon="s-plus">
                        {{ __('flashCard.empty.create_first') }}
                    </x-button>
                </div>
            </x-card>
        @endif
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- STUDY MODE --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    @if($mode === 'study')
        @if($this->currentCard)
            <div class="flex flex-col items-center">
                {{-- Progress Bar --}}
                <div class="w-full max-w-2xl mb-6">
                    <div class="flex items-center justify-between text-sm text-base-content/60 mb-2">
                        <span>{{ __('flashCard.study.progress') }}</span>
                        <span>{{ $currentIndex + 1 }} / {{ $this->studyCards->count() }}</span>
                    </div>
                    <div class="h-2 bg-base-200 rounded-full overflow-hidden">
                        <div class="h-full bg-primary transition-all duration-500 rounded-full"
                             style="width: {{ (($currentIndex + 1) / max($this->studyCards->count(), 1)) * 100 }}%"></div>
                    </div>
                </div>

                {{-- Card Navigation Dots --}}
                <div class="flex gap-1 mb-6 flex-wrap justify-center max-w-2xl">
                    @foreach($this->studyCards->take(20) as $idx => $card)
                        <button wire:click="goToCard({{ $idx }})"
                            class="w-2.5 h-2.5 rounded-full transition-all duration-200
                                {{ $idx === $currentIndex ? 'bg-primary scale-125' : 'bg-base-300 hover:bg-primary/50' }}
                                {{ $card->is_finished ? 'ring-2 ring-success ring-offset-1' : '' }}">
                        </button>
                    @endforeach
                    @if($this->studyCards->count() > 20)
                        <span class="text-xs text-base-content/50 self-center">+{{ $this->studyCards->count() - 20 }}</span>
                    @endif
                </div>

                {{-- Flash Card Container --}}
                <div class="flip-container w-full max-w-2xl mb-8" wire:ignore.self>
                    <div class="flip-card aspect-[4/3] md:aspect-[3/2] cursor-pointer {{ $isFlipped ? 'is-flipped' : '' }}"
                         @click="flip()"
                         x-bind:class="{ 'is-flipped': isFlipped }">

                        {{-- Front Side --}}
                        <div class="flip-card-face flip-card-front">
                            <div class="card bg-gradient-to-br from-primary/10 via-base-100 to-secondary/10 shadow-2xl border-2 border-primary/20 h-full">
                                <div class="card-body flex flex-col items-center justify-center p-6 md:p-10 relative">
                                    {{-- Card Header --}}
                                    <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
                                        <x-badge :value="$this->currentCard->title" class="badge-primary badge-lg" />
                                        <div class="flex gap-2">
                                            @if($this->currentCard->is_due)
                                                <x-badge value="{{ __('flashCard.status.due') }}" class="badge-warning" icon="o-clock" />
                                            @elseif($this->currentCard->is_new)
                                                <x-badge value="{{ __('flashCard.status.new') }}" class="badge-info" icon="o-sparkles" />
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Front Content --}}
                                    <div class="text-center text-xl md:text-2xl lg:text-3xl font-medium leading-relaxed prose prose-lg max-w-none">
                                        {!! $this->currentCard->front !!}
                                    </div>

                                    {{-- Flip Hint --}}
                                    <div class="absolute bottom-4 flex items-center gap-2 text-sm text-base-content/40">
                                        <x-icon name="o-arrow-path" class="w-4 h-4" />
                                        {{ __('flashCard.study.tap_to_flip') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Back Side --}}
                        <div class="flip-card-face flip-card-back">
                            <div class="card bg-gradient-to-br from-success/10 via-base-100 to-info/10 shadow-2xl border-2 border-success/20 h-full">
                                <div class="card-body flex flex-col items-center justify-center p-6 md:p-10 relative">
                                    {{-- Card Header --}}
                                    <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
                                        <x-badge :value="__('flashCard.study.answer')" class="badge-success badge-lg" />
                                        <button wire:click.stop="toggleFavorite({{ $this->currentCard->id }})"
                                            class="btn btn-ghost btn-sm btn-circle">
                                            <x-icon name="{{ $this->currentCard->favorite === \App\Enums\BooleanEnum::ENABLE ? 's-heart' : 'o-heart' }}"
                                                class="w-5 h-5 {{ $this->currentCard->favorite === \App\Enums\BooleanEnum::ENABLE ? 'text-error' : 'text-base-content/40' }}" />
                                        </button>
                                    </div>

                                    {{-- Back Content --}}
                                    <div class="text-center text-xl md:text-2xl lg:text-3xl font-medium leading-relaxed prose prose-lg max-w-none">
                                        {!! $this->currentCard->back !!}
                                    </div>

                                    {{-- Flip Hint --}}
                                    <div class="absolute bottom-4 flex items-center gap-2 text-sm text-base-content/40">
                                        <x-icon name="o-arrow-path" class="w-4 h-4" />
                                        {{ __('flashCard.study.tap_to_flip') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Control Buttons --}}
                <div class="flex flex-col items-center gap-4 w-full max-w-2xl">
                    {{-- Navigation --}}
                    <div class="flex items-center justify-center gap-4 w-full">
                        <button wire:click="prevCard" class="btn btn-circle btn-lg btn-ghost"
                            @if($currentIndex === 0) disabled @endif>
                            <x-icon name="o-chevron-right" class="w-6 h-6" />
                        </button>

                        <button @click="flip()" class="btn btn-circle btn-lg btn-primary">
                            <x-icon name="o-arrow-path" class="w-6 h-6" />
                        </button>

                        <button wire:click="nextCard" class="btn btn-circle btn-lg btn-ghost"
                            @if($currentIndex >= $this->studyCards->count() - 1) disabled @endif>
                            <x-icon name="o-chevron-left" class="w-6 h-6" />
                        </button>
                    </div>

                    {{-- Rating Buttons (shown when flipped) --}}
                    <div class="flex gap-3 w-full justify-center transition-all duration-300"
                         :class="{ 'opacity-100 translate-y-0': isFlipped, 'opacity-0 translate-y-4 pointer-events-none': !isFlipped }">
                        <button wire:click="markUnknown" wire:loading.attr="disabled"
                            class="btn btn-error btn-lg flex-1 max-w-[200px] gap-2">
                            <x-icon name="o-x-mark" class="w-5 h-5" />
                            {{ __('flashCard.study.didnt_know') }}
                        </button>
                        <button wire:click="markKnown" wire:loading.attr="disabled"
                            class="btn btn-success btn-lg flex-1 max-w-[200px] gap-2">
                            <x-icon name="o-check" class="w-5 h-5" />
                            {{ __('flashCard.study.knew_it') }}
                        </button>
                    </div>

                    {{-- Keyboard Shortcuts Help --}}
                    <div class="text-xs text-base-content/40 flex flex-wrap justify-center gap-4 mt-4">
                        <span><kbd class="kbd kbd-xs">Space</kbd> {{ __('flashCard.shortcuts.flip') }}</span>
                        <span><kbd class="kbd kbd-xs">←</kbd> {{ __('flashCard.shortcuts.prev') }}</span>
                        <span><kbd class="kbd kbd-xs">→</kbd> {{ __('flashCard.shortcuts.next') }}</span>
                        <span><kbd class="kbd kbd-xs">1</kbd> {{ __('flashCard.shortcuts.unknown') }}</span>
                        <span><kbd class="kbd kbd-xs">2</kbd> {{ __('flashCard.shortcuts.known') }}</span>
                    </div>
                </div>
            </div>
        @else
            {{-- Study Complete --}}
            <x-card class="!p-8 md:!p-12 text-center max-w-lg mx-auto">
                <div class="flex flex-col items-center">
                    <div class="w-24 h-24 md:w-32 md:h-32 mb-4 md:mb-6 rounded-full bg-gradient-to-br from-success/20 to-base-300 flex items-center justify-center animate-bounce">
                        <x-icon name="o-trophy" class="w-12 h-12 md:w-16 md:h-16 text-success" />
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-success mb-2">
                        {{ __('flashCard.study.complete_title') }}
                    </h3>
                    <p class="text-sm md:text-base text-base-content/50 max-w-md mb-6">
                        {{ __('flashCard.study.complete_description') }}
                    </p>
                    <div class="flex gap-3">
                        <x-button wire:click="switchMode('grid')" class="btn-outline btn-primary" icon="o-squares-2x2">
                            {{ __('flashCard.mode.grid') }}
                        </x-button>
                        <x-button :link="route('admin.flash-card.create')" class="btn-primary" icon="s-plus">
                            {{ __('flashCard.empty.create_new') }}
                        </x-button>
                    </div>
                </div>
            </x-card>
        @endif
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- CSS STYLES --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <style>
        /* Flip Card Container */
        .flip-container {
            perspective: 1000px;
        }

        .flip-card {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .flip-card.is-flipped {
            transform: rotateY(180deg);
        }

        /* Card Faces */
        .flip-card-face {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            overflow: hidden;
            border-radius: 1rem;
        }

        .flip-card-front {
            z-index: 2;
        }

        .flip-card-back {
            transform: rotateY(180deg);
            z-index: 1;
        }

        /* RTL Support - flip direction reversed */
        [dir="rtl"] .flip-card.is-flipped {
            transform: rotateY(-180deg);
        }

        [dir="rtl"] .flip-card-back {
            transform: rotateY(-180deg);
        }
    </style>
</div>

@script
<script>
    Alpine.data('flashCardApp', () => ({
        isFlipped: @entangle('isFlipped'),

        init() {
            // Listen for card reviewed events
            Livewire.on('card-reviewed', ({ known }) => {
                this.isFlipped = false;
            });
        },

        flip() {
            this.isFlipped = !this.isFlipped;
        },

        handleKeydown(event) {
            // Only handle in study mode
            if ($wire.mode !== 'study') return;

            switch(event.code) {
                case 'Space':
                    event.preventDefault();
                    this.flip();
                    break;
                case 'ArrowLeft':
                    event.preventDefault();
                    $wire.nextCard();
                    break;
                case 'ArrowRight':
                    event.preventDefault();
                    $wire.prevCard();
                    break;
                case 'Digit1':
                case 'Numpad1':
                    if (this.isFlipped) {
                        event.preventDefault();
                        $wire.markUnknown();
                    }
                    break;
                case 'Digit2':
                case 'Numpad2':
                    if (this.isFlipped) {
                        event.preventDefault();
                        $wire.markKnown();
                    }
                    break;
            }
        }
    }));
</script>
@endscript
