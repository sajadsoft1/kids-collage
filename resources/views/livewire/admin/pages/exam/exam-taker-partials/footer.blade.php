{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- FOOTER BAR --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<footer class="{{ $reviewMode ? 'bg-info text-info-content' : 'bg-primary text-primary-content' }} px-4 py-2 flex items-center justify-between">
    {{-- End & Suspend (فقط در حالت عادی) --}}
    <div class="flex items-center gap-2">
        @if ($reviewMode)
            {{-- در حالت بازبینی: دکمه بازگشت --}}
            <x-button href="{{ route('admin.exam.attempt.results', ['exam' => $exam->id, 'attempt' => $attempt->id]) }}"
                icon="o-arrow-uturn-left"
                class="btn-ghost btn-sm text-info-content hover:bg-white/20">
                {{ __('exam.back_to_results') }}
            </x-button>
        @else
            {{-- پایان آزمون - اتمام کامل بدون امکان ادامه --}}
            <x-button @click="showEndModal = true" icon="o-stop-circle"
                class="btn-error btn-sm text-error-content hover:bg-error/80">
                {{ __('exam.end') }}
            </x-button>

            {{-- توقف موقت - خروج با امکان ادامه --}}
            <x-button @click="showSuspendModal = true" icon="o-pause-circle"
                class="btn-ghost btn-sm text-primary-content hover:bg-white/20">
                {{ __('exam.suspend') }}
            </x-button>
        @endif
    </div>

    {{-- Navigation --}}
    <div class="flex items-center gap-2">
        @if ($isRtl)
            <x-button wire:click="previousQuestion" :disabled="$currentQuestionIndex === 0" icon="o-chevron-right"
                class="btn-ghost btn-sm {{ $reviewMode ? 'text-info-content' : 'text-primary-content' }} hover:bg-white/20 disabled:opacity-50">
                {{ __('exam.previous') }}
            </x-button>
        @else
            <x-button wire:click="previousQuestion" :disabled="$currentQuestionIndex === 0" icon="o-chevron-left"
                class="btn-ghost btn-sm {{ $reviewMode ? 'text-info-content' : 'text-primary-content' }} hover:bg-white/20 disabled:opacity-50">
                {{ __('exam.previous') }}
            </x-button>
        @endif

        {{-- Navigator Dropdown --}}
        @include('livewire.admin.pages.exam.exam-taker-partials.navigator')

        @if ($reviewMode)
            {{-- در حالت بازبینی: فقط دکمه بعدی --}}
            @if ($isRtl)
                <x-button wire:click="nextQuestion" :disabled="$isLastQuestion" icon="o-chevron-left"
                    class="btn-ghost btn-sm text-info-content hover:bg-white/20 disabled:opacity-50">
                    {{ __('exam.next') }}
                </x-button>
            @else
                <x-button wire:click="nextQuestion" :disabled="$isLastQuestion" icon="o-chevron-right" icon-right
                    class="btn-ghost btn-sm text-info-content hover:bg-white/20 disabled:opacity-50">
                    {{ __('exam.next') }}
                </x-button>
            @endif
        @else
            {{-- Next or Finish Button --}}
            @if ($isLastQuestion)
                {{-- آخرین سوال: دکمه اتمام آزمون --}}
                <x-button @click="showFinishModal = true" icon="o-check-circle"
                    class="btn-success btn-sm text-success-content hover:bg-success/80">
                    {{ __('exam.finish_exam') }}
                </x-button>
            @else
                {{-- سوالات قبلی: دکمه بعدی --}}
                @if ($isRtl)
                    <x-button wire:click="nextQuestion" icon="o-chevron-left"
                        class="btn-ghost btn-sm text-primary-content hover:bg-white/20">
                        {{ __('exam.next') }}
                    </x-button>
                @else
                    <x-button wire:click="nextQuestion" icon="o-chevron-right" icon-right
                        class="btn-ghost btn-sm text-primary-content hover:bg-white/20">
                        {{ __('exam.next') }}
                    </x-button>
                @endif
            @endif
        @endif
    </div>
</footer>

