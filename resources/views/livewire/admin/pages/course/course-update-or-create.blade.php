@php
    use App\Enums\BooleanEnum;
    use App\Enums\CourseTypeEnum;
    use App\Helpers\Constants;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="grid grid-cols-1 col-span-2 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">
                    <x-input :label="trans('validation.attributes.title')" wire:model.blur="title" required/>
                    <x-input :label="trans('validation.attributes.description')" wire:model.blur="description" required/>
                    <x-admin.shared.tinymce wire:model.blur="body"/>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <x-select :label="trans('validation.attributes.category')" wire:model="category_id" :options="$categories" required/>
                        <x-select :label="trans('validation.attributes.teacher')" wire:model="teacher_id" :options="$teachers" required/>
                    </div>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                        <x-input :label="trans('validation.attributes.price')" wire:model.blur="price" type="number" step="0.01" min="0" required/>
                        <x-input :label="trans('validation.attributes.capacity')" wire:model.blur="capacity" type="number" min="1"/>
                        <x-select :label="trans('validation.attributes.type')" wire:model="type" :options="CourseTypeEnum::options()" required/>
                    </div>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                        <x-input :label="trans('validation.attributes.start_time')" wire:model.blur="start_time" type="time"/>
                        <x-input :label="trans('validation.attributes.end_time')" wire:model.blur="end_time" type="time"/>
                        <x-select :label="trans('validation.attributes.room')" wire:model="room_id" :options="$rooms"/>
                    </div>

                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <x-select :label="trans('validation.attributes.days_of_week')" wire:model="days_of_week" :options="$daysOptions" multiple/>
                        <x-input :label="trans('validation.attributes.meeting_link')" wire:model.blur="meeting_link" type="text"/>
                    </div>

                    <x-tags :label="trans('validation.attributes.tags')" wire:model="tags" icon="o-tag" clearable/>
                </div>
            </x-card>
        </div>

        <div class="col-span-1">
            <div class="sticky top-16">
                <x-card :title="trans('general.page_sections.upload_image')" shadow separator progress-indicator="submit" class="">
                    <x-admin.shared.single-file-upload :ratio="1280 / 720" :hint="croperHint(Constants::RESOLUTION_1280_720)" :default_image="$model->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720)"/>
                </x-card>

                <!-- No published flags on Course entity in new schema -->
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions/>
</form>
