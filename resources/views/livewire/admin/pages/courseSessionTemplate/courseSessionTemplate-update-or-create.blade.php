@php use App\Enums\BooleanEnum;use App\Enums\SessionType; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-input :label="trans('validation.attributes.title')"
                     wire:model="title"
            />
            <x-input :label="trans('validation.attributes.description')"
                     wire:model="description"
            />

            <x-select :label="trans('validation.attributes.type')"
                      :options="SessionType::options()"
                      option-label="label"
                      option-value="value"
                      wire:model="type"
            />


            <x-input :label="trans('validation.attributes.duration_minutes')"
                     wire:model="duration_minutes"
                     type="number"
                     step="1"
                     min="1"
            />

        </div>
    </x-card>

    <x-admin.shared.form-actions/>
</form>
