@php use App\Enums\BooleanEnum; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid gap-4">
            <x-input :label="trans('validation.attributes.title')"
                     wire:model="title"
            />

            <x-admin.shared.tinymce wire:model="front" :label="trans('validation.attributes.front')"/>
            <x-admin.shared.tinymce wire:model="back" :label="trans('validation.attributes.back')"/>
        </div>
    </x-card>

    <x-admin.shared.form-actions/>
</form>
