@php use App\Enums\BooleanEnum;use App\Helpers\Constants; @endphp

<form wire:submit.prevent="submit" enctype="multipart/form-data">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

        <div class="col-span-2 grid grid-cols-1 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <x-input
                            :label="trans('validation.attributes.first_name')"
                            wire:model="name"
                            required
                    />

                    <x-input
                            :label="trans('validation.attributes.last_name')"
                            wire:model="family"
                            required
                    />

                    <x-input
                            :label="trans('validation.attributes.email')"
                            wire:model="email"
                            type="email"
                            required
                    />

                    <x-input
                            :label="trans('validation.attributes.mobile')"
                            wire:model="mobile"
                            type="tel"
                            required
                    />


                </div>
            </x-card>
            @if(!$edit_mode)
                <x-card :title="trans('user.page.password_section')" shadow separator progress-indicator="submit">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <x-password
                                :label="trans('validation.attributes.password')"
                                wire:model="password"
                                right
                                required
                        />

                        <x-password
                                :label="trans('validation.attributes.password_confirmation')"
                                wire:model="password_confirmation"
                                right
                                required
                        />
                    </div>
                </x-card>
            @endif
        </div>
        <div class="col-span-1 ">
            <div class="sticky top-20">
                <x-card :title="trans('general.page_sections.upload_image')" shadow separator progress-indicator="submit" class="">
                    <x-admin.shared.single-file-upload
                            wire_model="avatar"
                            :default_image="$user->getFirstMediaUrl('avatar',Constants::RESOLUTION_480_SQUARE)"
                            :crop_after_change="true"/>

                </x-card>

                <x-card :title="trans('datatable.setting')" shadow separator progress-indicator="submit" class="mt-5">

                    <div class="d-lg-grid grid-cols-1 gap-4">
                        <x-toggle :label="trans('datatable.status')" wire:model="status" right/>

                        <x-choices
                                :label="trans('validation.attributes.role')"
                                wire:model="selected_rules"
                                multiple
                                :options="$rules"
                        />
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions/>
</form>
