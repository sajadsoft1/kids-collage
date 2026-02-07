<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <form wire:submit="submit">
        <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
            <div class="grid gap-4 md:grid-cols-2">
                <x-input wire:model="name" :label="__('validation.attributes.name')" required />
                <x-input wire:model="order" type="number" :label="__('datatable.order')" min="0" />
            </div>
            <div class="mt-4">
                <x-textarea wire:model="description" :label="__('validation.attributes.description')" rows="3" />
            </div>
            <div class="mt-4">
                <x-select wire:model="auto_assign_strategy" :label="__('ticket_chat.auto_assign_strategy')" :options="[
                    ['id' => 'manual', 'name' => __('ticket_chat.auto_assign_manual')],
                    ['id' => 'round_robin', 'name' => __('ticket_chat.auto_assign_round_robin')],
                    ['id' => 'load_balance', 'name' => __('ticket_chat.auto_assign_load_balance')],
                ]" option-value="id" option-label="name" />
            </div>
            <div class="mt-4">
                <x-checkbox wire:model="is_active" :label="__('general.active')" />
            </div>
            <div class="mt-4">
                <x-choices wire:model="agent_ids" :options="$this->assignableUsers" :label="__('ticket_chat.department_agents')" option-value="id" option-label="name" multiple />
            </div>
        </x-card>

        <div class="mt-6 flex gap-4">
            <x-button type="submit" class="btn-primary" spinner="submit" :label="__('general.submit')" />
            <a href="{{ route('admin.ticket-chat.departments.index') }}" wire:navigate class="btn btn-ghost">{{ __('general.cancel') }}</a>
        </div>
    </form>
</div>
