@php use App\Enums\BooleanEnum; @endphp
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
            <x-toggle :label="trans('datatable.status')" wire:model="published" right/>
        </div>
    </x-card>

    <x-admin.shared.form-actions/>
</form>
