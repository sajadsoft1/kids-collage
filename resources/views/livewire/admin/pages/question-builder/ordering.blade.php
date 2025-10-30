<div class="space-y-4">
    <div class="space-y-2">
        @foreach ($options as $index => $item)
            <div wire:key="item-{{ $index }}" class="flex items-center gap-2 p-3 bg-gray-50 rounded">
                <div class="flex items-center gap-1">
                    <x-button size="xs" icon="o-chevron-up" wire:click="moveUp({{ $index }})" class="btn-ghost" />
                    <x-button size="xs" icon="o-chevron-down" wire:click="moveDown({{ $index }})" class="btn-ghost" />
                </div>
                <div class="text-xs w-8 text-center">{{ $index + 1 }}</div>
                <x-input class="flex-1" wire:model.live="options.{{ $index }}.content" />
                @if (count($options) > 2)
                    <x-button icon="o-trash" wire:click="removeItem({{ $index }})" class="btn-ghost" />
                @endif
            </div>
        @endforeach
    </div>

    <x-button icon="o-plus" wire:click="addItem" class="btn-outline w-full">افزودن مورد</x-button>

    <div class="pt-4 border-t border-gray-200 space-y-3">
        <h4 class="font-medium">تنظیمات</h4>
        <x-select wire:model.live="config.scoring_type">
            <option value="exact">ترتیب کاملاً درست</option>
            <option value="partial">تطبیق موقعیت‌ها</option>
            <option value="adjacent">تطبیق مجاورت</option>
        </x-select>
    </div>
</div>

<div x-data="{ draggedIndex: null }">
    {{-- Options List (Sortable) --}}
    <div class="mb-4 space-y-3">
        @foreach ($options as $index => $option)
            <div wire:key="option-{{ $index }}" draggable="true" x-on:dragstart="draggedIndex = {{ $index }}"
                x-on:dragover.prevent
                x-on:drop.prevent="
                     if (draggedIndex !== null && draggedIndex !== {{ $index }}) {
                         $wire.reorder([draggedIndex, {{ $index }}])
                     }
                     draggedIndex = null
                 "
                class="flex gap-3 items-start p-3 bg-gray-50 rounded-lg cursor-move hover:bg-gray-100">

                {{-- Drag Handle --}}
                <div class="pt-2">
                    <x-heroicon-o-bars-3 class="w-5 h-5 text-gray-400" />
                </div>

                {{-- Order Number --}}
                <div class="pt-2">
                    <span
                        class="inline-flex justify-center items-center w-8 h-8 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full">
                        {{ $index + 1 }}
                    </span>
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <x-textarea wire:model.blur="options.{{ $index }}.content" rows="2"
                        placeholder="آیتم {{ $index + 1 }}" />
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
            افزودن آیتم
        </button>
    @endif

    {{-- Config --}}
    <div class="pt-6 mt-6 border-t border-gray-200">
        <h4 class="mb-3 font-medium">تنظیمات</h4>

        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">نوع نمره‌دهی</label>
            <x-select wire:model.live="config.scoring_type">
                <option value="exact">دقیق (باید ترتیب کاملا درست باشد)</option>
                <option value="partial">جزئی (بر اساس تعداد موقعیت‌های صحیح)</option>
                <option value="adjacent">مجاورت (بر اساس جفت‌های مجاور صحیح)</option>
            </x-select>
        </div>
    </div>

    {{-- Info --}}
    <div class="p-3 mt-4 bg-blue-50 rounded-lg">
        <p class="text-sm text-blue-800">
            <x-heroicon-o-information-circle class="inline ml-1 w-4 h-4" />
            ترتیب فعلی آیتم‌ها، ترتیب صحیح است. می‌توانید با کشیدن و رها کردن ترتیب را تغییر دهید.
        </p>
    </div>
</div>
