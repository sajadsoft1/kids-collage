{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- CONFIRMATION MODALS --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- End Exam Modal - پایان آزمون --}}
<div x-show="showEndModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="showEndModal = false">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showEndModal = false"></div>

    {{-- Modal Content --}}
    <div class="relative bg-base-100 rounded-2xl shadow-2xl max-w-md w-full p-6 border border-error/20">
        {{-- Icon --}}
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center">
                <x-icon name="o-exclamation-triangle" class="w-8 h-8 text-error" />
            </div>
        </div>

        {{-- Title --}}
        <h3 class="text-xl font-bold text-center text-base-content mb-2">
            {{ __('exam.modal_end_title') }}
        </h3>

        {{-- Description --}}
        <p class="text-center text-base-content/70 mb-4">
            {{ __('exam.modal_end_description') }}
        </p>

        {{-- Warning List --}}
        <div class="bg-error/5 rounded-lg p-4 mb-6 space-y-2">
            <div class="flex items-start gap-2 text-sm text-error">
                <x-icon name="o-x-circle" class="w-5 h-5 shrink-0 mt-0.5" />
                <span>{{ __('exam.modal_end_warning_1') }}</span>
            </div>
            <div class="flex items-start gap-2 text-sm text-error">
                <x-icon name="o-x-circle" class="w-5 h-5 shrink-0 mt-0.5" />
                <span>{{ __('exam.modal_end_warning_2') }}</span>
            </div>
            <div class="flex items-start gap-2 text-sm text-base-content/60">
                <x-icon name="o-information-circle" class="w-5 h-5 shrink-0 mt-0.5" />
                <span>{{ __('exam.modal_end_info') }}</span>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="bg-base-200 rounded-lg p-3 text-center">
                <div class="text-2xl font-bold text-base-content">
                    {{ $progress['answered_questions'] ?? 0 }}/{{ $totalQuestions }}</div>
                <div class="text-xs text-base-content/60">{{ __('exam.modal_answered') }}</div>
            </div>
            <div class="bg-base-200 rounded-lg p-3 text-center">
                <div class="text-2xl font-bold text-base-content" x-text="formatTimeShort()">00:00</div>
                <div class="text-xs text-base-content/60">{{ __('exam.modal_time') }}</div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <x-button @click="showEndModal = false" class="btn-ghost flex-1" icon="o-arrow-uturn-left">
                {{ __('exam.modal_cancel') }}
            </x-button>
            <x-button wire:click="submitExam" @click="showEndModal = false" class="btn-error flex-1"
                icon="o-stop-circle" spinner>
                {{ __('exam.modal_confirm_end') }}
            </x-button>
        </div>
    </div>
</div>

{{-- Suspend Exam Modal - توقف موقت --}}
<div x-show="showSuspendModal" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4"
    @keydown.escape.window="showSuspendModal = false">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showSuspendModal = false"></div>

    {{-- Modal Content --}}
    <div class="relative bg-base-100 rounded-2xl shadow-2xl max-w-md w-full p-6 border border-warning/20">
        {{-- Icon --}}
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-warning/10 flex items-center justify-center">
                <x-icon name="o-pause-circle" class="w-8 h-8 text-warning" />
            </div>
        </div>

        {{-- Title --}}
        <h3 class="text-xl font-bold text-center text-base-content mb-2">
            {{ __('exam.modal_suspend_title') }}
        </h3>

        {{-- Description --}}
        <p class="text-center text-base-content/70 mb-4">
            {{ __('exam.modal_suspend_description') }}
        </p>

        {{-- Info List --}}
        <div class="bg-warning/5 rounded-lg p-4 mb-6 space-y-2">
            <div class="flex items-start gap-2 text-sm text-success">
                <x-icon name="o-check-circle" class="w-5 h-5 shrink-0 mt-0.5" />
                <span>{{ __('exam.modal_suspend_info_1') }}</span>
            </div>
            <div class="flex items-start gap-2 text-sm text-success">
                <x-icon name="o-check-circle" class="w-5 h-5 shrink-0 mt-0.5" />
                <span>{{ __('exam.modal_suspend_info_2') }}</span>
            </div>
            <div class="flex items-start gap-2 text-sm text-warning">
                <x-icon name="o-exclamation-triangle" class="w-5 h-5 shrink-0 mt-0.5" />
                <span>{{ __('exam.modal_suspend_warning') }}</span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <x-button @click="showSuspendModal = false" class="btn-ghost flex-1" icon="o-arrow-uturn-left">
                {{ __('exam.modal_cancel') }}
            </x-button>
            <x-button wire:click="suspendExam" @click="showSuspendModal = false" class="btn-warning flex-1"
                icon="o-pause-circle" spinner>
                {{ __('exam.modal_confirm_suspend') }}
            </x-button>
        </div>
    </div>
</div>

{{-- Finish Exam Modal - اتمام آزمون --}}
<div x-show="showFinishModal" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4"
    @keydown.escape.window="showFinishModal = false">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showFinishModal = false"></div>

    {{-- Modal Content --}}
    <div class="relative bg-base-100 rounded-2xl shadow-2xl max-w-md w-full p-6 border border-success/20">
        {{-- Icon --}}
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-success/10 flex items-center justify-center">
                <x-icon name="o-check-badge" class="w-8 h-8 text-success" />
            </div>
        </div>

        {{-- Title --}}
        <h3 class="text-xl font-bold text-center text-base-content mb-2">
            {{ __('exam.modal_finish_title') }}
        </h3>

        {{-- Description --}}
        <p class="text-center text-base-content/70 mb-4">
            {{ __('exam.modal_finish_description') }}
        </p>

        {{-- Stats Summary --}}
        <div class="bg-success/5 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-3 gap-3 text-center">
                <div>
                    <div class="text-2xl font-bold text-success">{{ $progress['answered_questions'] ?? 0 }}</div>
                    <div class="text-xs text-base-content/60">{{ __('exam.modal_answered_count') }}</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-warning">{{ $progress['remaining_questions'] ?? 0 }}</div>
                    <div class="text-xs text-base-content/60">{{ __('exam.modal_unanswered_count') }}</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-primary" x-text="formatTimeShort()">00:00</div>
                    <div class="text-xs text-base-content/60">{{ __('exam.modal_time') }}</div>
                </div>
            </div>
        </div>

        {{-- Info --}}
        @if (($progress['remaining_questions'] ?? 0) > 0)
            <div class="bg-warning/10 rounded-lg p-3 mb-4 flex items-start gap-2">
                <x-icon name="o-exclamation-triangle" class="w-5 h-5 text-warning shrink-0 mt-0.5" />
                <span class="text-sm text-warning">{{ __('exam.modal_finish_warning') }}</span>
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex gap-3">
            <x-button @click="showFinishModal = false" class="btn-ghost flex-1" icon="o-arrow-uturn-left">
                {{ __('exam.modal_cancel') }}
            </x-button>
            <x-button wire:click="submitExam" @click="showFinishModal = false" class="btn-success flex-1"
                icon="o-check-circle" spinner>
                {{ __('exam.modal_confirm_finish') }}
            </x-button>
        </div>
    </div>
</div>

