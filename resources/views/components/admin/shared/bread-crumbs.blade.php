<div class="bg-base-300 h-14 mb-2">
    <div class="flex items-center justify-between py-3">
        <div class="min-w-0 flex-1">
            <x-breadcrumbs :items="$breadcrumbs ?? $this->breadcrumbs" />
        </div>
        <div class="flex ms-4 join">
            @foreach (array_reverse($breadcrumbsActions ?? ($this->breadcrumbsActions ?? [])) as $action)
                @if (Arr::get($action, 'access', true))
                    <x-button :label="Arr::get($action, 'label')" :icon="Arr::get($action, 'icon')" :link="Arr::get($action, 'link')" @class([
                        'join-item btn-sm btn-outline btn-primary',
                        Arr::get($action, 'class'),
                    ]) />
                @endif
            @endforeach
        </div>
    </div>
</div>
