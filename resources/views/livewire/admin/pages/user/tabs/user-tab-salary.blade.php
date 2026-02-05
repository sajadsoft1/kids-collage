<form wire:submit.prevent="submit">
    <x-card :title="trans('user.page.salary_info')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <x-input type="number" :label="trans('validation.attributes.salary')" wire:model="salary" min="0" icon="lucide.hand-coins"
                :suffix="systemCurrency()" />
            <x-input type="number" :label="trans('validation.attributes.benefit')" wire:model="benefit" min="0" icon="lucide.hand-coins"
                :suffix="systemCurrency()" />
            <x-jalali-datepicker wire:model="cooperation_start_date" :label="trans('validation.attributes.cooperation_start_date')" jalali
                export-calendar="gregorian" export-format="Y-m-d" />
            <x-jalali-datepicker wire:model="cooperation_end_date" :label="trans('validation.attributes.cooperation_end_date')" jalali export-calendar="gregorian"
                export-format="Y-m-d" />
        </div>
    </x-card>
    <x-admin.shared.form-actions />
</form>
