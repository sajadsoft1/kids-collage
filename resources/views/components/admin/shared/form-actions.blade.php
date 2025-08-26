<div class="flex items-center justify-start mt-6 gap-x-6">
    <x-button :label="trans('general.submit')" type="submit" class="btn-primary btn-wide" spinner="submit" />
    <x-button :label="trans('general.reset')" type="reset" wire:loading.attr="disabled" wire:target="submit" />
    @if (count($errors) > 0)
        <x-button :label="trans('general.show_errors')" @click="$wire.set('showErrors', true)" wire:loading.attr="disabled"
            wire:target="submit" />
    @endif
    <x-modal wire:model="showErrors" :title="trans('general.show_errors')" class="backdrop-blur">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li class="text-red-500">{{ $error }}</li>
            @endforeach
        </ul>

        <x-slot:actions>
            <x-button :label="trans('general.close')" @click="$wire.set('showErrors', false)" />
        </x-slot:actions>
    </x-modal>
</div>
