<div class="space-y-4">
    @if ($question->title)
        <div class="text-lg font-medium text-base-content">{!! nl2br(e($question->title)) !!}</div>
    @endif

    @if ($question->body)
        <div class="p-4 max-w-none rounded-lg text-base-content bg-base-200 prose">
            {!! nl2br(e($question->body)) !!}
        </div>
    @endif

    <div class="flex gap-6">
        <label wire:click="choose(true)" @class([
            'inline-flex items-center gap-2 p-3 rounded-lg border-2 cursor-pointer transition-all',
            'border-primary bg-primary/10' => $selected === true,
            'border-base-200 hover:border-base-200' => !$selected,
            'cursor-not-allowed opacity-75' => $disabled,
            'border-success bg-success/10' =>
                $showCorrect && ($question->correct_answer['value'] ?? false) === true,
        ])>
            <input type="radio" name="true-false-option" value="1"
                class="w-5 h-5 text-primary border-base-300 focus:ring-primary" @checked($selected === true)
                @disabled($disabled) />
            <span class="text-base-content">{{ $question->config['true_label'] ?? 'درست' }}</span>
        </label>
        <label wire:click="choose(false)"
            class="inline-flex items-center gap-2 p-3 rounded-lg border-2 cursor-pointer transition-all {{ $selected === false ? 'border-primary bg-primary/10' : 'border-base-200 hover:border-base-200' }} {{ $disabled ? 'cursor-not-allowed opacity-75' : '' }} {{ $showCorrect && ($question->correct_answer['value'] ?? false) === false ? 'border-success bg-success/10' : '' }}">
            <input type="radio" name="true-false-option" value="0"
                class="w-5 h-5 text-primary border-base-300 focus:ring-primary" @checked($selected === false)
                @disabled($disabled) />
            <span class="text-base-content">{{ $question->config['false_label'] ?? 'غلط' }}</span>
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
