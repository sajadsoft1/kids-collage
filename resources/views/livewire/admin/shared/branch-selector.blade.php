<div class="flex-1 min-w-0 relative" x-data="{ open: false }">
    <button @click="open = !open"
        class="flex items-center justify-between w-full text-left text-white hover:text-white/80 transition-colors">
        <span class="text-base font-semibold truncate">
            @if ($selectedBranch)
                {{ collect($branches)->firstWhere('value', $selectedBranch)['label'] ?? 'Select Branch' }}
            @else
                {{ $branches[0]['label'] ?? 'Select Branch' }}
            @endif
        </span>
        <x-icon name="o-chevron-down" class="w-4 h-4 shrink-0 ml-2" x-bind:class="open ? 'rotate-180' : ''" />
    </button>

    <div x-show="open" @click.away="open = false" x-transition
        class="absolute top-full left-0 mt-2 w-64 bg-[#1E1E2E] border border-gray-800 rounded-lg shadow-2xl z-50">
        <div class="py-2">
            @foreach ($branches as $branch)
                <button wire:click="updatedSelectedBranch({{ $branch['value'] }})" @click="open = false"
                    class="w-full flex items-center px-4 py-2.5 text-sm text-white/80 hover:text-white hover:bg-white/10 transition-colors {{ $selectedBranch === $branch['value'] ? 'bg-white/10 text-white' : '' }}">
                    <span>{{ $branch['label'] }}</span>
                    @if ($selectedBranch === $branch['value'])
                        <x-icon name="o-check" class="w-4 h-4 ml-auto" />
                    @endif
                </button>
            @endforeach
        </div>
    </div>
</div>
