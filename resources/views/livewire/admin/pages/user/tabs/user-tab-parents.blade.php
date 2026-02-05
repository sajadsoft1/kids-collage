<form wire:submit.prevent="submit">
    <x-card :title="trans('user.page.parents_info')" shadow separator progress-indicator="submit">
        @if (in_array($detected_user_type, [\App\Enums\UserTypeEnum::EMPLOYEE, \App\Enums\UserTypeEnum::TEACHER]))
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <x-input :label="trans('validation.attributes.father_name')" wire:model="father_name" />
                <x-input :label="trans('validation.attributes.father_phone')" wire:model="father_phone" type="tel" placeholder="09123456789"
                    x-mask="99999999999" />
                <x-input :label="trans('validation.attributes.father_age')" wire:model="father_age" type="number" min="1" max="150"
                    :placeholder="trans('validation.attributes.father_age')" />
                <x-input :label="trans('validation.attributes.father_education')" wire:model="father_education" />
                <div class="lg:col-span-2">
                    <x-textarea :label="trans('validation.attributes.father_workplace')" wire:model="father_workplace" />
                </div>
                <x-input :label="trans('validation.attributes.mother_name')" wire:model="mother_name" />
                <x-input :label="trans('validation.attributes.mother_phone')" wire:model="mother_phone" type="tel" placeholder="09123456789"
                    x-mask="99999999999" />
                <x-input :label="trans('validation.attributes.mother_age')" wire:model="mother_age" type="number" min="1" max="150"
                    :placeholder="trans('validation.attributes.mother_age')" />
                <x-input :label="trans('validation.attributes.mother_education')" wire:model="mother_education" />
                <div class="lg:col-span-2">
                    <x-textarea :label="trans('validation.attributes.mother_workplace')" wire:model="mother_workplace" />
                </div>
            </div>
        @elseif($detected_user_type == \App\Enums\UserTypeEnum::USER)
            <x-alert :title="trans('user.validation.at_least_one_parent_required')" icon="o-exclamation-triangle" class="mb-4 alert-info" />
            <x-choices-offline :label="trans('validation.attributes.father_name')" wire:model="father_id" :options="$male_parents" clearable
                option-value="value" :placeholder="trans('user.page.select_father_placeholder')" placeholder-value="0" option-label="label" single searchable />
            <x-choices-offline :label="trans('validation.attributes.mother_name')" wire:model="mother_id" :options="$female_parents" clearable
                option-value="value" :placeholder="trans('user.page.select_mother_placeholder')" placeholder-value="0" option-label="label" single searchable />
        @else
            <x-choices-offline :label="trans('validation.attributes.children')" wire:model="children_id" :options="$childrens" clearable
                option-value="value" :placeholder="trans('user.page.select_children_placeholder')" placeholder-value="0" option-label="label" searchable />
        @endif
    </x-card>
    <x-admin.shared.form-actions />
</form>
