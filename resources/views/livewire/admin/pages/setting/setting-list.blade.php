<div class="flex flex-col flex-1 mt-5">
    <div class="flex flex-col md:flex-row md:-me-px flex-1">

        <aside class="flex flex-nowrap overflow-x-scroll no-scrollbar md:block md:overflow-auto px-3 py-6 border-b md:border-b-0 md:border-e border-slate-200 min-w-60 md:space-y-3 bg-base-100">
            <x-menu class=" px-4 sm:px-6 lg:px-0">
                @foreach($settings as $setting)
                    <x-menu-item
                            @class(['text-primary font-bold bg-primary/10'=>Arr::get($setting,'key')===Arr::get($detail,'key')])
                            title="{{$setting->title}}"
                            :icon="$iconMap[$setting['key']] ?? 'heroicon-o-cog'"
                            wire:click="show({{$setting}})"/>
                @endforeach
            </x-menu>
        </aside>

        <div class="flex grow flex-1">

            <div wire:loading wire:target="show" class="flex flex-1 justify-center items-center w-full h-full bg-base-100">
                <div class="flex flex-col items-center justify-center gap-4 p-8">
                    <span class="loading loading-bars loading-xl text-primary"></span>
                    <span class="text-lg font-semibold text-slate-700">در حال بارگذاری اطلاعات...</span>
                    <span class="text-sm text-slate-400">لطفاً چند لحظه صبر کنید</span>
                </div>
            </div>

            <div wire:loading.remove wire:target="show" class="flex flex-1 h-full">
                <x-card shadow separator progress-indicator="update"
                        class="flex-1 border-0 rounded-none"
                        :title="Arr::get($detail,'label')"
                        :subtitle="Arr::get($detail,'help')">
                    <div class="mx-auto max-w-2xl space-y-16 sm:space-y-20 lg:mx-0 lg:max-w-none">
                        <dl class="flex flex-wrap">
                            @foreach(Arr::get($detail,'rows',[]) as $row)

                                @if(Arr::get($row,'complex',false))

                                    <div class="flex flex-row items-center rounded shadow-sm bg-gray-200 w-full p-3 my-5">
                                        @if(!empty(Arr::get($row,'label')))
                                            <p> {!! Arr::get($row,'label') !!}</p>
                                        @endif
                                        @if(!empty(Arr::get($row,'help')))
                                            <small class="ms-5 text-gray-400 text-xs">: {!! Arr::get($row,'help') !!}</small>
                                        @endif
                                    </div>
                                    @foreach(Arr::get($row,'items',[]) as $item)
                                        <div class="mt-3 p-1 lg:w-{{Arr::get($item['ratio'],'lg')}}/12 md:w-{{Arr::get($item['ratio'],'md')}}/12 sm:w-{{Arr::get($item['ratio'],'sm')}}/12">

                                            @switch(Arr::get($item['value'],'type'))

                                                @case('text')
                                                    <x-input
                                                            :label="Arr::get($item,'label',' ')"
                                                            wire:model="data.{{ $row['key'] }}.{{ Arr::get($item, 'key') }}"
                                                            :placeholder="Arr::get($item,'hint',' ')"
                                                            :value="Arr::get($item['value'],'value')"
                                                            :hint="Arr::get($item,'help')"
                                                            :type="Arr::get($item['value'],'type')"/>
                                                    @break

                                                @case('textarea')
                                                    <x-textarea
                                                            :label="Arr::get($item,'label',' ')"
                                                            wire:model="data.{{ $row['key'] }}.{{ Arr::get($item, 'key') }}"
                                                            :placeholder="Arr::get($item,'hint',' ')"
                                                            :value="Arr::get($item['value'],'value')"
                                                            :hint="Arr::get($item,'help')"
                                                            :type="Arr::get($item['value'],'type')"/>
                                                    @break

                                                @case('select')
                                                    <x-select
                                                            :label="Arr::get($item,'label',' ')"
                                                            wire:model.live="data.{{ $row['key'] }}.{{ Arr::get($item, 'key') }}"
                                                            :options="Arr::get($item['value'],'options',[])"
                                                            option-label="label"
                                                            option-value="value"
                                                            :hint="Arr::get($item,'help',' ')"
                                                    />
                                                    @break

                                                @case('number')
                                                    <x-input
                                                            type="number"
                                                            :label="Arr::get($item,'label',' ')"
                                                            wire:model="data.{{ $row['key'] }}.{{ Arr::get($item, 'key') }}"
                                                            :placeholder="Arr::get($item,'hint',' ')"
                                                            :value="Arr::get($item['value'],'value')"
                                                            :hint="Arr::get($item,'help')"
                                                            :type="Arr::get($item['value'],'type')"/>
                                                    @break
                                                @case('file')
                                                    <x-file
                                                            :label="Arr::get($item,'label',' ')"
                                                            wire:model="data.{{ $row['key'] }}.{{ Arr::get($item, 'key') }}"
                                                            :placeholder="Arr::get($item,'hint')"
                                                            :value="Arr::get($item['value'],'value')"
                                                            :hint="Arr::get($item,'help')"
                                                            :type="Arr::get($item['value'],'type')"/>
                                                    @break
                                            @endswitch
                                        </div>
                                    @endforeach

                                @else
                                    <div class="bg-redmt-3 p-1 lg:w-{{Arr::get($row['ratio'],'lg')}}/12 md:w-{{Arr::get($row['ratio'],'md')}}/12 sm:w-{{Arr::get($row['ratio'],'sm')}}/12">

                                        @switch(Arr::get($row['value'],'type'))

                                            @case('text')
                                                <x-input
                                                        :label="Arr::get($row,'label',' ')"
                                                        wire:model="data.{{ $row['key'] }}"
                                                        :placeholder="Arr::get($row,'hint')"
                                                        :value="Arr::get($row['value'],'value')"
                                                        :hint="Arr::get($row,'help')"
                                                        :type="Arr::get($row['value'],'type')"/>
                                                @break

                                            @case('textarea')
                                                <x-textarea
                                                        :label="Arr::get($row,'label',' ')"
                                                        wire:model="data.{{ $row['key'] }}"
                                                        :placeholder="Arr::get($row,'hint')"
                                                        :value="Arr::get($row['value'],'value')"
                                                        :hint="Arr::get($row,'help')"
                                                        :type="Arr::get($row['value'],'type')"/>
                                                @break

                                            @case('select')
                                                <x-select
                                                        :label="Arr::get($row,'label',' ')"
                                                        wire:model.live="data.{{ $row['key'] }}"
                                                        :options="Arr::get($row['value'],'options',[])"
                                                        option-label="label"
                                                        option-value="value"
                                                        :hint="Arr::get($row,'help',' ')"
                                                />
                                                @break

                                            @case('number')
                                                <x-input
                                                        type="number"
                                                        :label="Arr::get($row,'label',' ')"
                                                        wire:model="data.{{ $row['key'] }}"
                                                        :placeholder="Arr::get($row,'hint')"
                                                        :value="Arr::get($row['value'],'value')"
                                                        :hint="Arr::get($row,'help')"
                                                        :type="Arr::get($row['value'],'type')"/>
                                                @break

                                            @case('file')
                                                <x-file
                                                        :label="Arr::get($row,'label',' ')"
                                                        wire:model="data.{{ $row['key'] }}"
                                                        :placeholder="Arr::get($row,'hint')"
                                                        :value="Arr::get($row['value'],'value')"
                                                        :hint="Arr::get($row,'help')"
                                                        :type="Arr::get($row['value'],'type')"/>
                                                @break

                                        @endswitch
                                    </div>
                                @endif

                            @endforeach
                        </dl>

                    </div>

                    <x-slot:actions>
                        <x-button :label="trans('general.submit')" class="btn-primary btn-wide" wire:click="update" spinner="update"/>
                    </x-slot:actions>
                </x-card>
            </div>

        </div>
    </div>


</div>

