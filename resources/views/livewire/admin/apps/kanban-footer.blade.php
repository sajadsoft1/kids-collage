<div class="">
    <!-- Card Creation Modal -->
    <x-kanban.card-form :show="'showCreateCardModal'" :title="trans('board.kanban.create_card')" submit-action="createCard" :submit-button-text="__('kanban.actions.create')"
        :card-form="$cardForm" :board="$board" :available-users="$this->getAvailableUsers()
            ->map(fn($user) => ['label' => $user->name, 'value' => $user->id])
            ->toArray()" :card-type-options="$this->getCardTypeOptions()" :priority-options="$this->getPriorityOptions()" :status-options="$this->getStatusOptions()" />

    <!-- Card Edit Modal -->
    <x-kanban.card-form :show="'showEditCardModal'" :title="trans('board.kanban.edit_card')" submit-action="updateCard" :submit-button-text="__('kanban.actions.update')"
        :card-form="$cardForm" :board="$board" :available-users="$this->getAvailableUsers()
            ->map(fn($user) => ['label' => $user->name, 'value' => $user->id])
            ->toArray()" :card-type-options="$this->getCardTypeOptions()" :priority-options="$this->getPriorityOptions()"
        :status-options="$this->getStatusOptions()" />

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
                        <span class="ml-2 text-sm {{ $selectedCard->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
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
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach ($selectedCard->assignees as $assignee)
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
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
