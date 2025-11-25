<div>
    <div class="mb-6">
        <p class="text-base-content/80">
            <strong class="font-bold text-base-content">{{ $exam->questions->count() }}</strong> سوال انتخاب شده
            @if ($exam->type === \App\Enums\ExamTypeEnum::SCORED)
                | مجموع نمره: <strong
                    class="font-bold text-base-content">{{ $exam->questions->sum('pivot.weight') }}</strong> از
                <strong class="font-bold text-base-content">{{ $totalScore ?? 0 }}</strong>
            @endif
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Selected Questions (Right Side) --}}
        <div class="p-6 rounded-lg shadow bg-base-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-base-content">سوالات انتخاب شده</h3>

                @if ($exam->type === \App\Enums\ExamTypeEnum::SCORED && !$this->validateTotalWeight())
                    <span class="flex items-center text-sm text-error">
                        <x-heroicon-o-exclamation-triangle class="ml-1 w-4 h-4" />
                        مجموع وزن‌ها برابر نیست!
                    </span>
                @endif
            </div>

            @if ($currentQuestions->isEmpty())
                <div class="py-12 text-center text-base-content/80">
                    <x-heroicon-o-question-mark-circle class="mx-auto mb-3 w-12 h-12 text-gray-400" />
                    <p>هنوز سوالی انتخاب نشده</p>
                </div>
            @else
                <div class="space-y-3" id="selected-questions-list" wire:ignore.self>
                    @foreach ($currentQuestions->sortBy('pivot.order') as $question)
                        <div wire:key="selected-{{ $question->id }}" data-question-id="{{ $question->id }}"
                            class="p-4 rounded-lg border-2 bg-base-200 border-base-300 sortable-item">

                            <div class="flex gap-3 items-start">
                                {{-- Order Handle & Controls --}}
                                <div class="flex flex-col gap-1 items-center">
                                    <button type="button" wire:click="moveQuestion({{ $question->id }}, 'up')"
                                        wire:loading.attr="disabled" {{ $loop->first ? 'disabled' : '' }}
                                        class="p-1 rounded hover:bg-base-300 disabled:opacity-40">
                                        <x-heroicon-o-chevron-up class="w-4 h-4" />
                                    </button>
                                    <div class="cursor-move drag-handle">
                                        <x-heroicon-o-bars-3 class="w-5 h-5 text-base-content/80" />
                                    </div>
                                    <button type="button" wire:click="moveQuestion({{ $question->id }}, 'down')"
                                        wire:loading.attr="disabled" {{ $loop->last ? 'disabled' : '' }}
                                        class="p-1 rounded hover:bg-base-300 disabled:opacity-40">
                                        <x-heroicon-o-chevron-down class="w-4 h-4" />
                                    </button>
                                </div>

                                {{-- Question Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium truncate text-base-content">
                                        {{ $question->title }}
                                    </div>
                                    <div class="flex gap-2 items-center mt-1 text-xs text-base-content/80">
                                        <span
                                            class="px-2 py-0.5 rounded bg-{{ $question->type->color() }}-100 text-{{ $question->type->color() }}-800">
                                            {{ $question->type->title() }}
                                        </span>
                                        @if ($question->difficulty)
                                            <span class="px-2 py-0.5 rounded {{ $question->difficulty->bgColor() }}">
                                                {{ $question->difficulty->title() }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Weight Input --}}
                                    @if ($exam->type === \App\Enums\ExamTypeEnum::SCORED)
                                        <div class="mt-2">
                                            <label class="text-xs text-base-content/80">وزن:</label>
                                            <input type="number" step="0.25"
                                                wire:model.blur="weights.{{ $question->id }}"
                                                wire:change="updateWeight({{ $question->id }}, $event.target.value)"
                                                class="px-2 py-1 w-20 text-sm rounded border-base-300 focus:border-primary focus:ring-primary">
                                        </div>
                                    @endif
                                </div>

                                {{-- Remove Button --}}
                                <button wire:click="removeQuestion({{ $question->id }})"
                                    class="p-2 rounded text-error hover:bg-error/10">
                                    <x-heroicon-o-x-mark class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Available Questions (Left Side) --}}
        <div class="p-6 rounded-lg shadow bg-base-100">
            <h3 class="mb-4 text-lg font-semibold text-base-content">بانک سوالات</h3>

            {{-- Search & Filters --}}
            <div class="mb-4 space-y-3">
                <x-input wire:model.live.debounce.300ms="search" placeholder="جستجو..." :label="trans('general.search')" />

                <x-select wire:model.live="typeFilter" :options="\App\Enums\QuestionTypeEnum::formatedCases()" :placeholder="trans('general.please_select_an_option')" option-label="label"
                    option-value="value">
                    <x-slot:append>
                        <x-button class="join-item btn-primary" icon="o-plus" :link="route('admin.question.create')" external
                            :tooltip-bottom="trans('general.page.create.title', ['model' => trans('question.model')])" />
                    </x-slot:append>
                </x-select>
            </div>

            {{-- Questions List --}}
            <div class="overflow-y-auto space-y-2 max-h-96">
                @forelse($questions as $question)
                    <div wire:key="available-{{ $question->id }}"
                        class="p-3 border rounded-lg hover:bg-base-200 transition-colors
                                {{ in_array($question->id, $selectedQuestions) ? 'bg-base-200 border-base-300' : 'border-base-300' }}">

                        <div class="flex gap-3 items-start">
                            {{-- Checkbox --}}
                            <div class="pt-1">
                                <x-checkbox
                                    wire:change="handleQuestionSelection({{ $question->id }}, $event.target.checked)"
                                    :checked="in_array($question->id, $selectedQuestions)" />
                            </div>

                            {{-- Question Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium line-clamp-2 text-base-content">
                                    {{ $question->title }}
                                </div>
                                <div class="flex gap-2 items-center mt-1 text-xs text-base-content/80">
                                    <span
                                        class="px-2 py-0.5 rounded text-{{ $question->type->color() }}-800 bg-{{ $question->type->color() }}-100">
                                        {{ $question->type->title() }}
                                    </span>
                                    <span>نمره: {{ $question->default_score }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-base-content/80">
                        سوالی یافت نشد
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($questions->hasPages())
                <div class="mt-4">
                    {{ $questions->links() }}
                </div>
            @endif
        </div>
    </div>

    @script
        <script>
            (function() {
                let sortableInstance = null;

                function initSortable() {
                    const container = document.getElementById('selected-questions-list');
                    if (!container) {
                        return;
                    }

                    // Destroy existing instance if any
                    if (sortableInstance) {
                        sortableInstance.destroy();
                        sortableInstance = null;
                    }

                    // Check if Sortable is available
                    if (typeof Sortable === 'undefined') {
                        console.warn('SortableJS is not loaded');
                        return;
                    }

                    sortableInstance = Sortable.create(container, {
                        animation: 150,
                        handle: '.drag-handle',
                        ghostClass: 'opacity-50',
                        forceFallback: true,
                        onEnd: function(evt) {
                            if (evt.oldIndex === evt.newIndex) {
                                return;
                            }

                            const orderedIds = Array.from(container.children)
                                .filter(child => child.classList.contains('sortable-item'))
                                .map(child => {
                                    return parseInt(child.dataset.questionId);
                                })
                                .filter(id => !isNaN(id));

                            if (orderedIds.length > 0) {
                                @this.reorderSelectedQuestions(orderedIds);
                            }
                        }
                    });
                }

                // Initialize on Livewire init
                document.addEventListener('livewire:init', () => {
                    // Wait a bit for DOM to be ready
                    setTimeout(initSortable, 100);
                });

                // Reinitialize after Livewire updates
                document.addEventListener('livewire:update', () => {
                    setTimeout(initSortable, 150);
                });

                // Also try to initialize immediately if DOM is already ready
                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                    setTimeout(initSortable, 200);
                } else {
                    window.addEventListener('load', () => {
                        setTimeout(initSortable, 200);
                    });
                }
            })();
        </script>
    @endscript

    {{-- Actions --}}
    {{-- <div class="flex justify-between mt-6">
        <a href="{{ route('admin.exam.index') }}"
            class="px-4 py-2 text-gray-700 bg-white rounded-lg border border-gray-300 hover:bg-gray-50">
            بازگشت
        </a>

        <div class="flex gap-3">
            @if ($exam->status === \App\Enums\ExamStatusEnum::DRAFT)
                <button wire:click="$dispatch('publish-exam')" {{ $exam->questions->isEmpty() ? 'disabled' : '' }}
                    class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    انتشار آزمون
                </button>
            @endif
        </div>
    </div> --}}
</div>
