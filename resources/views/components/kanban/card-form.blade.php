@props([
    'show' => false,
    'title' => 'Create Card',
    'submitAction' => 'createCard',
    'submitButtonText' => 'Create',
    'cardForm' => [],
    'board' => null,
    'availableUsers' => [],
    'cardTypeOptions' => [],
    'priorityOptions' => [],
    'statusOptions' => [],
])

<x-modal wire:model="{{ $show }}" max-width="2xl" :title="$title">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Basic Information -->
        <div class="space-y-4">
            <x-input wire:model="cardForm.title" label="{{ __('kanban.labels.name') }}"
                placeholder="{{ __('kanban.placeholders.card_title') }}" required />

            <x-textarea wire:model="cardForm.description" label="{{ __('kanban.labels.description') }}"
                placeholder="{{ __('kanban.placeholders.card_description') }}" rows="3" />

            <x-select wire:model="cardForm.card_type" label="{{ __('kanban.labels.type') }}" :options="$cardTypeOptions" required
                option-label="label" option-value='value' />

            <x-select wire:model="cardForm.priority" label="{{ __('kanban.labels.priority') }}" :options="$priorityOptions"
                required option-label="label" option-value='value' />

            <x-select wire:model="cardForm.status" label="{{ __('kanban.labels.status') }}" :options="$statusOptions" required
                option-label="label" option-value='value' />
        </div>

        <!-- Additional Information -->
        <div class="space-y-4">
            <x-select wire:model="cardForm.column_id" label="{{ __('kanban.labels.column') }}" :options="$board?->columns->map(fn($col) => ['label' => $col->name, 'value' => $col->id])->toArray() ?? []"
                option-label="label" option-value='value' required />

            <x-input wire:model="cardForm.due_date" label="{{ __('kanban.labels.due_date') }}" type="date" />

            <x-choices compact wire:model="cardForm.assignees" label="{{ __('kanban.labels.assignees') }}"
                :options="$availableUsers" option-label="label" option-value='value' />

            <x-choices compact wire:model="cardForm.reviewers" label="{{ __('kanban.labels.reviewers') }}"
                :options="$availableUsers" option-label="label" option-value='value' />

            <x-choices compact wire:model="cardForm.watchers" label="{{ __('kanban.labels.watchers') }}"
                :options="$availableUsers" option-label="label" option-value='value' />
        </div>
    </div>

    <x-slot:actions separator>
        <div class="flex justify-end space-x-3">
            <x-button wire:click="$set('{{ $show }}', false)" class="btn-neutral">
                {{ __('Cancel') }}
            </x-button>
            <x-button wire:click="{{ $submitAction }}" class="btn-primary" icon="o-check">
                {{ $submitButtonText }}
            </x-button>
        </div>
    </x-slot:actions>
</x-modal>
