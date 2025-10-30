<div>
    {{-- Options List --}}
    <div class="mb-4 space-y-3">
        @foreach ($options as $index => $option)
            <div wire:key="option-{{ $index }}" class="flex gap-3 items-start p-3 bg-gray-50 rounded-lg">
                {{-- Radio (single correct) --}}
                <div class="pt-2">
                    <x-radio wire:click="setCorrect({{ $index }})" :checked="$option['is_correct']" class="w-5 h-5" />
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <x-textarea wire:model.blur="options.{{ $index }}.content" rows="2"
                        placeholder="متن گزینه {{ $index + 1 }}" />
                </div>

                {{-- Remove Button --}}
                @if (count($options) > 2)
                    <x-button icon="o-trash" wire:click="removeOption({{ $index }})"
                        class="btn-ghost text-red-600" />
                @endif
            </div>
        @endforeach
    </div>

    {{-- Add Option Button --}}
    @if (count($options) < 10)
        <x-button icon="o-plus" wire:click="addOption" class="btn-outline btn-block">
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

<div>
    {{-- Options List --}}
    <div class="mb-4 space-y-3">
        @foreach ($options as $index => $option)
            <div wire:key="option-{{ $index }}" class="flex gap-3 items-start p-3 bg-gray-50 rounded-lg">
                {{-- Radio --}}
                <div class="pt-2">
                    <x-radio wire:click="setCorrect({{ $index }})" :checked="$option['is_correct']" class="w-5 h-5" />
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <x-textarea wire:model.blur="options.{{ $index }}.content" rows="2"
                        placeholder="متن گزینه {{ $index + 1 }}" />
                </div>

                {{-- Remove Button --}}
                @if (count($options) > 2)
                    <button type="button" wire:click="removeOption({{ $index }})"
                        class="p-2 text-red-600 rounded hover:bg-red-50">
                        <x-heroicon-o-trash class="w-5 h-5" />
                    </button>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Add Option Button --}}
    @if (count($options) < 10)
        <button type="button" wire:click="addOption"
            class="py-2 w-full text-gray-600 rounded-lg border-2 border-gray-300 border-dashed hover:border-blue-500 hover:text-blue-600">
            <x-heroicon-o-plus class="inline ml-1 w-5 h-5" />
            افزودن گزینه
        </button>
    @endif

    {{-- Config --}}
    <div class="pt-6 mt-6 border-t border-gray-200">
        <h4 class="mb-3 font-medium">تنظیمات</h4>

        <div class="space-y-3">
            <label class="flex items-center">
                <x-checkbox wire:model.live="config.shuffle_options" />
                <span class="mr-2 text-sm text-gray-700">به هم ریختن گزینه‌ها هنگام نمایش</span>
            </label>

            <label class="flex items-center">
                <x-checkbox wire:model.live="config.show_explanation" />
                <span class="mr-2 text-sm text-gray-700">نمایش توضیحات پاسخ</span>
            </label>
        </div>
    </div>
</div>
