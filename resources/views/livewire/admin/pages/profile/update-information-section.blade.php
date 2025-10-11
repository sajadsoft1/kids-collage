<form wire:submit.prevent="save">
    <x-card :title="trans('user.profile.tabs.information-tab')" shadow separator progress-indicator="save">
        {{-- Personal Information Section --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            {{-- Name --}}
            <x-input :label="trans('validation.attributes.first_name')" wire:model="name" required icon="o-user" />

            {{-- Family Name --}}
            <x-input :label="trans('validation.attributes.last_name')" wire:model="family" required icon="o-user" />

            {{-- Email --}}
            <x-input :label="trans('validation.attributes.email')" wire:model="email" type="email" placeholder="example@example.com"
                icon="o-envelope" />

            {{-- Mobile --}}
            <x-input :label="trans('validation.attributes.mobile')" wire:model="mobile" type="tel" placeholder="09123456789" x-mask="99999999999"
                icon="o-phone" />

            {{-- Gender --}}
            <x-select :label="trans('validation.attributes.gender')" wire:model="gender" :options="\App\Enums\GenderEnum::formatedCases()" option-value="value" option-label="label"
                icon="o-user-circle" />

            {{-- Birth Date --}}
            <x-admin.shared.smart-datetime :label="trans('validation.attributes.birth_date')" wire-property-name="birth_date" :required="false" />

            {{-- National Code --}}
            <x-input :label="trans('validation.attributes.national_code')" wire:model="national_code" minlength="10" maxlength="10" x-mask="9999999999"
                placeholder="0770110505" icon="o-identification" />

            {{-- Phone --}}
            <x-input :label="trans('validation.attributes.phone')" wire:model="phone" type="tel" placeholder="05135554466" x-mask="99999999999"
                icon="o-phone" />

            {{-- Religion --}}
            <x-select :label="trans('validation.attributes.religion')" wire:model="religion" :options="\App\Enums\ReligionEnum::formatedCases()" option-value="value"
                option-label="label" />

            {{-- Address - Full Width --}}
            <div class="md:col-span-2 lg:col-span-3">
                <x-textarea :label="trans('validation.attributes.address')" wire:model="address" rows="3" icon="o-map-pin" />
            </div>
        </div>

        {{-- Form Actions --}}
        <x-slot:actions>
            <x-button label="{{ trans('general.submit') }}" type="submit" spinner="save" icon="o-check"
                class="btn-primary" />
        </x-slot:actions>
    </x-card>
</form>
