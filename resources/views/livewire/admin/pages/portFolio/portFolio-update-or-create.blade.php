@php use App\Enums\BooleanEnum; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="col-span-2">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <x-input :label="trans('validation.attributes.title')" wire:model="title" />
                    <x-input :label="trans('validation.attributes.description')" wire:model="description" />
                </div>
            </x-card>
        </div>
        <div class="col-span-1">
            <div class="sticky top-20">
                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit">
                    <x-admin.shared.published-config :has-published-at="$this->getHasPublishedAtProperty()" :default-date="$published_at" />
                </x-card>
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions />
</form>
