@php
    $allActions = array_values(array_filter(
        array_reverse($breadcrumbsActions ?? ($this->breadcrumbsActions ?? [])),
        fn ($action) => Arr::get($action, 'access', true)
    ));
    $firstAction = $allActions[0] ?? null;
    $secondAction = $allActions[1] ?? null;
    $dropdownActionsMobile = array_slice($allActions, 1);
    $dropdownActionsDesktop = array_slice($allActions, 2);
@endphp
<div class="mb-2 min-h-14">
    <div class="flex justify-between items-center py-3 gap-2">
        <div class="flex-1 min-w-0">
            <x-breadcrumbs :items="$breadcrumbs ?? $this->breadcrumbs" />
        </div>
        <div class="flex flex-shrink-0 ms-2">
            <div class="flex join">
                {{-- اکشن اول: همیشه --}}
                @if ($firstAction)
                    @if (Arr::get($firstAction, 'action'))
                        <x-button :label="Arr::get($firstAction, 'label')" :icon="Arr::get($firstAction, 'icon')" wire:click="{{ Arr::get($firstAction, 'action') }}"
                            :spinner="Arr::get($firstAction, 'action')" @class([
                                'join-item btn-sm btn-outline btn-primary',
                                Arr::get($firstAction, 'class'),
                            ]) />
                    @else
                        <x-button :label="Arr::get($firstAction, 'label')" :icon="Arr::get($firstAction, 'icon')" :link="Arr::get($firstAction, 'link')" @class([
                            'join-item btn-sm btn-outline btn-primary',
                            Arr::get($firstAction, 'class'),
                        ]) />
                    @endif
                @endif
                {{-- اکشن دوم: فقط دسکتاپ (lg) --}}
                @if ($secondAction)
                    @if (Arr::get($secondAction, 'action'))
                        <x-button :label="Arr::get($secondAction, 'label')" :icon="Arr::get($secondAction, 'icon')" wire:click="{{ Arr::get($secondAction, 'action') }}"
                            :spinner="Arr::get($secondAction, 'action')" @class([
                                'join-item btn-sm btn-outline btn-primary hidden lg:inline-flex',
                                Arr::get($secondAction, 'class'),
                            ]) />
                    @else
                        <x-button :label="Arr::get($secondAction, 'label')" :icon="Arr::get($secondAction, 'icon')" :link="Arr::get($secondAction, 'link')" @class([
                            'join-item btn-sm btn-outline btn-primary hidden lg:inline-flex',
                            Arr::get($secondAction, 'class'),
                        ]) />
                    @endif
                @endif
                {{-- دراپ‌داون موبایل/تبلت: از اکشن دوم به بعد --}}
                @if (count($dropdownActionsMobile) > 0)
                    <span class="lg:hidden">
                    <x-dropdown right>
                        <x-slot:trigger>
                            <x-button icon="s-ellipsis-vertical" class="join-item btn-sm btn-outline btn-primary" />
                        </x-slot:trigger>
                        <x-menu class="w-48">
                            @foreach ($dropdownActionsMobile as $action)
                                @if (Arr::get($action, 'action'))
                                    <x-menu-item
                                        :title="Arr::get($action, 'label')"
                                        :icon="Arr::get($action, 'icon')"
                                        wire:click="{{ Arr::get($action, 'action') }}"
                                        :spinner="Arr::get($action, 'action')"
                                    />
                                @else
                                    <x-menu-item
                                        :title="Arr::get($action, 'label')"
                                        :icon="Arr::get($action, 'icon')"
                                        :link="Arr::get($action, 'link')"
                                    />
                                @endif
                            @endforeach
                        </x-menu>
                    </x-dropdown>
                    </span>
                @endif
                {{-- دراپ‌داون دسکتاپ: از اکشن سوم به بعد --}}
                @if (count($dropdownActionsDesktop) > 0)
                    <span class="hidden lg:inline-block">
                    <x-dropdown right>
                        <x-slot:trigger>
                            <x-button icon="s-ellipsis-vertical" class="join-item btn-sm btn-outline btn-primary" />
                        </x-slot:trigger>
                        <x-menu class="w-48">
                            @foreach ($dropdownActionsDesktop as $action)
                                @if (Arr::get($action, 'action'))
                                    <x-menu-item
                                        :title="Arr::get($action, 'label')"
                                        :icon="Arr::get($action, 'icon')"
                                        wire:click="{{ Arr::get($action, 'action') }}"
                                        :spinner="Arr::get($action, 'action')"
                                    />
                                @else
                                    <x-menu-item
                                        :title="Arr::get($action, 'label')"
                                        :icon="Arr::get($action, 'icon')"
                                        :link="Arr::get($action, 'link')"
                                    />
                                @endif
                            @endforeach
                        </x-menu>
                    </x-dropdown>
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
