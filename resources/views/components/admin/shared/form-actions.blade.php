<div class="mt-6 flex items-center justify-start gap-x-6">
    <x-button :label="trans('general.submit')" type="submit" class="btn-primary btn-wide" spinner="submit"/>
    <x-button :label="trans('general.reset')" type="reset" wire:loading.attr="disabled" wire:target="submit"/>
</div>