{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- QUESTION PANEL --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<div class="flex-1 flex flex-col bg-base-100 {{ $isRtl ? 'border-s' : 'border-e' }} border-base-300">
    @if ($currentQuestion)
        {{-- Question Content Area --}}
        <div class="flex-1 p-6 overflow-y-auto">
            <div class="space-y-3">
                @php
                    $componentName =
                        'admin.pages.question-display.' . str_replace('_', '-', $currentQuestion->type->value);
                @endphp

                {{-- در حالت بازبینی: غیرفعال + نمایش جواب صحیح --}}
                <livewire:is :component="$componentName" :question="$currentQuestion" :value="$answers[$currentQuestion->id] ?? null" :disabled="$reviewMode"
                    :showCorrect="$reviewMode" :key="'question-' . $currentQuestion->id . ($reviewMode ? '-review' : '')" />
            </div>
        </div>

        {{-- Bottom Stats Bar - فقط در حالت بازبینی نمایش داده میشه --}}
        @if ($reviewMode)
            <div class="border-t-4 border-info bg-base-200 px-6 py-3">
                <div class="flex items-center gap-8 text-sm">
                    {{-- نمره سوال / نمره کل آزمون --}}
                    <div class="flex items-center gap-2">
                        <x-icon name="o-academic-cap" class="w-5 h-5 text-base-content/60" />
                        <div>
                            <span class="font-semibold text-base-content">
                                {{ number_format($questionMaxScore, 0) }} / {{ number_format($maxScore, 0) }}
                            </span>
                            <span class="text-xs text-base-content/60 block">{{ __('exam.question_of_total') }}</span>
                        </div>
                    </div>

                    {{-- زمان صرف شده روی این سوال --}}
                    <div class="flex items-center gap-2">
                        <x-icon name="o-clock" class="w-5 h-5 text-base-content/60" />
                        <div>
                            <span class="font-semibold text-base-content font-mono">
                                {{ gmdate('i:s', $questionTimeSpent) }}
                            </span>
                            <span class="text-xs text-base-content/60 block">{{ __('exam.question_time_spent') }}</span>
                        </div>
                    </div>

                    {{-- نمره دریافتی از این سوال --}}
                    <div class="flex items-center gap-2">
                        @if ($currentAnswer)
                            @if ($currentAnswer->is_correct)
                                <x-icon name="o-check-circle" class="w-5 h-5 text-success" />
                            @elseif ($currentAnswer->is_partially_correct)
                                <x-icon name="o-minus-circle" class="w-5 h-5 text-warning" />
                            @else
                                <x-icon name="o-x-circle" class="w-5 h-5 text-error" />
                            @endif
                        @else
                            <x-icon name="o-question-mark-circle" class="w-5 h-5 text-base-content/60" />
                        @endif
                        <div>
                            <span class="font-semibold text-base-content">
                                {{ number_format($questionScore, 1) }} / {{ number_format($questionMaxScore, 0) }}
                            </span>
                            <span class="text-xs text-base-content/60 block">{{ __('exam.question_score') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="flex-1 flex items-center justify-center text-base-content/50">
            <x-icon name="o-exclamation-circle" class="w-8 h-8 me-2" />
            {{ __('exam.no_question') }}
        </div>
    @endif
</div>

