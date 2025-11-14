<div class="space-y-4">
    <div class="grid grid-cols-2 gap-3">
        <x-input type="number" label="حداقل تعداد کلمات" wire:model.live="config.min_words" />
        <x-input type="number" label="حداکثر تعداد کلمات" wire:model.live="config.max_words" />
    </div>
    <x-toggle label="متن غنی (Rich Text)" wire:model.live="config.rich_text" />

    <div class="p-3 bg-blue-50 rounded text-sm text-blue-800">
        پاسخ تشریحی به صورت دستی نمره‌دهی می‌شود.
    </div>
</div>


