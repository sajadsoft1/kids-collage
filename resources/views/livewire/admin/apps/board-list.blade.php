<div class="flex-1 mt-5">
    <!-- Header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">{{ __('board.board_list.title') }} ✨</h1>
        </div>
        <div class="flex sm:flex-row items-center gap-x-1">
            <div class="relative sm:w-full sm:flex-1">
                <x-input wire:model.live.debounce.300ms="search"
                         placeholder="{{ __('board.board_list.search_boards') }}" class="w-64"
                         icon="o-magnifying-glass"/>
                @if ($search)
                    <button wire:click="clearSearch"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <x-icon name="o-x-mark" class="w-4 h-4"/>
                    </button>
                @endif
            </div>


            <div class="flex gap-x-1">
                <x-dropdown>
                    <x-slot:trigger>
                        <x-button icon="o-bell" class=""/>
                    </x-slot:trigger>
                    <x-menu-item :title="__('All')" wire:click.stop="$set('filter','all')" spinner="updating"/>
                    <x-menu-item :title="__('Active')" wire:click.stop="$set('filter','active')" spinner="updating"/>
                    <x-menu-item :title="__('Inactive')" wire:click.stop="$set('filter','inactive')" spinner="updating"/>
                </x-dropdown>
                <x-button wire:click="resetFilters" class="" icon="o-arrow-path"/>
                <x-button wire:click="$set('showBoardModal', true)" class="" icon="o-plus"/>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="mx-auto py-6">
        @if ($this->boards->isEmpty())
            <!-- No Boards -->
            <div class="text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <x-icon name="o-squares-2x2" class="w-full h-full"/>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('kanban.labels.no_boards') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('kanban.messages.no_boards_found') }}</p>
                <div class="mt-6">
                    <x-button wire:click="$set('showBoardModal', true)" class="btn-primary" icon="o-plus">
                        {{ __('kanban.actions.create') }} {{ __('kanban.labels.board') }}
                    </x-button>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($this->boards as $board)
                    <x-card class="rounded-2xl shadow-lg p-6" :title="$board->name">
                        <x-slot:menu>
                            <x-button icon="o-eye" class="btn-circle btn-ghost btn-sm" :link="route('admin.app.kanban', $board->id)"/>
                            <x-dropdown right>
                                <x-slot:trigger>
                                    <x-button icon="s-ellipsis-vertical" class="btn-circle btn-ghost btn-sm"/>
                                </x-slot:trigger>
                                <x-menu-item :title="trans('general.edit')"/>
                                <x-menu-item :title="trans('general.delete')"/>
                            </x-dropdown>

                        </x-slot:menu>
                        <div class="grid grid-cols-3 gap-1 mb-4">
                            <div class="p-1 text-center">
                                <div class="font-bold text-base-content">92</div>
                                <div class="text-sm text-base-content/50">Purchases</div>
                            </div>
                            <div class="border-r-2 border-l-2 border-dashed border-gray-300 p-1 text-center">
                                <div class="font-bold text-base-content">$69</div>
                                <div class="text-sm text-base-content/50">Avg</div>
                            </div>
                            <div class="p-1 text-center">
                                <div class="font-bold text-base-content">$6,240</div>
                                <div class="text-sm text-base-content/50">Total</div>
                            </div>
                        </div>

                        <div class="flex justify-between text-sm text-gray-500 mb-2">
                            <span>Start: <span
                                        class="font-semibold text-base-content">{{ $board->created_at->format('Y-m-d') }}</span></span>
                            <span>End: <span
                                        class="font-semibold text-base-content">{{ $board->updated_at->format('Y-m-d') }}</span></span>
                        </div>

                        <div class="mb-4">
                            <div class="h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-blue-500 rounded-full" style="width: 75%;"></div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            @foreach ($board->users->take(2) as $user)
                                <x-avatar :image="$user->getFirstMediaUrl(
                                    'avatar',
                                    \App\Helpers\Constants::RESOLUTION_50_SQUARE,
                                )" alt="My image"/>
                            @endforeach
                            @if ($board->users->count() > 2)
                                <div
                                        class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-xs font-medium text-gray-600">
                                    +{{ $board->users->count() - 2 }}
                                </div>
                            @endif
                        </div>

                    </x-card>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($this->boards->hasPages())
                <div class="mt-8">
                    {{ $this->boards->links() }}
                </div>
            @endif
        @endif
    </div>
    <!-- Board Creation/Edit Modal -->
    <x-modal wire:model="showBoardModal" max-width="md" :title="$selectedBoard
        ? __('kanban.actions.edit') . ' ' . __('kanban.labels.board')
        : __('kanban.actions.create') . ' ' . __('kanban.labels.board')">
        <div class="space-y-4">
            <x-input wire:model="boardForm.name" label="{{ __('kanban.labels.name') }}"
                     placeholder="{{ __('kanban.placeholders.board_name') }}" required/>

            <x-textarea wire:model="boardForm.description" label="{{ __('kanban.labels.description') }}"
                        placeholder="{{ __('kanban.placeholders.board_description') }}" rows="3"/>

            <x-input wire:model="boardForm.color" label="{{ __('kanban.labels.color') }}" type="color"
                     class="w-20"/>

            <x-toggle wire:model="boardForm.is_active" label="{{ __('kanban.labels.is_active') }}"/>
        </div>

        <x-slot:actions separator>
            <div class="flex justify-end space-x-3">

                <x-button wire:click="$set('showBoardModal', false)" :label="trans('general.cancel')"/>
                <x-button wire:click="{{ $selectedBoard ? 'updateBoard' : 'createBoard' }}"
                          class="btn-primary btn-wide"
                          label="{{ $selectedBoard ? __('Update') : __('kanban.actions.create') }}" icon="o-check"/>
            </div>
        </x-slot:actions>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="showDeleteModal" max-width="sm">
        <x-card title="{{ __('Confirm Delete') }}">
            <div class="space-y-4">
                <p class="text-gray-600">
                    {{ __('Are you sure you want to delete the board') }}
                    <strong>"{{ $boardToDelete?->name }}"</strong>?
                </p>
                <p class="text-sm text-red-600">
                    {{ __('This action cannot be undone. All cards and columns will be permanently deleted.') }}
                </p>
            </div>

            <x-slot:actions separator>
                <div class="flex justify-end space-x-3">
                    <x-button wire:click="$set('showDeleteModal', false)" class="btn-neutral">
                        {{ __('Cancel') }}
                    </x-button>
                    <x-button wire:click="deleteBoard" class="btn-error" icon="o-trash">
                        {{ __('Delete') }}
                    </x-button>
                </div>
            </x-slot:actions>
        </x-card>
    </x-modal>
</div>

@script
<script>
    // Listen for board events
    document.addEventListener('board-created', (event) => {
        window.$wireui.notify({
            title: 'Success',
            description: event.detail.message,
            icon: 'success'
        });
    });

    document.addEventListener('board-updated', (event) => {
        window.$wireui.notify({
            title: 'Success',
            description: event.detail.message,
            icon: 'success'
        });
    });

    document.addEventListener('board-deleted', (event) => {
        window.$wireui.notify({
            title: 'Success',
            description: event.detail.message,
            icon: 'success'
        });
    });
</script>
@endscript
