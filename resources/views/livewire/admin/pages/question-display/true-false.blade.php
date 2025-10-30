<div class="space-y-4">
    @if ($question->title)
        <div class="text-lg font-medium text-gray-900">{!! nl2br(e($question->title)) !!}</div>
    @endif

    @if ($question->body)
        <div class="p-4 max-w-none text-gray-700 bg-gray-50 rounded-lg prose">
            {!! nl2br(e($question->body)) !!}
        </div>
    @endif

    <div class="flex gap-6">
        <label
            class="inline-flex items-center gap-2 p-3 rounded-lg border-2 cursor-pointer transition-all {{ $selected === true ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }} {{ $disabled ? 'cursor-not-allowed opacity-75' : '' }} {{ $showCorrect && ($question->correct_answer['value'] ?? false) === true ? 'border-green-500 bg-green-50' : '' }}">
            <x-radio wire:click="choose(true)" :checked="$selected === true" :disabled="$disabled" />
            <span>{{ $question->config['true_label'] ?? 'درست' }}</span>
        </label>
        <label
            class="inline-flex items-center gap-2 p-3 rounded-lg border-2 cursor-pointer transition-all {{ $selected === false ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }} {{ $disabled ? 'cursor-not-allowed opacity-75' : '' }} {{ $showCorrect && ($question->correct_answer['value'] ?? false) === false ? 'border-green-500 bg-green-50' : '' }}">
            <x-radio wire:click="choose(false)" :checked="$selected === false" :disabled="$disabled" />
            <span>{{ $question->config['false_label'] ?? 'غلط' }}</span>
        </label>
    </div>

    @if ($showCorrect && $question->explanation)
        <div class="p-4 mt-4 bg-yellow-50 rounded border-r-4 border-yellow-400">
            <div class="flex items-start">
                <x-heroicon-o-light-bulb class="mt-0.5 ml-2 w-5 h-5 text-yellow-600" />
                <div>
                    <h4 class="mb-1 font-medium text-yellow-900">توضیحات</h4>
                    <div class="text-sm text-yellow-800">{!! nl2br(e($question->explanation)) !!}</div>
                </div>
            </div>
        </div>
    @endif
</div>
