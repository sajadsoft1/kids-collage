@php
    use App\Helpers\Constants;
@endphp
<form wire:submit.prevent="submit" enctype="multipart/form-data">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

        <div class="col-span-2 grid grid-cols-1 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">
                    <x-input :label="trans('validation.attributes.title')" wire:model="title" required />
                    <x-input :label="trans('validation.attributes.description')" wire:model="description" required />
                    <x-admin.shared.tinymce wire:model="body" />
                    <x-select :label="trans('validation.attributes.type')" wire:model="type" :options="\App\Enums\CategoryTypeEnum::formatedCases()" option-value="value"
                        option-label="label" required />
                </div>
            </x-card>

        </div>
        <div class="col-span-1 ">
            <div class="sticky top-20">
                <x-card :title="trans('general.page_sections.upload_image')" shadow separator progress-indicator="submit" class="">
                    <x-admin.shared.single-file-upload
                        default_image="{{ $model->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE) }}" />
                </x-card>

                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit" class="mt-5">
                    <x-admin.shared.published-config has-published-at="0" />
                </x-card>
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions />
</form>
