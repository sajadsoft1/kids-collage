<div class="space-y-4">
    @if ($question->title)
        <div class="text-lg font-medium text-gray-900">{{ $question->title }}</div>
    @endif

    @if ($question->body)
        <div class="p-4 max-w-none text-gray-700 bg-gray-50 rounded-lg prose">
            {!! $question->body !!}
        </div>
    @endif

    <div class="space-y-2">
        @foreach ($order as $idx => $optionId)
            @php $opt = $options->firstWhere('id', $optionId); @endphp
            <div wire:key="ord-{{ $optionId }}" class="flex items-center gap-2 p-3 rounded border-2 {{ $disabled ? 'opacity-75' : '' }}">
                <div class="flex items-center gap-1">
                    <x-button size="xs" icon="o-chevron-up" wire:click="moveUp({{ $idx }})" class="btn-ghost" :disabled="$disabled" />
                    <x-button size="xs" icon="o-chevron-down" wire:click="moveDown({{ $idx }})" class="btn-ghost" :disabled="$disabled" />
                </div>
                <div class="w-8 text-center text-xs">{{ $idx + 1 }}</div>
                <div class="flex-1">{{ $opt?->content }}</div>
            </div>
        @endforeach
    </div>

    @if ($showCorrect)
        <div class="p-3 mt-2 bg-green-50 rounded text-sm text-green-800">
            ترتیب صحیح پس از اتمام نمایش داده می‌شود.
        </div>
    @endif
</div>
