@props([
    'label' => null,
    'info' => null,
])
<div class="flex items-center justify-between py-2 border-b border-slate-200">
    <div class="flex items-center space-x-2">
        <span class="text-sm font-medium text-slate-800">
            {{ $label }}
        </span>
        @if ($info)
            <span class="text-xs text-slate-500 max-[640px]:hidden">
                {{ $info }}
            </span>
            <div class="sm:hidden">
                <x-popover position="top-start" offset="20">
                    <x-slot:trigger>
                        <x-icon name="o-information-circle" class="w-4 h-4 text-slate-500" />
                    </x-slot:trigger>
                    <x-slot:content>
                        {{ $info }}
                    </x-slot:content>
                </x-popover>
            </div>
        @endif
    </div>
    {{ $slot }}
</div>
