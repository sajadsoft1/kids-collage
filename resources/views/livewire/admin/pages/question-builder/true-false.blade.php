<div class="space-y-4">
    <div class="grid grid-cols-2 gap-3">
        <x-input label="{{ __('question.builder.true_false.true_label') }}" wire:model.live="config.true_label" />
        <x-input label="{{ __('question.builder.true_false.false_label') }}" wire:model.live="config.false_label" />
    </div>

    <div class="p-4 bg-gray-50 rounded-lg">
        <div class="text-sm text-gray-700 mb-2">{{ __('question.builder.true_false.correct_answer') }}</div>
        <div class="flex gap-6">
            <label class="inline-flex items-center gap-2">
                <x-radio wire:click="setAnswer(true)" :checked="($correct_answer['value'] ?? false) === true" />
                <span>{{ $config['true_label'] ?? 'درست' }}</span>
            </label>
            <label class="inline-flex items-center gap-2">
                <x-radio wire:click="setAnswer(false)" :checked="($correct_answer['value'] ?? false) === false" />
                <span>{{ $config['false_label'] ?? 'غلط' }}</span>
            </label>
        </div>
    </div>
</div>
