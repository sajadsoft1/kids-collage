<div class="mb-2 h-14">
    <div class="flex justify-between items-center py-3">
        <div class="flex-1 min-w-0">
            <x-breadcrumbs :items="$breadcrumbs ?? $this->breadcrumbs" />
        </div>
        <div class="flex ms-4 join">
            @foreach (array_reverse($breadcrumbsActions ?? ($this->breadcrumbsActions ?? [])) as $action)
                @if (Arr::get($action, 'access', true))
                    @if (Arr::get($action, 'action'))
                        <x-button :label="Arr::get($action, 'label')" :icon="Arr::get($action, 'icon')" wire:click="{{ Arr::get($action, 'action') }}"
                            :spinner="Arr::get($action, 'action')" @class([
                                'join-item btn-sm btn-outline btn-primary',
                                Arr::get($action, 'class'),
                            ]) />
                    @else
                        <x-button :label="Arr::get($action, 'label')" :icon="Arr::get($action, 'icon')" :link="Arr::get($action, 'link')" @class([
                            'join-item btn-sm btn-outline btn-primary',
                            Arr::get($action, 'class'),
                        ]) />
                    @endif
                @endif
            @endforeach
        </div>
    </div>
</div>
