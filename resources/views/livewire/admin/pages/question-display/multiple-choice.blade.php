<div class="space-y-4">
    {{-- Question Title --}}
    @if ($question->title)
        <div class="text-lg font-medium text-base-content">
            {!! nl2br(e($question->title)) !!}
        </div>
    @endif

    {{-- Question Body --}}
    @if ($question->body)
        <div class="p-4 max-w-none rounded-lg text-base-content bg-base-200 prose">
            {!! nl2br(e($question->body)) !!}
        </div>
    @endif

    {{-- Hint --}}
    <div class="flex items-center text-sm text-base-content-muted">
        <x-icon name="o-information-circle" class="ml-1 w-4 h-4" />
        {{ __('question.display.multiple.hint_multi_select') }}
    </div>

    {{-- Options --}}
    <div class="space-y-2">
        @foreach ($options as $option)
            <label wire:key="option-{{ $option->id }}"
                class="flex items-start p-4 rounded-lg border-2 cursor-pointer transition-all
                          {{ in_array($option->id, $selectedOptions) ? 'border-primary bg-primary/10' : 'border-base-200 hover:border-base-200' }}
                          {{ $disabled ? 'cursor-not-allowed opacity-75' : '' }}
                          {{ $showCorrect && $option->is_correct ? 'border-success bg-success/10' : '' }}
                          {{ $showCorrect && in_array($option->id, $selectedOptions) && !$option->is_correct ? 'border-error bg-error/10' : '' }}">

                <x-checkbox wire:click="toggleOption({{ $option->id }})" value="{{ $option->id }}" :checked="in_array($option->id, $selectedOptions)"
                    :disabled="$disabled" class="mt-1 w-5 h-5" />

                <div class="flex-1 mr-3">
                    <div class="text-base-content">
                        {{ $option->content }}
                    </div>

                    {{-- Show correct/incorrect indicator --}}
                    @if ($showCorrect)
                        @if ($option->is_correct)
                            <div class="flex items-center mt-2 text-sm text-success">
                                <x-icon name="o-check-circle" class="ml-1 w-4 h-4" />
                                {{ __('question.display.correct') }}
                            </div>
                        @elseif(in_array($option->id, $selectedOptions))
                            <div class="flex items-center mt-2 text-sm text-error">
                                <x-icon name="o-x-circle" class="ml-1 w-4 h-4" />
                                {{ __('question.display.should_not_be_selected') }}
                            </div>
                        @endif
                    @endif
                </div>
            </label>
        @endforeach
    </div>

    {{-- Explanation --}}
    @if ($showCorrect && $question->explanation)
        <div class="p-4 mt-4 rounded border-r-4 bg-warning/10 border-warning">
            <div class="flex items-start">
                <x-icon name="o-light-bulb" class="mt-0.5 ml-2 w-5 h-5 text-warning" />
                <div>
                    <h4 class="mb-1 font-medium text-base-content">{{ __('question.display.explanation') }}</h4>
                    <div class="text-sm text-base-content-muted">
                        {!! nl2br(e($question->explanation)) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
