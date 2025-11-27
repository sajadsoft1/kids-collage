@php
    $isRtl = app()->getLocale() === 'fa';
    $dir = $isRtl ? 'rtl' : 'ltr';

    $attempt = $this->attempt ?? null;
    $typeLabel = $currentQuestion?->type ? \Illuminate\Support\Str::headline($currentQuestion->type->value) : null;
    $allQuestions = $this->questions;

    // نمره کل آزمون
    $maxScore = $exam->total_score ?? 0;

    // وزن سوال فعلی (نمره این سوال از کل آزمون)
    $questionWeight = $pivotData?->weight ?? ($currentQuestion?->default_weight ?? 1);

    // اگر آزمون زمان‌دار نیست، زمان سپری شده رو نشون بده
    $elapsedSeconds = $progress['elapsed_time'] ?? 0;

    // اطلاعات پاسخ سوال فعلی
    $currentAnswer = $currentQuestion
        ? $attempt?->answers()->where('question_id', $currentQuestion->id)->first()
        : null;
    $questionScore = $currentAnswer?->score ?? 0; // نمره دریافتی از این سوال
    $questionMaxScore = $currentAnswer?->max_score ?? $questionWeight; // حداکثر نمره این سوال
    $questionTimeSpent = $currentAnswer?->time_spent ?? 0; // زمان صرف شده روی این سوال
@endphp

<div class="min-h-screen flex flex-col bg-base-200" dir="{{ $dir }}" x-data="{ ...examTimer(), showEndModal: false, showSuspendModal: false, showFinishModal: false }">

    {{-- Header --}}
    @include('livewire.admin.pages.exam.exam-taker-partials.header')

    {{-- Toolbar --}}
    @include('livewire.admin.pages.exam.exam-taker-partials.toolbar')

    {{-- Main Content - Two Columns --}}
    <div class="flex-1 flex overflow-hidden {{ $isRtl ? 'flex-row-reverse' : '' }}">
        {{-- Question Panel --}}
        @include('livewire.admin.pages.exam.exam-taker-partials.question-panel')

        {{-- Explanation Panel --}}
        @include('livewire.admin.pages.exam.exam-taker-partials.explanation-panel')
    </div>

    {{-- Footer --}}
    @include('livewire.admin.pages.exam.exam-taker-partials.footer')

    {{-- Modals --}}
    @include('livewire.admin.pages.exam.exam-taker-partials.modals')
</div>

{{-- Timer Script --}}
@include('livewire.admin.pages.exam.exam-taker-partials.timer-script')
