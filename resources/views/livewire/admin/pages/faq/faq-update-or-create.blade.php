<form wire:submit.prevent="submit" enctype="multipart/form-data">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

        <div class="col-span-2 grid grid-cols-1 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">
                    <x-input :label="trans('validation.attributes.title')" wire:model="title" required />
                    <x-textarea :label="trans('validation.attributes.description')" wire:model="description" required />
                    <x-select :label="trans('validation.attributes.category')" wire:model="category_id" :options="$categories" option-label="title"
                        option-value="id" required />
                </div>
            </x-card>
        </div>
        <div class="col-span-1 ">
            <div class="sticky top-20">
                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit" class="">
                    <x-admin.shared.published-config :has-published-at="$this->getHasPublishedAtProperty()" :default-date="$published_at" />
                </x-card>
                <x-card :title="trans('setting.model')" shadow separator progress-indicator="submit" class="mt-5">
                    <div class="grid grid-col-1 gap-4 ">
                        <x-input :label="trans('validation.attributes.ordering')" wire:model="ordering" type="number" required />
                        <x-toggle :label="trans('validation.attributes.favorite_faq')" wire:model="favorite" right />
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions />
</form>
