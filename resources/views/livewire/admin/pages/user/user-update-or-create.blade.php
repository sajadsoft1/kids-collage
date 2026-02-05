@php
    use App\Enums\UserTypeEnum;
@endphp

<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    @if (!$edit_mode)
        <x-alert :title="trans('user.page.generatin_password_is_mobile_number')" icon="o-exclamation-triangle" class="mb-5 alert-warning" />
    @endif

    <x-tabs wire:model="activeTab" class="mt-4">
        <x-tab name="basic" :label="trans('user.page.tabs.basic')">
            <livewire:admin.pages.user.tabs.user-tab-basic :user="$user" :detected_route_name="$detected_route_name" :detected_user_type="$detected_user_type"
                :key="'basic-' . ($user->id ?? 'new')" />
        </x-tab>

        @if ($edit_mode)
            <x-tab name="images" :label="trans('user.page.tabs.images')">
                <livewire:admin.pages.user.tabs.user-tab-images :user="$user" :detected_user_type="$detected_user_type" :detected_route_name="$detected_route_name"
                    :key="'images-' . $user->id" />
            </x-tab>

            <x-tab name="parents" :label="trans('user.page.tabs.parents')">
                <livewire:admin.pages.user.tabs.user-tab-parents :user="$user" :detected_user_type="$detected_user_type"
                    :detected_route_name="$detected_route_name" :male_parents="$male_parents" :female_parents="$female_parents" :childrens="$childrens" :key="'parents-' . $user->id" />
            </x-tab>

            @if (in_array($detected_user_type, [UserTypeEnum::EMPLOYEE, UserTypeEnum::TEACHER]))
                <x-tab name="salary" :label="trans('user.page.tabs.salary')">
                    <livewire:admin.pages.user.tabs.user-tab-salary :user="$user" :detected_user_type="$detected_user_type"
                        :detected_route_name="$detected_route_name" :key="'salary-' . $user->id" />
                </x-tab>

                <x-tab name="resume" :label="trans('user.page.tabs.resume')">
                    <livewire:admin.pages.user.tabs.user-tab-resume :user="$user" :detected_user_type="$detected_user_type"
                        :detected_route_name="$detected_route_name" :key="'resume-' . $user->id" />
                </x-tab>
            @endif

            <x-tab name="settings" :label="trans('user.page.tabs.settings')">
                <livewire:admin.pages.user.tabs.user-tab-settings :user="$user" :detected_user_type="$detected_user_type"
                    :detected_route_name="$detected_route_name" :rules="$roles" :key="'settings-' . $user->id" />
            </x-tab>
        @endif
    </x-tabs>
</div>
