<div class="space-y-6">
    <div class="grid grid-cols-2 gap-3">
        <x-toggle label="انتخاب چندگانه" wire:model.live="config.allow_multiple" />
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">نوع نمره‌دهی</label>
            <x-select wire:model.live="config.scoring_type">
                <option value="partial">نمره جزئی</option>
                <option value="exact">دقیقاً مطابق</option>
            </x-select>
        </div>
    </div>

    <div class="space-y-2">
        <div class="text-sm text-gray-700">بازه‌های صحیح (start/end بر اساس کاراکتر)</div>
        @foreach (($correct_answer['selections'] ?? []) as $i => $sel)
            <div class="flex items-center gap-2" wire:key="sel-{{ $i }}">
                <x-input type="number" class="w-32" wire:model.live="correct_answer.selections.{{ $i }}.start" placeholder="start" />
                <x-input type="number" class="w-32" wire:model.live="correct_answer.selections.{{ $i }}.end" placeholder="end" />
                <x-button icon="o-trash" wire:click="removeSelection({{ $i }})" class="btn-ghost" />
            </div>
        @endforeach
        <x-button icon="o-plus" wire:click="addSelection" class="btn-outline">افزودن بازه</x-button>
    </div>

    <div class="p-3 bg-blue-50 rounded text-sm text-blue-800">
        برای انتخاب دقیق متن در نمایش، از ابزار انتخاب متن استفاده می‌شود.
    </div>
</div>


