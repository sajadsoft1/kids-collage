@php
    use App\Enums\BooleanEnum;
    use App\Helpers\Constants;
    use App\Enums\UserTypeEnum;
@endphp

<form wire:submit.prevent="submit" enctype="multipart/form-data">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

        <div class="grid grid-cols-1 col-span-2 gap-4">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <x-input :label="trans('validation.attributes.first_name')" wire:model="name" required />

                    <x-input :label="trans('validation.attributes.last_name')" wire:model="family" required />

                    <x-input :label="trans('validation.attributes.email')" wire:model="email" type="email" required
                        placeholder="example@example.com" />

                    <x-input :label="trans('validation.attributes.mobile')" wire:model="mobile" type="tel" required placeholder="09123456789"
                        x-mask="99999999999" />

                    <x-select :label="trans('validation.attributes.gender')" wire:model="gender" :options="\App\Enums\GenderEnum::formatedCases()" option-value="value"
                        option-label="label" required />

                    <x-admin.shared.smart-datetime :label="trans('validation.attributes.birth_date')" wire-property-name="birth_date" />

                    <div class="md:col-span-2 lg:col-span-3">
                        <x-textarea :label="trans('validation.attributes.address')" wire:model="address" />
                    </div>

                    <x-select :label="trans('validation.attributes.religion')" wire:model="religion" :options="\App\Enums\ReligionEnum::formatedCases()" option-value="value"
                        option-label="label" />

                    <x-input :label="trans('validation.attributes.phone')" wire:model="phone" type="tel" placeholder="05135554466"
                        x-mask="99999999999" />

                    <x-input :label="trans('validation.attributes.national_code')" wire:model="national_code" minlength="10" maxlength="10"
                        x-mask="9999999999" placeholder="0770110505" />
                </div>
            </x-card>

            <x-card :title="trans('user.page.images_section')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <x-admin.shared.single-file-upload :hint="trans('user.page.image.hint')" :label="trans('user.page.image.avatar')" wire_model="avatar"
                        :default_image="$user->getFirstMediaUrl('avatar', Constants::RESOLUTION_480_SQUARE)" :crop_after_change="true" />
                    <x-admin.shared.single-file-upload :hint="trans('user.page.image.hint')" :label="trans('user.page.image.national_card')" wire_model="national_card"
                        :default_image="$user->getFirstMediaUrl('national_card', Constants::RESOLUTION_480_SQUARE)" :crop_after_change="true" />
                    <x-admin.shared.single-file-upload :hint="trans('user.page.image.hint')" :label="trans('user.page.image.birth_certificate')"
                        wire_model="birth_certificate" :default_image="$user->getFirstMediaUrl('birth_certificate', Constants::RESOLUTION_480_SQUARE)" :crop_after_change="true" />
                </div>
            </x-card>

            <x-card :title="trans('user.page.images_gallery')" shadow separator progress-indicator="submit">
                <x-image-gallery :images="[
                    $user->getFirstMediaUrl('avatar'),
                    $user->getFirstMediaUrl('national_card'),
                    $user->getFirstMediaUrl('birth_certificate'),
                ]" class="h-40 rounded-box col-span-3" />
            </x-card>
        </div>
        <div class="col-span-1 ">
            <div class="sticky top-20">
                @if (!$edit_mode)
                    <x-alert :title="trans('user.page.generatin_password_is_mobile_number')" icon="o-exclamation-triangle" class="alert-warning mb-5" />
                @endif
                <x-card :title="trans('user.page.parents_info')" shadow separator progress-indicator="submit" class="">
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <x-input :label="trans('validation.attributes.father_name')" wire:model="father_name" />
                        <x-input :label="trans('validation.attributes.father_phone')" wire:model="father_phone" type="tel" placeholder="09123456789"
                            x-mask="99999999999" />
                        <x-input :label="trans('validation.attributes.mother_name')" wire:model="mother_name" />
                        <x-input :label="trans('validation.attributes.mother_phone')" wire:model="mother_phone" type="tel" placeholder="09123456789"
                            x-mask="99999999999" />
                    </div>
                </x-card>

                <x-card :title="trans('datatable.setting')" shadow separator progress-indicator="submit" class="mt-5">

                    <div class="grid-cols-1 gap-4 d-lg-grid">
                        <x-toggle :label="trans('datatable.status')" wire:model="status" right />

                        @if ($detected_user_type !== UserTypeEnum::USER && $detected_user_type !== UserTypeEnum::PARENT)
                            <x-choices :label="trans('validation.attributes.role')" wire:model="selected_rules" multiple :options="$rules" />
                        @endif
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <x-admin.shared.form-actions />
</form>
