<div>
    {{-- Options List --}}
    <div class="mb-4 space-y-3">
        @foreach ($options as $index => $option)
            <div wire:key="option-{{ $index }}" class="flex gap-3 items-start p-3 bg-gray-50 rounded-lg">
                {{-- Checkbox --}}
                <div class="pt-2">
                    <x-checkbox wire:click="toggleCorrect({{ $index }})" :checked="$option['is_correct']" class="w-5 h-5" />
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <x-textarea wire:model.blur="options.{{ $index }}.content" rows="2"
                        placeholder="متن گزینه {{ $index + 1 }}" />
                </div>

                {{-- Remove Button --}}
                @if (count($options) > 2)
                    <x-button icon="o-trash" wire:click="removeOption({{ $index }})" class="btn-ghost text-red-600" />
                @endif
            </div>
        @endforeach
    </div>

    {{-- Add Option Button --}}
    @if (count($options) < 10)
        <x-button icon="o-plus" wire:click="addOption" class="btn-outline btn-block">
            {{ __('question.builder.multiple.add_option') }}
        </x-button>
    @endif

    {{-- Config --}}
    <div class="pt-6 mt-6 border-t border-gray-200">
        <h4 class="mb-3 font-medium">{{ __('question.builder.common.settings') }}</h4>

        <div class="space-y-3">
            <x-checkbox wire:model.live="config.shuffle_options" label="{{ __('question.builder.multiple.shuffle_options') }}" />

            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">{{ __('question.builder.multiple.scoring_type') }}</label>
                <x-select wire:model.live="config.scoring_type">
                    <option value="all_or_nothing">{{ __('question.builder.multiple.scoring_all_or_nothing') }}</option>
                    <option value="partial">{{ __('question.builder.multiple.scoring_partial') }}</option>
                </x-select>
            </div>
        </div>
    </div>

    {{-- Info --}}
    <div class="p-3 mt-4 bg-blue-50 rounded-lg">
        <p class="text-sm text-blue-800">
            <x-icon name="o-information-circle" class="inline ml-1 w-4 h-4" />
            {{ __('question.info.select_one_correct') }}
        </p>
    </div>
</div>
