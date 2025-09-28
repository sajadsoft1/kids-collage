<form wire:submit.prevent="submit" enctype="multipart/form-data">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

        <div class="grid grid-cols-1 col-span-2 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">
                    <div class="grid grid-cols-2 gap-x-4">
                        <x-select :label="trans('validation.attributes.comment_for')" wire:model.live="morphable_type" :options="$objects"
                            option-label="value" option-value="key" required />
                        <x-select :label="trans('validation.attributes.title')" wire:model="morphable_id" :options="$object_values" option-label="title"
                            option-value="id" wire:loading.attr="disabled" wire:target="morphable_type" required />
                    </div>
                    <x-select :label="trans('validation.attributes.username')" wire:model="user_id" :options="$admins" option-label="name"
                        option-value="id" required />
                    <x-textarea :label="trans('validation.attributes.comment')" wire:model="comment" required />
                    <div class="grid grid-cols-1 gap-4">
                        <x-select :label="trans('validation.attributes.admin_name')" wire:model="admin_id" :options="$admins" option-label="name"
                            option-value="id" />
                        <x-textarea :label="trans('validation.attributes.admin_note')" wire:model="admin_note" />

                    </div>

                </div>
            </x-card>
        </div>
        <div class="col-span-1">
            <div class="sticky top-20">
                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit">
                    <x-admin.shared.published-config :has-published-at="true" :default-date="$published_at" />
                </x-card>
                {{--                <x-card :title="trans('setting.model')" shadow separator --}}
                {{--                        progress-indicator="submit" class="mt-5"> --}}
                {{--                    <div class="grid gap-4 grid-col-1"> --}}
                {{--                        <x-input :label="trans('validation.attributes.ordering')" --}}
                {{--                                 wire:model="ordering" type="number" --}}
                {{--                        /> --}}
                {{--                    </div> --}}
                {{--                </x-card> --}}
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions />
</form>
