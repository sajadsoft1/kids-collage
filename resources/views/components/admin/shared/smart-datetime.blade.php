@props([
    'defaultDate' => null,
    'label' => trans('validation.attributes.published_at'),
    'wirePropertyName' => 'published_at',
    'required' => true,
    'withTime' => false,
    'withTimeSeconds' => false,
    'setNullInput' => false,
    'xRef' => null,
    'disabled' => false,
    'showFormat' => null,
    'returnFormat' => null,
])

@php
    $showFormat = $showFormat ?? ($withTime ? 'jYYYY/jMM/jDD HH:mm:ss' : 'jYYYY/jMM/jDD');
    $returnFormat = $returnFormat ?? ($withTime ? 'YYYY-MM-DD HH:mm:ss' : 'YYYY-MM-DD');
@endphp

@if (app()->getLocale() == 'fa')
    <x-persian-datepicker :wirePropertyName="$wirePropertyName" :label="$label" :showFormat="$showFormat" :returnFormat="$returnFormat" :required="$required"
        :defaultDate="$defaultDate" :disabled="$disabled" :withTime="$withTime" :setNullInput="$setNullInput" :withTimeSeconds="$withTimeSeconds" />
    @error($wirePropertyName)
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
@else
    <x-datepicker :label="$label" wire:model="{{ $wirePropertyName }}" x-ref="{{ $xRef }}" />
@endif
