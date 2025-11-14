<div>
    {{-- Options List --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        @foreach ($options as $index => $option)
            <div wire:key="option-{{ $index }}" class="flex items-start gap-4 p-3 rounded-lg bg-base-200">
                {{-- Radio (single correct) --}}
                <div class="pt-2">
                    <input type="radio" name="correct_option_single_choice" value="{{ $index }}"
                        wire:change="setCorrect({{ $index }})" @checked($option['is_correct'])
                        @disabled(!$hasCorrectAnswer) class="radio radio-primary w-5 h-5" />
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <x-textarea wire:model.live.debounce.300ms="options.{{ $index }}.content" rows="2"
                        placeholder="متن گزینه {{ $index + 1 }}" />
                </div>

                {{-- Remove Button --}}
                @if (count($options) > 2)
                    <x-button icon="o-trash" wire:click="removeOption({{ $index }})"
                        class="text-red-600 btn-ghost" wire:loading.attr="disabled"
                        wire:target="removeOption({{ $index }})" spinner="removeOption({{ $index }})" />
                @endif
            </div>
        @endforeach
    </div>

    {{-- Add Option Button --}}
    @if (count($options) < 10)
        <x-button icon="o-plus" wire:click="addOption" class="btn-outline btn-block mt-5 btn-primary"
            wire:loading.attr="disabled" wire:target="addOption" spinner="addOption">
            {{ __('question.builder.single.add_option') }}
        </x-button>
    @endif

    {{-- Config --}}
    <div class="pt-6 mt-6 border-t border-gray-200">
        <h4 class="mb-3 font-medium">{{ __('question.builder.common.settings') }}</h4>

        <div class="space-y-3">
            <x-checkbox wire:model.live="config.shuffle_options"
                label="{{ __('question.builder.common.shuffle_options') }}" />

            <x-checkbox wire:model.live="config.show_explanation"
                label="{{ __('question.builder.single.show_explanation') }}" />
        </div>
    </div>


</div>
