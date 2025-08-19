<div class="sticky top-0 bg-base-100 p-4 z-10">
    {{-- Full content when expanded --}}
    <div class="hidden-when-collapsed">
        @if ($expandedTitle)
            <h1 class="text-xl font-bold text-base-content">{{ $expandedTitle }}</h1>
        @endif
        @if ($expandedSubtitle)
            <p class="text-sm text-base-content/70">{{ $expandedSubtitle }}</p>
        @endif
    </div>

    {{-- Collapsed content --}}
    <div class="display-when-collapsed hidden text-center">
        @switch($variant)
            @case('icon')
                <div class="flex flex-col items-center gap-1">
                    @if ($collapsedIcon)
                        <x-icon :name="$collapsedIcon" class="w-6 h-6 text-primary" />
                    @endif
                    @if ($collapsedText)
                        <span class="text-xs text-base-content/70">{{ $collapsedText }}</span>
                    @endif
                </div>
            @break

            @case('abbreviation')
                <div class="text-center">
                    @if ($collapsedText)
                        <div class="text-lg font-bold text-primary">{{ $collapsedText }}</div>
                    @endif
                    @if ($collapsedSubtext)
                        <div class="text-xs text-base-content/70">{{ $collapsedSubtext }}</div>
                    @endif
                </div>
            @break

            @default
                {{-- Default: First letter or custom text --}}
                @if ($collapsedText)
                    <div class="text-2xl font-bold text-primary">{{ $collapsedText }}</div>
                @endif
                @if ($collapsedSubtext)
                    <div class="text-xs text-base-content/70">{{ $collapsedSubtext }}</div>
                @endif
        @endswitch
    </div>
</div>
