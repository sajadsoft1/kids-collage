<div class="space-y-4" x-data="{ draggedIndex: null }">
    {{-- Options List (Sortable) --}}
    <div class="mb-4 space-y-3">
        @foreach ($options ?? [] as $index => $option)
            <div wire:key="option-{{ $index }}" draggable="true" x-on:dragstart="draggedIndex = {{ $index }}"
                x-on:dragover.prevent
                x-on:drop.prevent="
                 if (draggedIndex !== null && draggedIndex !== {{ $index }}) {
                     $wire.reorder([draggedIndex, {{ $index }}])
                 }
                 draggedIndex = null
             "
                class="flex items-start gap-3 p-3 rounded-lg cursor-move bg-gray-50 hover:bg-gray-100">

                {{-- Drag Handle --}}
                <div class="pt-2">
                    <x-heroicon-o-bars-3 class="w-5 h-5 text-gray-400" />
                </div>

                {{-- Order Number --}}
                <div class="pt-2">
                    <span
                        class="inline-flex items-center justify-center w-8 h-8 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full">
                        {{ $index + 1 }}
                    </span>
                </div>

                {{-- Content --}}
                <div class="flex-1">
                    <x-textarea wire:model.blur="options.{{ $index }}.content" rows="2"
                        placeholder="آیتم {{ $index + 1 }}" />
                </div>

                {{-- Remove Button --}}
                @if (count($options ?? []) > 2)
                    <button type="button" wire:click="removeOption({{ $index }})"
                        class="p-2 text-red-600 rounded hover:bg-red-50">
                        <x-heroicon-o-trash class="w-5 h-5" />
                    </button>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Add Option Button --}}
    @if (count($options ?? []) < 10)
        <button type="button" wire:click="addOption"
            class="w-full py-2 text-gray-600 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 hover:text-blue-600">
            <x-heroicon-o-plus class="inline w-5 h-5 ml-1" />
            افزودن آیتم
        </button>
    @endif

    {{-- Config --}}
    <div class="pt-6 mt-6 border-t border-gray-200">
        <h4 class="mb-3 font-medium">تنظیمات</h4>

        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">نوع نمره‌دهی</label>
            <x-select wire:model.live="config.scoring_type" :options="$scoringTypeOptions" option-label="label"
                option-value="value" />
        </div>
    </div>

    {{-- Info --}}
    <x-alert icon="o-information-circle" class="alert-info alert-soft">
        ترتیب فعلی آیتم‌ها، ترتیب صحیح است. می‌توانید با کشیدن و رها کردن ترتیب را تغییر دهید.
    </x-alert>
</div>
