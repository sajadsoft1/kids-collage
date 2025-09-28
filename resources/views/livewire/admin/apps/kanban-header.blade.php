    <div class="flex flex-1 justify-between items-center py-4 mx-auto">
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <div class="flex items-center px-3 py-1 space-x-2 rounded-lg" style="">
                    <div class="w-5 h-5 rounded-full" style="background-color: {{ $board->color }}">
                    </div>
                    <span class="text-lg font-bold text-base-content">{{ $board->name }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <x-button wire:click="$set('showCreateCardModal', true)" class="btn-primary" icon="o-plus">
                {{ __('board.kanban.create_card') }}
            </x-button>
            <x-button link="{{ route('admin.app.boards') }}" class="btn-neutral" icon="o-arrow-left" />
        </div>
    </div>
