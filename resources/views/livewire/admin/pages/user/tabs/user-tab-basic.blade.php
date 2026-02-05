<form wire:submit.prevent="submit">
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            <x-input :label="trans('validation.attributes.first_name')" wire:model="name" required />
            <x-input :label="trans('validation.attributes.last_name')" wire:model="family" required />
            <x-input :label="trans('validation.attributes.email')" wire:model="email" type="email" placeholder="example@example.com" />
            <x-input :label="trans('validation.attributes.mobile')" wire:model="mobile" type="tel" required placeholder="09123456789"
                x-mask="99999999999" />
            <x-select :label="trans('validation.attributes.gender')" wire:model="gender" :options="\App\Enums\GenderEnum::formatedCases()" option-value="value" :placeholder="trans('general.please_select_an_option')"
                placeholder-value="" option-label="label" required />
            <x-jalali-datepicker wire:model="birth_date" :label="trans('validation.attributes.birth_date')" jalali export-calendar="gregorian"
                export-format="Y-m-d" :placeholder="trans('validation.attributes.birth_date')" />
            <div class="md:col-span-2 lg:col-span-3">
                <x-textarea :label="trans('validation.attributes.address')" wire:model="address" />
            </div>
            <div class="md:col-span-2 lg:col-span-3">
                <x-textarea :label="trans('validation.attributes.biography')" wire:model="biography" />
            </div>
            <div class="md:col-span-2 lg:col-span-3">
                <x-textarea :label="trans('validation.attributes.sickness')" wire:model="sickness" />
            </div>
            <div class="md:col-span-2 lg:col-span-3">
                <x-textarea :label="trans('validation.attributes.delivery_recipient')" wire:model="delivery_recipient" />
            </div>
            <x-select :label="trans('validation.attributes.religion')" wire:model="religion" :options="\App\Enums\ReligionEnum::formatedCases()" option-value="value"
                option-label="label" />
            <x-input :label="trans('validation.attributes.phone')" wire:model="phone" type="tel" placeholder="05135554466"
                x-mask="99999999999" />
            <x-input :label="trans('validation.attributes.national_code')" wire:model="national_code" minlength="10" maxlength="10" x-mask="9999999999"
                placeholder="0770110505" />
        </div>
    </x-card>
    <x-admin.shared.form-actions />
</form>
