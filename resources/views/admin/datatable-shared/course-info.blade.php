@php use App\Helpers\Constants;use App\Helpers\StringHelper; @endphp
<x-avatar :image="$row->template->getFirstMediaUrl('image',Constants::RESOLUTION_100_SQUARE)" class="!w-14 !rounded-lg">
    <x-slot:title class="!font-bold ps-1">
        <a href="{{route('admin.course.show',['course' => $row->id])}}" target="_blank">{{ $row->template->title }}</a>
    </x-slot:title>

    <x-slot:subtitle class="grid gap-1 mt-1 ps-1 text-xs">
        <x-icon name="lucide.user" class="!w-4 !h-4" :label="$row->teacher->full_name"/>
        <x-icon name="lucide.coins" class="!w-4 !h-4" :label="StringHelper::toCurrency($row->price)"/>
    </x-slot:subtitle>

</x-avatar>