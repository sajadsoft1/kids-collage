<form wire:submit.prevent="submit">
    <x-card :title="trans('datatable.setting')" shadow separator progress-indicator="submit">
        <div class="grid-cols-1 gap-4 d-lg-grid">
            <x-toggle :label="trans('datatable.status')" wire:model="status" right :hint="trans('user.published_status_default_hint')" />

            @if ($detected_user_type !== \App\Enums\UserTypeEnum::USER && $detected_user_type !== \App\Enums\UserTypeEnum::PARENT)
                <x-choices :label="trans('validation.attributes.role')" wire:model="selected_rules" multiple :options="$rules" clearable />
            @endif
        </div>
    </x-card>
    <x-admin.shared.form-actions />
</form>
