<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-input :label="trans('validation.attributes.name')" wire:model="name" />
            <x-input :label="trans('validation.attributes.location')" wire:model="location" />

            <x-input :label="trans('validation.attributes.capacity')" wire:model="capacity" type="number" min="1" />
        </div>
    </x-card>

    <x-admin.shared.form-actions />
</form>
