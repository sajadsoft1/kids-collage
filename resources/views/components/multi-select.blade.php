@props([
    'options' => [], // like [['id' => 1, 'name' => 'Option 1'], ['id' => 2, 'name' => 'Option 2']]
    'model' => '',
    'placeholder' => 'Select items...',
    'searchable' => true,
    'compact' => false,
])

<div x-data="multiSelect({
    options: {{ json_encode($options) }},
    model: @entangle($model).defer,
    searchable: {{ $searchable ? 'true' : 'false' }},
    compact: {{ $compact ? 'true' : 'false' }},
    placeholder: '{{ $placeholder }}'
})" class="relative" @click.away="open = false">
    <div class="relative">
        <button type="button" @click="open = !open"
            class="w-full flex items-center justify-between px-3 py-2 text-sm border rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            :class="{ 'h-10': !compact, 'h-8': compact }">
            <div class="flex flex-wrap gap-1">
                <template x-if="selected.length === 0">
                    <span class="text-gray-500" x-text="placeholder"></span>
                </template>
                <template x-for="item in selected" :key="item.id">
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800">
                        <span x-text="item.name"></span>
                        <button type="button" @click.stop="removeItem(item)"
                            class="ml-1 inline-flex text-primary-400 hover:text-primary-600">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </span>
                </template>
            </div>
            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-1 w-full bg-white shadow-lg rounded-lg py-1">
        <template x-if="searchable">
            <div class="px-3 py-2">
                <input type="text" x-model="search" @click.stop
                    class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Search...">
            </div>
        </template>

        <div class="max-h-60 overflow-y-auto">
            <template x-for="option in filteredOptions" :key="option.id">
                <div @click="toggleOption(option)" class="px-3 py-2 text-sm cursor-pointer hover:bg-gray-100"
                    :class="{ 'bg-primary-50': isSelected(option) }">
                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input type="checkbox" :checked="isSelected(option)"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                @click.stop>
                        </div>
                        <div class="ml-3">
                            <span x-text="option.name" class="block"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
