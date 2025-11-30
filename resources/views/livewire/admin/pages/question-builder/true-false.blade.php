<div class="space-y-4">
    <div class="grid grid-cols-2 gap-3">
        <x-input label="{{ __('question.builder.true_false.true_label') }}" wire:model.live="config.true_label" />
        <x-input label="{{ __('question.builder.true_false.false_label') }}" wire:model.live="config.false_label" />
    </div>

    <x-alert icon="o-information-circle" class="alert-info alert-soft">
        {{ __('question.builder.true_false.shuffle_info') }}
    </x-alert>
</div>
