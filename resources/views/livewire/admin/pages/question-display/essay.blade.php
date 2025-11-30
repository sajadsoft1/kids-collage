<div class="space-y-4">
    @if ($question->title)
        <div class="text-lg font-medium text-gray-900">{{ $question->title }}</div>
    @endif

    @if ($question->body)
        <div class="p-4 max-w-none text-gray-700 bg-gray-50 rounded-lg prose">
            {!! $question->body !!}
        </div>
    @endif

    <x-textarea wire:model.live="value" rows="6" :disabled="$disabled" placeholder="پاسخ خود را بنویسید" />

    <div class="text-xs text-gray-500">پاسخ شما بعداً توسط مدرس بررسی می‌شود.</div>
</div>
