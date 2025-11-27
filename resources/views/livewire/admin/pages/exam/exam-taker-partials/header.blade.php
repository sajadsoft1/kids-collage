{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- TOP HEADER BAR --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<header class="{{ $reviewMode ? 'bg-info text-info-content' : 'bg-primary text-primary-content' }}">
    <div class="flex items-center justify-between px-4 py-2">
        {{-- Test Name --}}
        <div class="flex-1">
            <span class="text-sm font-medium flex items-center gap-2">
                @if ($reviewMode)
                    <x-icon name="o-eye" class="w-4 h-4" />
                    <span class="badge badge-warning badge-sm">{{ __('exam.review_mode') }}</span>
                @else
                    <x-icon name="o-academic-cap" class="w-4 h-4" />
                @endif
                {{ $exam->title }} - {{ $attempt?->user?->name ?? auth()->user()->name ?? __('exam.guest') }}
            </span>
        </div>

        {{-- Center: Test Info --}}
        <div class="flex-1 text-center">
            <div class="text-sm">
                {{ __('exam.test_id') }}: {{ $attempt?->id ?? '---' }}
                ({{ ucfirst($exam->type->value) }}, {{ $exam->duration ? __('exam.timed') : __('exam.untimed') }})
            </div>
            <div class="text-xs opacity-70">{{ __('exam.question_id') }}: {{ $currentQuestion?->id ?? '---' }}</div>
        </div>

        {{-- Time & Question Counter --}}
        <div class="flex-1 flex items-center justify-end gap-6">
            @if (! $reviewMode)
                <div class="flex items-center gap-2">
                    <x-icon name="o-clock" class="w-4 h-4" />
                    <span class="text-sm">{{ __('exam.time') }}</span>
                    <span class="font-mono text-sm" x-text="formatTime()">00:00:00</span>
                </div>
            @endif
            <div class="flex items-center gap-2">
                <x-icon name="o-document-text" class="w-4 h-4" />
                <span class="text-sm">{{ __('exam.question') }}</span>
                <span class="font-mono text-sm">{{ $currentQuestionIndex + 1 }} {{ __('exam.of') }}
                    {{ $totalQuestions }}</span>
            </div>
        </div>
    </div>
</header>

