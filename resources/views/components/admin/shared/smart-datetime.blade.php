@props([
    'defaultDate' => null,
    'label' => trans('validation.attributes.published_at'),
    'wirePropertyName' => 'published_at',
    'required' => true,
    'withTime' => false,
    'withTimeSeconds' => false,
    'ignoreWire' => true,
    'setNullInput' => false,
    'xRef' => null,
    'showFormat' => 'jYYYY/jMM/jDD',
    'returnFormat' => 'YYYY-MM-DD',
])

@if (app()->getLocale() == 'fa')
    <x-persian-datepicker wirePropertyName="{{ $wirePropertyName }}" :label="$label" showFormat="{{ $showFormat }}"
        returnFormat="{{ $returnFormat }}" :required="$required" :defaultDate="$defaultDate" :withTime="$withTime" :setNullInput="$setNullInput"
        :ignoreWire="$ignoreWire" :withTimeSeconds="$withTimeSeconds" />
    @error($wirePropertyName)
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
@else
    <x-datetime :label="$label" wire:model="$wirePropertyName" x-ref="$xRef" />
@endif
