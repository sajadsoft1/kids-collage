<div class="py-6">
    <div class="px-4 mx-auto w-full max-w-5xl">
        <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="[]" />

        <div class="p-6 mt-4 rounded-xl border shadow-sm border-base-300 bg-base-100">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-content">
                        {{ $exam->title }}
                    </h1>

                    @if (filled($exam->description))
                        <p class="mt-2 text-sm leading-6 text-content-muted">
                            {{ $exam->description }}
                        </p>
                    @endif
                </div>

                @if ($timeRemaining !== null)
                    <div x-data="timer({{ $timeRemaining }})" x-init="startTimer()"
                        class="flex gap-2 items-center px-4 py-2 text-sm font-semibold rounded-lg"
                        :class="remainingSeconds < 300 ? 'bg-error/10 text-error' : 'bg-primary/10 text-primary'">
                        <x-heroicon-o-clock class="w-5 h-5" />
                        <span class="font-mono" x-text="formatTime()"></span>
                    </div>
                @endif
            </div>

            <div class="mt-6 space-y-4">
                <div>
                    <div class="flex justify-between items-center text-sm text-content-muted">
                        <span>پیشرفت</span>
                        <span>{{ $progress['answered_questions'] }} از {{ $totalQuestions }}</span>
                    </div>
                    <div class="mt-2 w-full h-2 rounded-full bg-base-200">
                        <div class="h-2 rounded-full transition-all bg-primary"
                            style="width: {{ $progress['progress_percentage'] }}%"></div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach ($questions as $index => $q)
                        <x-button wire:click="goToQuestion({{ $index }})" :label="$index + 1" :disabled="$index === $currentQuestionIndex"
                            @class([
                                'h-10 w-10 rounded-lg text-sm font-semibold transition-all',
                                'bg-primary text-content ring-2 ring-primary/40' =>
                                    $index === $currentQuestionIndex,
                                'bg-base-200 text-content' => $index !== $currentQuestionIndex,
                                'border border-success/60' => isset($answers[$q->id]),
                            ]) spinner wire:target="goToQuestion({{ $index }})" />
                    @endforeach
                </div>
            </div>
        </div>

        @if (!$canContinue)
            <div class="px-4 py-3 mt-4 text-sm rounded-xl border border-success/30 bg-success/10 text-success">
                این نظر سنجی قبلاً ثبت شده است. می‌توانید پاسخ‌های خود را مرور کنید.
            </div>
        @endif

        @if ($currentQuestion)
            <div class="p-8 mt-6 rounded-xl border shadow-sm border-base-300 bg-base-100">
                <div class="flex justify-between items-center mb-4 text-sm text-content-muted">
                    <span>
                        سوال {{ $currentQuestionIndex + 1 }} از {{ $totalQuestions }}
                    </span>
                    @if ($currentQuestion->pivot->is_required ?? false)
                        <span class="text-xs text-error">اجباری</span>
                    @endif
                </div>

                @php
                    $componentName =
                        'admin.pages.question-display.' . str_replace('_', '-', $currentQuestion->type->value);
                @endphp

                <livewire:is :component="$componentName" :question="$currentQuestion" :value="$answers[$currentQuestion->id] ?? null" :disabled="!$canContinue" :showCorrect="false"
                    :key="'survey-question-' . $currentQuestion->id" />
            </div>
        @endif

        <div class="flex flex-col gap-3 mt-6 md:flex-row md:items-center md:justify-between">
            <x-button wire:click="previousQuestion" icon="o-arrow-right" label="قبلی" :disabled="$currentQuestionIndex === 0"
                class="btn-outline btn-sm md:w-auto" spinner wire:target="previousQuestion" />


            @if ($canContinue)
                <x-button wire:click="submitSurvey" wire:confirm="آیا از ثبت پاسخ‌های این نظر سنجی اطمینان دارید؟"
                    icon="o-check" label="ثبت پاسخ‌ها" class="btn btn-primary md:w-auto" wire:loading.attr="disabled"
                    spinner wire:target="submitSurvey" />
            @else
                <x-button link="{{ route('admin.survey.index') }}" icon="o-arrow-left" label="بازگشت به لیست"
                    class="btn btn-outline md:w-auto" />
            @endif

            <x-button wire:click="nextQuestion" icon="o-arrow-left" label="بعدی" :disabled="$currentQuestionIndex === $totalQuestions - 1"
                class="btn btn-outline btn-sm md:w-auto" spinner wire:target="nextQuestion" />
        </div>
    </div>
</div>

<script>
    function timer(initialSeconds) {
        return {
            remainingSeconds: initialSeconds,
            interval: null,
            startTimer() {
                this.interval = setInterval(() => {
                    this.remainingSeconds--;
                    if (this.remainingSeconds <= 0) {
                        clearInterval(this.interval);
                        @this.call('submitSurvey');
                    }
                }, 1000);
            },
            formatTime() {
                const hours = Math.floor(this.remainingSeconds / 3600);
                const minutes = Math.floor((this.remainingSeconds % 3600) / 60);
                const seconds = this.remainingSeconds % 60;

                if (hours > 0) {
                    return `${hours}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }

                return `${minutes}:${String(seconds).padStart(2, '0')}`;
            }
        }
    }
</script>
