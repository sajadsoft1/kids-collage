<div>
    {{-- Notifications table is rendered by PowerGrid component --}}

    <x-modal wire:model="showDetailModal" max-width="md" :title="$detailTitle ?? trans('notification.model')" class="backdrop-blur">
        <div class="space-y-4">
            <p class="text-sm leading-relaxed text-base-content/80">
                {!! nl2br(e($detailBody)) !!}
            </p>
        </div>

        <x-slot:actions>
            <x-button :label="trans('general.close')" @click="$wire.showDetailModal = false" />
        </x-slot:actions>
    </x-modal>
</div>
