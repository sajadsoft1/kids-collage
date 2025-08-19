@props([
    'hasPublishedAt' => 0,
    'defaultDate' => null,
])

<div class="grid grid-cols-1 gap-4">
    <x-toggle :label="trans('validation.attributes.published')" wire:model.live="published" right value="1" />
    @if ($hasPublishedAt)
        <div x-data x-show="!$wire.published">
            @if (app()->getLocale() == 'fa')
                <x-persian-datepicker wirePropertyName="published_at" :label="trans('validation.attributes.published_at')" showFormat="jYYYY/jMM/jDD"
                    returnFormat="YYYY-MM-DD HH:mm:ss" :required="true" :defaultDate="$defaultDate" :withTime="true"
                    :setNullInput="is_null($defaultDate)" :ignoreWire="true" :withTimeSeconds="false" />
                @error('published_at')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            @else
                <x-datetime :label="trans('validation.attributes.published_at')" wire:model="published_at" x-ref="publishedAt" />
            @endif
        </div>
    @endif
</div>
