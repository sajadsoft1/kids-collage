@php use App\Enums\TermStatus; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-input :label="trans('validation.attributes.title')" wire:model="title" required />
            <x-input :label="trans('validation.attributes.description')" wire:model="description" required />

            <x-jalali-datepicker wire:model="start_date" :label="trans('validation.attributes.start_date')" jalali export-calendar="gregorian"
                name="start_date" export-format="Y-m-d" :required="true" />

            <x-select :label="trans('datatable.status')" option-label="label" option-value="value" wire:model="status" :placeholder="trans('general.please_select_an_option')"
                placeholder-value="" :options="TermStatus::options()" required />

            <x-jalali-datepicker wire:model="end_date" :label="trans('validation.attributes.end_date')" jalali export-calendar="gregorian"
                name="end_date" export-format="Y-m-d" :required="true" />


        </div>
    </x-card>

    <x-admin.shared.form-actions />
</form>
