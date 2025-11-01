@php use App\Enums\BooleanEnum; @endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <x-tabs wire:model="selectedTab" active-class="bg-primary rounded !text-white" label-class="font-semibold"
        label-div-class="p-2 rounded bg-primary/5 w-fit">
        <x-tab name="basic" label="اطلاعات پایه" icon="o-document-text">
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <x-input :label="trans('validation.attributes.title')" wire:model="title" />
                    <x-input :label="trans('validation.attributes.description')" wire:model="description" />
                    <x-toggle :label="trans('datatable.status')" wire:model="published" right />
                </div>
            </x-card>
        </x-tab>

        <x-tab name="rules" label="قوانین شرکت" icon="o-shield-check">
            <x-card title="قوانین شرکت در آزمون" shadow separator>
                <livewire:admin.pages.exam.exam-rule-builder :rules="$rules" />
            </x-card>
        </x-tab>
    </x-tabs>

    <x-admin.shared.form-actions />
</form>
