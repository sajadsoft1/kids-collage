{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- EXPLANATION PANEL --}}
{{-- نمایش در حالت بازبینی یا حالت immediate --}}
{{-- در حالت immediate قبل از ثبت پاسخ: پیام انتظار --}}
{{-- در حالت immediate بعد از ثبت پاسخ: توضیحات واقعی --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
@php
    // نمایش پنل در حالت بازبینی یا حالت immediate
    $showPanel = $reviewMode || $isImmediateMode;
    
    // آیا توضیحات واقعی نمایش داده بشه؟ (فقط وقتی قفل شده یا حالت بازبینی)
    $showActualExplanation = $reviewMode || ($isImmediateMode && $isCurrentQuestionLocked);
@endphp

@if ($showPanel)
    <div class="w-[45%] flex flex-col bg-base-100">
        {{-- Tab Header --}}
        <div class="border-b border-base-300">
            <button class="px-6 py-3 text-sm font-medium text-base-content border-b-2 border-primary bg-base-200">
                <x-icon name="o-light-bulb" class="w-4 h-4 inline me-1" />
                {{ __('exam.explanation') }}
            </button>
        </div>

        {{-- Explanation Content --}}
        <div class="flex-1 p-6 overflow-y-auto text-sm leading-relaxed">
            @if ($showActualExplanation)
                {{-- نمایش توضیحات واقعی --}}
                @if ($currentQuestion?->explanation)
                    <div class="prose prose-sm max-w-none">
                        {!! $currentQuestion->explanation !!}
                    </div>
                @else
                    <div class="text-base-content/40 italic flex items-center gap-2">
                        <x-icon name="o-information-circle" class="w-5 h-5" />
                        {{ __('exam.no_explanation') }}
                    </div>
                @endif
            @else
                {{-- پیام انتظار قبل از ثبت پاسخ --}}
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <div class="w-16 h-16 mb-4 rounded-full bg-base-200 flex items-center justify-center">
                        <x-icon name="o-lock-closed" class="w-8 h-8 text-base-content/30" />
                    </div>
                    <p class="text-base-content/50 text-sm">
                        {{ __('exam.explanation_after_submit') }}
                    </p>
                </div>
            @endif
        </div>
    </div>
@endif

