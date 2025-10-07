@php use App\Enums\GenderEnum; @endphp
<div @class([
        'flex items-center justify-start',
    ])>
    <i @class([
            'h-3.5 w-3.5 stroke-[1.7]',
            'fa fa-mars'=>$gender===GenderEnum::MALE,
            'fa fa-venus'=>$gender===GenderEnum::FEMALE,
])></i>
    <div class="ms-1.5 whitespace-nowrap">
        {{ $gender?->title()??'-' }}
    </div>
</div>