@php
    use App\Enums\BooleanEnum;
     use App\Helpers\Constants;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="grid grid-cols-1 col-span-2 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">
                    <x-input :label="trans('validation.attributes.title')" wire:model="title" required/>
                    <x-input :label="trans('validation.attributes.description')" wire:model="description"
                             required/>
                    <x-admin.shared.tinymce wire:model="body"/>
                    <x-tags :label="trans('validation.attributes.tags')" wire:model="tags" icon="o-tag" clearable/>
                </div>
            </x-card>
        </div>

        <div class="col-span-1">
            <div class="sticky top-16">
                <x-card :title="trans('general.page_sections.upload_image')" shadow separator
                        progress-indicator="submit" class="">
                    <x-admin.shared.single-file-upload :ratio="1280 / 720"
                                                       :hint="croperHint(Constants::RESOLUTION_1280_720)"
                                                       :default_image="$model->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720)"/>
                </x-card>

                <x-card :title="trans('general.page_sections.publish_config')" shadow separator
                        progress-indicator="submit" class="mt-5">
                    <div class="grid grid-cols-1 gap-4">
                        <x-admin.shared.published-config :has-published-at="true" :default-date="$published_at"/>

                        <x-toggle :label="trans('validation.attributes.is_online')" wire:model.live="is_online" right
                                  value="0"/>

                        @if($is_online)
                            <x-input type="url" :label="trans('validation.attributes.link')" wire:model="location"
                                     required/>
                        @else
                            <x-textarea :label="trans('validation.attributes.location')" wire:model="location"
                                        required/>
                        @endif


                        <x-admin.shared.smart-datetime
                            wire-property-name="start_date"
                            :default-date="$start_date"
                            :label="trans('validation.attributes.start_date')"/>
                        <x-admin.shared.smart-datetime :default-date="$end_date"
                                                       wire-property-name="end_date"
                                                       :label="trans('validation.attributes.end_date')"/>
                        <x-input :label="trans('validation.attributes.price')" wire:model="price"
                                 :suffix="systemCurrency()" required/>
                        <x-input :label="trans('validation.attributes.capacity')" wire:model="capacity"
                                 :suffix="trans('validation.attributes.person')" required/>
                    </div>


                </x-card>
            </div>
        </div>


    </div>

    <x-admin.shared.form-actions/>
</form>
