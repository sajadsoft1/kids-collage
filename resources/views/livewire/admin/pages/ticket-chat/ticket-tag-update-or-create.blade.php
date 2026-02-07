<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <form wire:submit="submit">
        <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
            <div class="grid gap-4 md:grid-cols-2">
                <x-input wire:model="name" :label="__('validation.attributes.name')" required />
                <x-input wire:model="color" :label="__('validation.attributes.color')" placeholder="#3b82f6" />
            </div>
            <div class="mt-4">
                <x-textarea wire:model="description" :label="__('validation.attributes.description')" rows="3" />
            </div>
        </x-card>

        <div class="mt-6 flex gap-4">
            <x-button type="submit" class="btn-primary" spinner="submit" :label="__('general.submit')" />
            <a href="{{ route('admin.ticket-chat.tags.index') }}" wire:navigate class="btn btn-ghost">{{ __('general.cancel') }}</a>
        </div>
    </form>
</div>
