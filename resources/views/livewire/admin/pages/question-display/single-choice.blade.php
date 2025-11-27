<div class="space-y-4">
    @if ($question->title)
        <div class="text-lg font-medium text-base-content">{{ $question->title }}</div>
    @endif

    @if ($question->body)
        <div class="p-4 max-w-none rounded-lg text-base-content bg-base-200 prose">
            {!! $question->body !!}
        </div>
    @endif

    <div class="space-y-2">
        @foreach ($options as $option)
            <label wire:key="option-{{ $option->id }}" wire:click="choose({{ $option->id }})"
                class="flex items-start p-4 rounded-lg border-2 cursor-pointer transition-all
                          {{ $selected === $option->id ? 'border-primary bg-primary/10' : 'border-base-200 hover:border-base-200' }}
                          {{ $disabled ? 'cursor-not-allowed opacity-75' : '' }}
                          {{ $showCorrect && $option->is_correct ? 'border-success bg-success/10' : '' }}
                          {{ $showCorrect && $selected === $option->id && !$option->is_correct ? 'border-error bg-error/10' : '' }}">

                <input type="radio" name="selected-option" value="{{ $option->id }}"
                    class="mt-1 w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500" @checked($selected === $option->id)
                    @disabled($disabled) />

                <div class="flex-1 mr-3 text-base-content">{{ $option->content }}</div>
            </label>
        @endforeach
    </div>

    @if (($question->config['show_explanation'] ?? false) && $showCorrect && $question->explanation)
        <div class="p-4 mt-4 rounded border-r-4 bg-warning/10 border-warning">
            <div class="flex items-start">
                <x-icon name="o-light-bulb" class="mt-0.5 ml-2 w-5 h-5 text-warning" />
                <div>
                    <h4 class="mb-1 font-medium text-warning">{!! __('question.display.explanation') !!}</h4>
                    <div class="text-sm text-base-content-muted">{!! nl2br(e($question->explanation)) !!}</div>
                </div>
            </div>
        </div>
    @endif
</div>
