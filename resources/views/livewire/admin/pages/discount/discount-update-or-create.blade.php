@php
    use App\Enums\BooleanEnum;use App\Enums\DiscountTypeEnum;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>

    {{-- Basic Information Card --}}
    <x-card :title="trans('discount.page.fields.code') . ' & ' . trans('discount.page.fields.type')" shadow separator
            progress-indicator="submit" class="mt-5">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <x-input :label="trans('discount.page.fields.code')" :placeholder="trans('discount.page.placeholders.code')"
                     :hint="trans('discount.page.help.code')" wire:model="code" uppercase required/>

            <x-select :label="trans('discount.page.fields.type')"
                      :placeholder="trans('general.please_select_an_option')" :hint="trans('discount.page.help.type')"
                      wire:model.live="type" :options="$discountTypes"
                      option-label="label" option-value="value" required/>

            <x-input :label="trans('discount.page.fields.value')"
                     :placeholder="trans('discount.page.placeholders.value')" :hint="trans('discount.page.help.value')"
                     wire:model="value" type="number"
                     step="0.01" min="0" :max="$type === DiscountTypeEnum::PERCENTAGE->value ? '100' : null"
                     suffix="{{ $type === DiscountTypeEnum::PERCENTAGE->value ? '%' : systemCurrency() }}" required/>
        </div>
    </x-card>

    {{-- Restrictions Card --}}
    <x-card :title="trans('discount.page.restrictions')" shadow separator class="mt-5">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <x-select :label="trans('discount.page.fields.user')" :placeholder="trans('discount.page.no_restrictions')"
                      :hint="trans('discount.page.help.user')" wire:model="user_id" :options="$users"
                      option-label="label" option-value="value" clearable/>

            <x-input :label="trans('discount.page.fields.min_order_amount')"
                     :placeholder="trans('discount.page.placeholders.min_order_amount')"
                     :hint="trans('discount.page.help.min_order_amount')" wire:model="min_order_amount"
                     type="number" step="0.01" min="0" :prefix="systemCurrency()" required/>

            @if ($type === DiscountTypeEnum::PERCENTAGE->value)
                <x-input :label="trans('discount.page.fields.max_discount_amount')"
                         :placeholder="trans('discount.page.placeholders.max_discount_amount')"
                         :hint="trans('discount.page.help.max_discount_amount')" wire:model="max_discount_amount"
                         type="number" step="0.01" min="0" :prefix="systemCurrency()"/>
            @endif
        </div>
    </x-card>

    {{-- Usage Limits Card --}}
    <x-card :title="trans('discount.page.usage_limits')" shadow separator class="mt-5">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-input :label="trans('discount.page.fields.usage_limit')"
                     :placeholder="trans('discount.page.placeholders.usage_limit')"
                     :hint="trans('discount.page.help.usage_limit')" wire:model="usage_limit" type="number"
                     min="1" :prefix="trans('discount.page.person_usage_unit')"/>

            <x-input :label="trans('discount.page.fields.usage_per_user')"
                     :hint="trans('discount.page.help.usage_per_user')" wire:model="usage_per_user" type="number"
                     min="1"
                     required :prefix="trans('discount.page.person_usage_unit')"/>
        </div>

        @if ($edit_mode)
            <div class="mt-4 alert alert-info">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     class="w-6 h-6 stroke-current shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ trans('discount.page.fields.used_count') }}: <strong>{{ $model->used_count }}</strong></span>
            </div>
        @endif
    </x-card>

    {{-- Date & Status Card --}}
    <x-card :title="trans('discount.page.usage_schedule') . ' & ' . trans('validation.attributes.status')" shadow
            separator class="mt-5">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

            <x-admin.shared.smart-datetime :label="trans('validation.attributes.start_date')"
                                           :default-date="$starts_at"
                                           wire-property-name="starts_at"/>
            <x-admin.shared.smart-datetime :label="trans('validation.attributes.expired_at')"
                                           :default-date="$expires_at"
                                           wire-property-name="expires_at"/>

            <x-select :label="trans('validation.attributes.status')" wire:model="is_active"
                      :options="BooleanEnum::options()" option-label="label" option-value="value"/>
        </div>

        @if ($edit_mode)
            <div class="mt-4">
                <div class="badge badge-lg {{ $model->isValid() ? 'badge-success' : 'badge-error' }}">
                    {{ trans('discount.page.fields.status') }}:
                    <x-badge :value="$model->getStatusText()['label']"
                             class="badge-{{$model->getStatusText()['color']}}"/>
                </div>
            </div>
        @endif
    </x-card>

    {{-- Description Card --}}
    <x-card :title="trans('discount.page.fields.description')" shadow separator class="mt-5">
        <x-textarea :label="trans('discount.page.fields.description')"
                    :placeholder="trans('discount.page.placeholders.description')" wire:model="description" rows="3"/>
    </x-card>

    <x-admin.shared.form-actions/>
</form>
