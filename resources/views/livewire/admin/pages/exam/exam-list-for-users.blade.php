@php
    $attemptStatusCompleted = \App\Enums\AttemptStatusEnum::COMPLETED;
@endphp
<div class="py-4 md:py-6" x-data="{ activeTab: @entangle('activeTab') }">
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- HEADER SECTION --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col gap-4">
            <div>
                <h1 class="text-2xl font-bold md:text-3xl">
                    {{ __('exam_list.title') }}
                </h1>
                <p class="mt-1 text-sm md:text-base text-base-content/60">{{ __('exam_list.subtitle') }}</p>
            </div>
            {{-- Search Box --}}
            <x-input wire:model.live.debounce.300ms="search" icon="o-magnifying-glass"
                placeholder="{{ __('exam_list.search_placeholder') }}" class="w-full" />
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- STATISTICS SECTION --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-3 mb-6 md:gap-4 md:mb-8 md:grid-cols-4">
        <x-stat title="{{ __('exam_list.stats.available') }}" value="{{ $this->stats['available_count'] }}"
            icon="o-clipboard-document-list" color="text-primary" class="border border-primary/20 bg-primary/5" />

        <x-stat title="{{ __('exam_list.stats.in_progress') }}" value="{{ $this->stats['in_progress_count'] }}"
            icon="o-play" color="text-info" class="border border-info/20 bg-info/5" />

        <x-stat title="{{ __('exam_list.stats.completed') }}" value="{{ $this->stats['completed_count'] }}"
            icon="o-check-circle" color="text-success" class="border border-success/20 bg-success/5" />

        <x-stat title="{{ __('exam_list.stats.average_score') }}"
            value="{{ number_format($this->stats['average_score'], 1) }}%" icon="o-chart-bar" color="text-warning"
            class="border border-warning/20 bg-warning/5" />
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- IN PROGRESS ALERT (فقط وقتی در تب دیگه هستیم) --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    @if ($this->inProgressAttempts->count() > 0 && $activeTab !== 'in_progress')
        <x-alert icon="o-information-circle" class="mb-6 alert-info md:mb-8" dismissible>
            <div class="flex justify-between items-center w-full">
                <div>
                    <span class="font-bold">{{ __('exam_list.in_progress_alert.title') }}</span>
                    <span
                        class="text-sm">{{ __('exam_list.in_progress_alert.description', ['count' => $this->inProgressAttempts->count()]) }}</span>
                </div>
                <x-button wire:click="switchTab('in_progress')" class="btn-info btn-sm" icon="o-arrow-left">
                    {{ __('exam_list.view_in_progress') }}
                </x-button>
            </div>
        </x-alert>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- TABS --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <x-tabs wire:model="activeTab" active-class="bg-primary rounded !text-white" label-class="px-4 py-3 font-semibold"
        label-div-class="p-2 rounded bg-base-100">

        {{-- TAB 1: AVAILABLE EXAMS --}}
        <x-tab name="available" :label="__('exam_list.tabs.available')" icon="o-clipboard-document-list">
            @if ($this->availableExams->count() > 0)
                <div class="grid grid-cols-1 gap-4 md:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($this->availableExams as $exam)
                        <div wire:key="exam-{{ $exam->id }}"
                            class="border shadow-md transition-all duration-300 card bg-base-100 hover:shadow-xl hover:-translate-y-1 border-base-200">

                            {{-- Card Body --}}
                            <div class="p-4 card-body md:p-5">
                                {{-- Header: Title & Badge --}}
                                <div class="flex gap-3 items-start mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="mb-1 text-base card-title md:text-lg line-clamp-2">
                                            {{ $exam->title }}
                                        </h3>
                                        @if ($exam->category)
                                            <span
                                                class="text-xs text-base-content/50">{{ $exam->category->title }}</span>
                                        @endif
                                    </div>
                                    <x-badge value="{{ __('exam_list.available') }}"
                                        class="badge-success badge-sm shrink-0" />
                                </div>

                                {{-- Description --}}
                                @if ($exam->description)
                                    <p class="mb-3 text-sm text-base-content/60 line-clamp-2">
                                        {{ $exam->description }}
                                    </p>
                                @endif

                                {{-- Stats Row --}}
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <div class="flex gap-1 items-center px-2 py-1 text-xs rounded-md bg-base-200">
                                        <x-icon name="o-document-text" class="w-3.5 h-3.5 text-primary" />
                                        <span>{{ $exam->questions_count }} {{ __('exam_list.questions') }}</span>
                                    </div>
                                    @if ($exam->duration)
                                        <div class="flex gap-1 items-center px-2 py-1 text-xs rounded-md bg-base-200">
                                            <x-icon name="o-clock" class="w-3.5 h-3.5 text-secondary" />
                                            <span>{{ $exam->duration }} {{ __('exam_list.minutes') }}</span>
                                        </div>
                                    @endif
                                    @if ($exam->total_score)
                                        <div class="flex gap-1 items-center px-2 py-1 text-xs rounded-md bg-base-200">
                                            <x-icon name="o-star" class="w-3.5 h-3.5 text-warning" />
                                            <span>{{ $exam->total_score }} {{ __('exam_list.points') }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Deadline Alert --}}
                                @if ($exam->ends_at)
                                    <div
                                        class="flex gap-2 items-center px-3 py-2 mb-4 text-xs rounded-lg text-warning bg-warning/10">
                                        <x-icon name="o-exclamation-triangle" class="w-4 h-4" />
                                        <span>{{ __('exam_list.ends_at') }}:
                                            {{ $exam->ends_at->diffForHumans() }}</span>
                                    </div>
                                @endif

                                {{-- Action Button --}}
                                <div class="mt-auto card-actions">
                                    <x-button :link="route('admin.exam.taker', ['exam' => $exam->id])" class="w-full btn-primary" icon="o-play" wire:navigate>
                                        {{ __('exam_list.start_exam') }}
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <x-card class="py-12 text-center md:py-16">
                    <div class="flex flex-col items-center">
                        <div
                            class="flex justify-center items-center mb-4 w-24 h-24 bg-gradient-to-br rounded-full md:w-32 md:h-32 md:mb-6 from-base-200 to-base-300">
                            <x-icon name="o-clipboard-document-list"
                                class="w-12 h-12 md:w-16 md:h-16 text-base-content/30" />
                        </div>
                        <h3 class="mb-2 text-lg font-bold md:text-xl text-base-content/70">
                            {{ __('exam_list.empty.available_title') }}</h3>
                        <p class="max-w-md text-sm text-center text-base-content/50 md:text-base">
                            {{ __('exam_list.empty.available_description') }}</p>
                    </div>
                </x-card>
            @endif
        </x-tab>

        {{-- TAB 2: IN PROGRESS EXAMS --}}
        <x-tab name="in_progress" :label="__('exam_list.tabs.in_progress')" icon="o-play">
            @if ($this->inProgressAttempts->count() > 0)
                <div class="grid grid-cols-1 gap-4 md:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($this->inProgressAttempts as $attempt)
                        <div wire:key="in-progress-{{ $attempt->id }}"
                            class="border-2 shadow-md transition-all duration-300 card bg-base-100 hover:shadow-xl border-info/30">

                            {{-- Progress Bar Header --}}
                            <div class="overflow-hidden h-2 rounded-t-2xl bg-base-200">
                                <div class="h-full transition-all duration-500 bg-info"
                                    style="width: {{ $attempt->getProgressPercentage() }}%"></div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-4 card-body md:p-5">
                                {{-- Header: Title & Badge --}}
                                <div class="flex gap-3 items-start mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="mb-1 text-base card-title md:text-lg line-clamp-2">
                                            {{ $attempt->exam->title }}
                                        </h3>
                                        @if ($attempt->exam->category)
                                            <span
                                                class="text-xs text-base-content/50">{{ $attempt->exam->category->title }}</span>
                                        @endif
                                    </div>
                                    <x-badge value="{{ __('exam_list.in_progress') }}"
                                        class="animate-pulse badge-info badge-sm shrink-0" />
                                </div>

                                {{-- Progress Info --}}
                                <div class="flex justify-between items-center p-3 mb-4 rounded-lg bg-info/10">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-info">
                                            {{ $attempt->getProgressPercentage() }}%</div>
                                        <div class="text-xs text-base-content/60">{{ __('exam_list.progress') }}</div>
                                    </div>
                                    <div class="divider divider-horizontal"></div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-base-content">
                                            {{ $attempt->answers_count ?? 0 }}</div>
                                        <div class="text-xs text-base-content/60">از
                                            {{ $attempt->exam->questions_count }} {{ __('exam_list.questions') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Time Info --}}
                                <div class="mb-4 space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-base-content/60">{{ __('exam_list.started_at') }}</span>
                                        <span
                                            class="font-medium">{{ $attempt->started_at?->format('Y/m/d H:i') }}</span>
                                    </div>
                                    @if ($attempt->getRemainingTime())
                                        <div class="flex justify-between items-center">
                                            <span
                                                class="text-base-content/60">{{ __('exam_list.remaining_time') }}</span>
                                            <x-badge :value="gmdate('H:i:s', $attempt->getRemainingTime())" class="font-mono badge-warning badge-sm"
                                                icon="o-clock" />
                                        </div>
                                    @endif
                                </div>

                                {{-- Action Button --}}
                                <div class="mt-auto card-actions">
                                    <x-button :link="route('admin.exam.taker', [
                                        'exam' => $attempt->exam->id,
                                        'attempt' => $attempt->id,
                                    ])" class="w-full btn-info" icon="o-play" wire:navigate>
                                        {{ __('exam_list.continue_exam') }}
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <x-card class="py-12 text-center md:py-16">
                    <div class="flex flex-col items-center">
                        <div
                            class="flex justify-center items-center mb-4 w-24 h-24 bg-gradient-to-br rounded-full md:w-32 md:h-32 md:mb-6 from-info/20 to-base-300">
                            <x-icon name="o-play" class="w-12 h-12 md:w-16 md:h-16 text-info/50" />
                        </div>
                        <h3 class="mb-2 text-lg font-bold md:text-xl text-base-content/70">
                            {{ __('exam_list.empty.in_progress_title') }}
                        </h3>
                        <p class="max-w-md text-sm text-center text-base-content/50 md:text-base">
                            {{ __('exam_list.empty.in_progress_description') }}
                        </p>
                        <x-button wire:click="switchTab('available')" class="mt-4 btn-primary"
                            icon="o-clipboard-document-list">
                            {{ __('exam_list.browse_exams') }}
                        </x-button>
                    </div>
                </x-card>
            @endif
        </x-tab>

        {{-- TAB 3: COMPLETED EXAMS --}}
        <x-tab name="completed" :label="__('exam_list.tabs.completed')" icon="o-check-circle">
            @if ($this->completedAttempts->count() > 0)
                <div class="grid grid-cols-1 gap-4 md:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($this->completedAttempts as $attempt)
                        <div wire:key="attempt-{{ $attempt->id }}"
                            class="border shadow-md transition-all duration-300 card bg-base-100 hover:shadow-xl border-base-200">

                            {{-- Status Color Bar --}}
                            <div
                                class="h-1 {{ $attempt->isPassed() === true ? 'bg-success' : ($attempt->isPassed() === false ? 'bg-error' : 'bg-base-300') }} rounded-t-2xl">
                            </div>

                            {{-- Card Body --}}
                            <div class="p-4 card-body md:p-5">
                                {{-- Header --}}
                                <div class="flex gap-3 items-start mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="mb-1 text-base card-title md:text-lg line-clamp-2">
                                            {{ $attempt->exam->title }}
                                        </h3>
                                        <x-badge :value="$attempt->status->title()"
                                            class="badge-xs {{ $attempt->status->bgColor() }}" />
                                    </div>
                                    @if ($attempt->isPassed() !== null)
                                        <x-badge :value="$attempt->isPassed()
                                            ? __('exam_list.passed')
                                            : __('exam_list.failed')"
                                            class="badge-sm shrink-0 {{ $attempt->isPassed() ? 'badge-success' : 'badge-error' }}" />
                                    @endif
                                </div>

                                {{-- Score Circle --}}
                                @if ($attempt->percentage !== null)
                                    <div class="flex justify-center my-4">
                                        <div class="radial-progress {{ $attempt->isPassed() === true ? 'text-success' : ($attempt->isPassed() === false ? 'text-error' : 'text-primary') }}"
                                            style="--value:{{ $attempt->percentage }}; --size:5rem; --thickness:6px;"
                                            role="progressbar">
                                            <span
                                                class="text-lg font-bold">{{ number_format($attempt->percentage, 0) }}%</span>
                                        </div>
                                    </div>
                                @endif

                                {{-- Details --}}
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span
                                            class="text-base-content/60">{{ __('exam_list.questions_answered') }}</span>
                                        <span class="font-medium">{{ $attempt->getAnsweredQuestionsCount() }} /
                                            {{ $attempt->getTotalQuestionsCount() }}</span>
                                    </div>
                                    @if ($attempt->total_score !== null)
                                        <div class="flex justify-between">
                                            <span
                                                class="text-base-content/60">{{ __('exam_list.total_score') }}</span>
                                            <span class="font-medium">{{ number_format($attempt->total_score, 1) }} /
                                                {{ $attempt->exam->total_score }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-base-content/60">{{ __('exam_list.time_spent') }}</span>
                                        <span
                                            class="font-medium">{{ gmdate('H:i:s', $attempt->getElapsedTime()) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-base-content/60">{{ __('exam_list.completed_at') }}</span>
                                        <span
                                            class="text-xs font-medium">{{ $attempt->completed_at?->format('Y/m/d H:i') ?? '-' }}</span>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="pt-4 mt-4 border-t card-actions border-base-200">
                                    <div class="flex gap-2 w-full">
                                        <x-button :link="route('admin.exam.attempt.results', [
                                            'exam' => $attempt->exam->id,
                                            'attempt' => $attempt->id,
                                        ])" class="flex-1 btn-outline btn-primary btn-sm"
                                            icon="o-eye" wire:navigate>
                                            {{ __('exam_list.view_results') }}
                                        </x-button>
                                        @if ($attempt->exam->allow_review && $attempt->status === $attemptStatusCompleted)
                                            <x-button :link="route('admin.exam.attempt.review', [
                                                'exam' => $attempt->exam->id,
                                                'attempt' => $attempt->id,
                                            ])" class="btn-ghost btn-sm btn-square"
                                                icon="o-document-magnifying-glass" wire:navigate
                                                tooltip="{{ __('exam_list.review_answers') }}" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <x-card class="py-12 text-center md:py-16">
                    <div class="flex flex-col items-center">
                        <div
                            class="flex justify-center items-center mb-4 w-24 h-24 bg-gradient-to-br rounded-full md:w-32 md:h-32 md:mb-6 from-base-200 to-base-300">
                            <x-icon name="o-check-circle" class="w-12 h-12 md:w-16 md:h-16 text-base-content/30" />
                        </div>
                        <h3 class="mb-2 text-lg font-bold md:text-xl text-base-content/70">
                            {{ __('exam_list.empty.completed_title') }}</h3>
                        <p class="max-w-md text-sm text-center text-base-content/50 md:text-base">
                            {{ __('exam_list.empty.completed_description') }}</p>
                    </div>
                </x-card>
            @endif
        </x-tab>
    </x-tabs>
</div>
