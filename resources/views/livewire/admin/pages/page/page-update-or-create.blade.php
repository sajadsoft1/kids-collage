@php
    use App\Enums\BooleanEnum;
    use App\Helpers\Constants;
@endphp
<form wire:submit="submit" enctype="multipart/form-data">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="col-span-2 grid grid-cols-1 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">
                    <x-input :label="trans('validation.attributes.title')"
                             wire:model="title" required
                    />
                    <x-select
                        :label="trans('validation.attributes.type')"
                        wire:model="type"
                        :options="\App\Enums\PageTypeEnum::formatedCases()"
                        option-value="value"
                        option-label="label"
                        required
                    />
                    <x-admin.shared.tinymce wire:model.blur="body"/>
                </div>
            </x-card>
            <x-card :title="trans('general.page_sections.seo_options')" shadow separator progress-indicator="submit">
                <x-admin.shared.seo-options class="lg:grid-cols-1"/>
            </x-card>
        </div>
        <div class="col-span-1">
            <div class="sticky top-20">
                <x-card :title="trans('general.page_sections.upload_image')" shadow separator progress-indicator="submit" class="">
                    <x-admin.shared.single-file-upload
                        default_image="{{ $model->getFirstMediaUrl('image', Constants::RESOLUTION_100_SQUARE) }}"
                    />
                </x-card>
            </div>
        </div>
    </div>



    <x-admin.shared.form-actions/>
</form>
