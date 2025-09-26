@php
    use App\Helpers\Constants;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="grid grid-cols-1 col-span-2 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">
                    <x-input :label="trans('validation.attributes.title')" wire:model.blur="title" required />
                    <x-input :label="trans('validation.attributes.description')" wire:model.blur="description" required />
                </div>
            </x-card>
        </div>

        <div class="col-span-1">
            <div class="sticky top-16">
                <x-card :title="trans('general.page_sections.upload_image')" shadow separator progress-indicator="submit" class="">
                    <x-admin.shared.single-file-upload :ratio="1280 / 720" :hint="croperHint(Constants::RESOLUTION_1280_720)" :default_image="$model->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720)" />
                </x-card>
            </div>
        </div>

    </div>

    <x-admin.shared.form-actions />
</form>
