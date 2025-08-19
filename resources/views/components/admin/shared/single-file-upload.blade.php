@props(['default_image'=>'/assets/images/default/user-avatar.png','wire_model'=>'image','accept'=>'image/image','crop_after_change'=>true,'ratio'=>1])

<x-file
        :wire:model="$wire_model"
        :accept="$accept"
        :crop-after-change="$crop_after_change"
        class="w-full flex flex-row justify-center"
        :crop-config="['aspectRatio'=>$ratio,'guides'=>true]"
>
        <img src="{{ $default_image }}" class="w-50 rounded-lg" />
</x-file>
