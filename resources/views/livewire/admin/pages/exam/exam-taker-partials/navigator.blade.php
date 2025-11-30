{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- NAVIGATOR DROPDOWN --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<div x-data="{ open: false }" class="relative">
    <x-button @click="open = !open" icon="o-squares-2x2"
        class="btn-ghost btn-sm text-primary-content hover:bg-white/20">
        {{ __('exam.navigator') }}
    </x-button>

    {{-- Navigator Panel --}}
    <div x-show="open" @click.away="open = false" x-transition
        class="absolute bottom-full {{ $isRtl ? 'left-0' : 'right-0' }} mb-2 w-80 bg-base-100 rounded-lg shadow-xl border border-base-300 p-4 z-50">
        <h4 class="text-base-content font-semibold mb-3 text-sm flex items-center gap-2">
            <x-icon name="o-map" class="w-4 h-4" />
            {{ __('exam.question_navigator') }}
        </h4>
        <div class="grid grid-cols-10 gap-1">
            @foreach ($allQuestions as $index => $q)
                <button wire:click="goToQuestion({{ $index }})" @click="open = false"
                    class="w-7 h-7 rounded text-xs font-medium transition
                        {{ $index === $currentQuestionIndex
                            ? 'bg-primary text-primary-content'
                            : (isset($answers[$q->id])
                                ? 'bg-success/20 text-success hover:bg-success/30'
                                : 'bg-base-200 text-base-content hover:bg-base-300') }}">
                    {{ $index + 1 }}
                </button>
            @endforeach
        </div>
        <div class="mt-3 pt-3 border-t border-base-300 flex items-center gap-4 text-xs text-base-content/60">
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 rounded bg-primary"></span> {{ __('exam.current') }}
            </span>
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 rounded bg-success/20"></span> {{ __('exam.answered') }}
            </span>
            <span class="flex items-center gap-1">
                <span class="w-3 h-3 rounded bg-base-200"></span> {{ __('exam.unanswered') }}
            </span>
        </div>
    </div>
</div>

