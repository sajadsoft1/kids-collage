@php use App\Helpers\Constants; @endphp
<x-avatar :image="$row->getFirstMediaUrl('avatar',Constants::RESOLUTION_512_SQUARE)" class="!w-14 !rounded-lg">
    <x-slot:title class="!font-bold ps-1">
        {{ $row->full_name }}
    </x-slot:title>

    <x-slot:subtitle class="grid gap-1 mt-1 ps-1 text-xs">
        <x-icon name="lucide.phone" class="!w-4 !h-4" :label="$row->mobile??'-'" />
        <x-icon name="lucide.mail" class="!w-4 !h-4" :label="$row->email??'-'" />
    </x-slot:subtitle>

</x-avatar>