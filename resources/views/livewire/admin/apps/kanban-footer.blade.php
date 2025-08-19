<div class="">
    <!-- Card Creation Modal -->
    <x-modal wire:model="showCreateCardModal" max-width="2xl" :title="trans('board.kanban.create_card')">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <x-input wire:model="cardForm.title" label="{{ __('kanban.labels.name') }}"
                    placeholder="{{ __('kanban.placeholders.card_title') }}" required />

                <x-textarea wire:model="cardForm.description" label="{{ __('kanban.labels.description') }}"
                    placeholder="{{ __('kanban.placeholders.card_description') }}" rows="3" />

                <x-select wire:model="cardForm.card_type" label="{{ __('kanban.labels.type') }}" :options="$this->getCardTypeOptions()"
                    required option-label="label" option-value='value' />

                <x-select wire:model="cardForm.priority" label="{{ __('kanban.labels.priority') }}" :options="$this->getPriorityOptions()"
                    required option-label="label" option-value='value' />

                <x-select wire:model="cardForm.status" label="{{ __('kanban.labels.status') }}" :options="$this->getStatusOptions()"
                    required option-label="label" option-value='value' />
            </div>

            <!-- Additional Information -->
            <div class="space-y-4">
                <x-select wire:model="cardForm.column_id" label="{{ __('kanban.labels.column') }}" :options="$board?->columns->map(fn($col) => ['label' => $col->name, 'value' => $col->id])->toArray() ??
                    []"
                    required />

                <x-input wire:model="cardForm.due_date" label="{{ __('kanban.labels.due_date') }}" type="date" />

                <x-choices compact wire:model="cardForm.assignees" label="{{ __('kanban.labels.assignees') }}"
                    :options="$this->getAvailableUsers()
                        ->map(fn($user) => ['label' => $user->name, 'value' => $user->id])
                        ->toArray()" option-label="label" option-value='value' />

                <x-choices compact wire:model="cardForm.reviewers" label="{{ __('kanban.labels.reviewers') }}"
                    :options="$this->getAvailableUsers()
                        ->map(fn($user) => ['label' => $user->name, 'value' => $user->id])
                        ->toArray()" option-label="label" option-value='value' />

                <x-choices compact wire:model="cardForm.watchers" label="{{ __('kanban.labels.watchers') }}"
                    :options="$this->getAvailableUsers()
                        ->map(fn($user) => ['label' => $user->name, 'value' => $user->id])
                        ->toArray()" option-label="label" option-value='value' />
            </div>
        </div>

        <x-slot:actions separator>
            <div class="flex justify-end space-x-3">
                <x-button wire:click="$set('showCreateCardModal', false)" class="btn-neutral">
                    {{ __('Cancel') }}
                </x-button>
                <x-button wire:click="createCard" class="btn-primary" icon="o-check">
                    {{ __('kanban.actions.create') }}
                </x-button>
            </div>
        </x-slot:actions>
    </x-modal>

    <x-modal wire:model="showEditCardModal" max-width="2xl" :title="trans('board.kanban.create_card')">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <x-input wire:model="cardForm.title" label="{{ __('kanban.labels.name') }}"
                    placeholder="{{ __('kanban.placeholders.card_title') }}" required />

                <x-textarea wire:model="cardForm.description" label="{{ __('kanban.labels.description') }}"
                    placeholder="{{ __('kanban.placeholders.card_description') }}" rows="3" />

                <x-select wire:model="cardForm.card_type" label="{{ __('kanban.labels.type') }}" :options="$this->getCardTypeOptions()"
                    required option-label="label" option-value='value' />

                <x-select wire:model="cardForm.priority" label="{{ __('kanban.labels.priority') }}" :options="$this->getPriorityOptions()"
                    required option-label="label" option-value='value' />

                <x-select wire:model="cardForm.status" label="{{ __('kanban.labels.status') }}" :options="$this->getStatusOptions()"
                    required option-label="label" option-value='value' />
            </div>

            <!-- Additional Information -->
            <div class="space-y-4">
                <x-select wire:model="cardForm.column_id" label="{{ __('kanban.labels.column') }}" :options="$board?->columns->map(fn($col) => ['label' => $col->name, 'value' => $col->id])->toArray() ??
                    []"
                    required />

                <x-input wire:model="cardForm.due_date" label="{{ __('kanban.labels.due_date') }}" type="date" />

                <x-choices compact wire:model="cardForm.assignees" label="{{ __('kanban.labels.assignees') }}"
                    :options="$this->getAvailableUsers()
                        ->map(fn($user) => ['label' => $user->name, 'value' => $user->id])
                        ->toArray()" option-label="label" option-value='value' />

                <x-choices compact wire:model="cardForm.reviewers" label="{{ __('kanban.labels.reviewers') }}"
                    :options="$this->getAvailableUsers()
                        ->map(fn($user) => ['label' => $user->name, 'value' => $user->id])
                        ->toArray()" option-label="label" option-value='value' />

                <x-choices compact wire:model="cardForm.watchers" label="{{ __('kanban.labels.watchers') }}"
                    :options="$this->getAvailableUsers()
                        ->map(fn($user) => ['label' => $user->name, 'value' => $user->id])
                        ->toArray()" option-label="label" option-value='value' />
            </div>
        </div>

        <x-slot:actions separator>
            <div class="flex justify-end space-x-3">
                <x-button wire:click="$set('showEditCardModal', false)" class="btn-neutral">
                    {{ __('Cancel') }}
                </x-button>
                <x-button wire:click="createCard" class="btn-primary" icon="o-check">
                    {{ __('kanban.actions.create') }}
                </x-button>
            </div>
        </x-slot:actions>
    </x-modal>

    <!-- Card Details Modal -->
    <x-modal wire:model="showCardModal" max-width="2xl" :title="$selectedCard?->title">
        @if ($selectedCard)
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('kanban.labels.type') }}:</span>
                        <span class="ml-2 text-sm text-gray-900">{{ $selectedCard->card_type->title() }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('kanban.labels.priority') }}:</span>
                        <span class="ml-2 text-sm text-gray-900">{{ $selectedCard->priority->title() }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('kanban.labels.status') }}:</span>
                        <span class="ml-2 text-sm text-gray-900">{{ $selectedCard->status->title() }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('kanban.labels.column') }}:</span>
                        <span class="ml-2 text-sm text-gray-900">{{ $selectedCard->column->name }}</span>
                    </div>
                </div>

                @if ($selectedCard->description)
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('kanban.labels.description') }}:</span>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedCard->description }}</p>
                    </div>
                @endif

                @if ($selectedCard->due_date)
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('kanban.labels.due_date') }}:</span>
                        <span
                            class="ml-2 text-sm {{ $selectedCard->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $selectedCard->due_date->format('Y-m-d') }}
                            @if ($selectedCard->isOverdue())
                                ({{ __('kanban.labels.is_overdue') }})
                            @endif
                        </span>
                    </div>
                @endif

                @if ($selectedCard->assignees->isNotEmpty())
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ __('kanban.labels.assignees') }}:</span>
                        <div class="mt-1 flex flex-wrap gap-1">
                            @foreach ($selectedCard->assignees as $assignee)
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $assignee->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <x-slot:actions separator>
                <div class="flex justify-end space-x-3">
                    <x-button wire:click="$set('showCardModal', false)" class="btn-neutral">
                        {{ __('Close') }}
                    </x-button>
                    <x-button wire:click="switchToEditModal" class="btn-primary" icon="o-pencil">
                        {{ __('kanban.actions.edit') }}
                    </x-button>
                </div>
            </x-slot:actions>
        @endif
    </x-modal>
</div>
