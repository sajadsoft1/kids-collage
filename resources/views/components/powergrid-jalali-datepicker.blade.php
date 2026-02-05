@props([
    'field' => '',
    'title' => '',
    'wireModel' => null,
    'range' => true,
    'maxDate' => null,
    'minDate' => null,
])

@php
    $wireModel = $wireModel ?? 'filters.input_text.' . $field;
    $label = $title ?: trans('datatable.created_at');
@endphp

<x-jalali-datepicker wire:model.live="{{ $wireModel }}" :label="$label" :range="$range" :max-date="$maxDate"
    :min-date="$minDate" jalali export-calendar="gregorian" export-format="Y-m-d" />
