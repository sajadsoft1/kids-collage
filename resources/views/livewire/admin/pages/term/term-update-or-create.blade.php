@php use App\Enums\TermStatus; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-input :label="trans('validation.attributes.title')" wire:model="title" required />
            <x-input :label="trans('validation.attributes.description')" wire:model="description" required />
            <x-select :label="trans('datatable.status')" option-label="label" option-value="value" wire:model="status" :placeholder="trans('general.please_select_an_option')"
                placeholder-value="" :options="TermStatus::options()" required />
            <x-admin.shared.smart-datetime :label="trans('validation.attributes.start_date')" wire-property-name="start_date" required />
            <x-admin.shared.smart-datetime :label="trans('validation.attributes.end_date')" wire-property-name="end_date" required />
        </div>
    </x-card>

    <x-admin.shared.form-actions />
</form>
