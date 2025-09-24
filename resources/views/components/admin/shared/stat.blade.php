@props([
        'style'=>'center',
'title'=>null,
'description'=>null,
'value'=>null,
'icon'=>null,
'iconBgColor'=>'bg-indigo-100',
'iconTextColor'=>'text-indigo-500',
])
<div class="flex flex-col rounded shadow-sm">
    @switch($style)
        @case('center')
            <div class="p-5 lg:p-6 grow w-full">
                @if($icon)
                    <div class="flex justify-center items-center rounded-xl w-16 h-16 mx-auto mt-5 {{$iconBgColor}}">
                        <x-icon :name="$icon" @class([$iconTextColor])/>
                    </div>
                @endif

                <dl class="py-5">
                    <dt class="text-3xl font-bold">
                        {{@$value}}
                    </dt>
                    <dd class="text-lg text-gray-500">
                        {{@$description}}
                    </dd>
                </dl>
            </div>
        @break
    @endswitch
</div>