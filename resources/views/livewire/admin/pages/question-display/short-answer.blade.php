<div class="space-y-4">
    @if ($question->title)
        <div class="text-lg font-medium text-gray-900">{!! nl2br(e($question->title)) !!}</div>
    @endif

    @if ($question->body)
        <div class="p-4 max-w-none text-gray-700 bg-gray-50 rounded-lg prose">
            {!! nl2br(e($question->body)) !!}
        </div>
    @endif

    <x-input wire:model.live="value" :disabled="$disabled" placeholder="پاسخ شما" />

    @if ($showCorrect && !empty($question->correct_answer['acceptable_answers'] ?? []))
        <div class="p-3 bg-green-50 rounded">
            <div class="text-sm text-green-800">پاسخ‌های قابل قبول: {{ implode('، ', $question->correct_answer['acceptable_answers']) }}</div>
        </div>
    @endif
</div>


