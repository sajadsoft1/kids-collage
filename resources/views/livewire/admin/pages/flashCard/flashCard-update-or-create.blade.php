<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions"/>
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
           <div class="grid gap-4">
               <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                   <x-admin.shared.tinymce wire:model="front" :label="trans('flashCard.fields.front')" :config="['max_height' => 500, 'min_height' => 500]"/>
                   <x-admin.shared.tinymce wire:model="back" :label="trans('flashCard.fields.back')" :config="['max_height' => 500, 'min_height' => 500]"/>
               </div>
           </div>
    </x-card>

    <x-admin.shared.form-actions/>
</form>
