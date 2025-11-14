<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <x-tabs wire:model.live="type" active-class="bg-primary rounded !text-white" label-class="px-4 py-2 font-semibold"
        label-div-class="p-2 mx-auto rounded bg-primary/5 w-fit">
        @foreach (\App\Enums\CategoryTypeEnum::cases() as $type)
            <x-tab name="{{ $type->value }}" label="{{ $type->title() }}">
                <livewire:admin.pages.category.category-table :type="$type->value" :key="'category-table-' . $type->value" :table-name="'index_category_datatable_' . $type->value" />
            </x-tab>
        @endforeach

    </x-tabs>


</div>
