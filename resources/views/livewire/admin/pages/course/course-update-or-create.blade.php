@php
    use App\Enums\BooleanEnum;
    use App\Enums\CourseTypeEnum;
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
                    <x-admin.shared.tinymce wire:model.blur="body" />
                    <x-tags :label="trans('validation.attributes.tags')" wire:model="tags" icon="o-tag" clearable />
                </div>
            </x-card>
        </div>

        <div class="col-span-1">
            <div class="sticky top-16">
                <x-card :title="trans('general.page_sections.upload_image')" shadow separator progress-indicator="submit" class="">
                    <x-admin.shared.single-file-upload :ratio="1280 / 720" :hint="croperHint(Constants::RESOLUTION_1280_720)" :default_image="$courseTemplate->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720)" />
                </x-card>

                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit" class="mt-5">
                    <div class="grid grid-cols-1 gap-4">
                        <x-select :label="trans('validation.attributes.category')" wire:model="category_id" :options="$categories" required
                            option-value="value" option-label="label" />
                        <x-choices-offline single searchable :label="trans('validation.attributes.teacher')" wire:model="teacher_id"
                            option-value="value" option-label="label" :options="$teachers" required />
                        <x-input :label="trans('validation.attributes.price')" wire:model.blur="price" type="number" step="0.01" min="0"
                            required />
                        <x-input :label="trans('validation.attributes.capacity')" wire:model.blur="capacity" type="number" min="1" />
                        <x-select :label="trans('validation.attributes.type')" wire:model="type" :options="CourseTypeEnum::options()" required option-value="value"
                            option-label="label" />
                        <x-select :label="trans('validation.attributes.room_id')" wire:model="room_id" :options="$rooms" option-value="value"
                            option-label="label" />
                    </div>
                </x-card>

                <!-- No published flags on Course entity in new schema -->
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions />
</form>
