<form wire:submit.prevent="submit" enctype="multipart/form-data">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

        <div class="grid grid-cols-1 col-span-2 gap-4">
            <x-card shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">

                    <x-textarea :label="trans('validation.attributes.message')" wire:model="comment" readonly />
                    <x-textarea :label="trans('validation.attributes.note')" wire:model="admin_note" />
                </div>
            </x-card>
        </div>
        <div class="col-span-1">
            <div class="sticky top-20">
                <x-card shadow separator progress-indicator="submit">
                    <div class="grid grid-cols-1 gap-4">
                        <x-input :label="trans('validation.attributes.name')" wire:model="name" readonly />
                        <x-input :label="trans('validation.attributes.email')" wire:model="email" readonly />
                        <x-input :label="trans('validation.attributes.mobile')" wire:model="mobile" readonly />
                        <x-toggle :label="trans('validation.attributes.follow_up')" wire:model="follow_up" right value="1" />
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions />
</form>
