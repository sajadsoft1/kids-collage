@props([
    'link' => null,
    'title' => null,
    'description' => null,
    'image' => null,
    'icon' => null,
    'btn_label' => null,
    'btn_link' => null,
])
<div @class([
    'relative block w-full rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 p-12 text-center',
    'hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden',
])>
    {{-- load image --}}
    @if ($icon)
        <x-icon :name="$icon" class="w-12 h-12 text-base-content/30 mx-auto mb-3" />
    @elseif ($image)
        <img src="{{ $image }}" alt="empty" @class(['mx-auto text-gray-400', $attributes->get('class:image')])>
    @endif
    {{-- load title --}}
    @if ($title)
        <h3 class="mt-2 text-sm font-semibold text-base-content/60">{{ $title }}</h3>
    @endif
    {{-- load description --}}
    @if ($description)
        <p class="mt-1 text-sm text-base-content/40">{{ $description }}</p>
    @endif
    <div class="mt-6">
        @if ($btn_label)
            <x-button :label="$btn_label" :link="$btn_link ?? null" icon="o-plus" class="btn-primary btn-dash" />
        @endif
    </div>
</div>
