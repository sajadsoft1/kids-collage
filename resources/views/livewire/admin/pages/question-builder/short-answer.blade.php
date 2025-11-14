<div class="space-y-6">
    <div class="space-y-2">
        <div class="text-sm text-gray-700">پاسخ‌های قابل قبول</div>
        <div class="space-y-2">
            @foreach ($correct_answer['acceptable_answers'] as $i => $ans)
                <div class="flex gap-2 items-center" wire:key="acceptable-{{ $i }}">
                    <x-input class="flex-1" wire:model.live="correct_answer.acceptable_answers.{{ $i }}"
                        placeholder="پاسخ {{ $i + 1 }}" />
                    @if (count($correct_answer['acceptable_answers']) > 1)
                        <x-button icon="o-trash" wire:click="removeAcceptable({{ $i }})" class="btn-ghost" />
                    @endif
                </div>
            @endforeach
        </div>
        <x-button icon="o-plus" wire:click="addAcceptable" class="btn-outline">افزودن پاسخ</x-button>
    </div>

    <div class="pt-4 border-t border-gray-200 space-y-3">
        <h4 class="font-medium">تنظیمات</h4>
        <div class="grid grid-cols-2 gap-3">
            <x-input type="number" label="حداکثر طول" wire:model.live="config.max_length" />
            <x-toggle label="حساس به حروف" wire:model.live="config.case_sensitive" />
            <x-toggle label="حذف فاصله‌های ابتدا/انتها" wire:model.live="config.trim_whitespace" />
            <x-toggle label="نمره‌دهی خودکار" wire:model.live="config.auto_grade" />
        </div>
    </div>
</div>
