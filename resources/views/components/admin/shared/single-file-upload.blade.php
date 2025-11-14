@props([
    'label' => null,
    'hint' => null,
    'default_image' => '/assets/images/default/user-avatar.png',
    'wire_model' => 'image',
    'accept' => 'image/image',
    'crop_after_change' => true,
    'ratio' => 1,
])

<div wire:key="file-upload-{{ $ratio }}">
    <x-file :label="$label" :hint="$hint" :wire:model="$wire_model" :accept="$accept" :crop-after-change="$crop_after_change"
        class="flex flex-row justify-center w-full" :crop-config="['aspectRatio' => $ratio, 'guides' => true]">
        <img src="{{ $default_image }}" class="rounded-lg w-50" />
    </x-file>
</div>
