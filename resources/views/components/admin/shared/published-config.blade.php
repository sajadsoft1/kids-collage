@props([
    'hasPublishedAt' => 0,
    'defaultDate' => null,
])

<div class="grid grid-cols-1 gap-4">
    <x-toggle :label="trans('validation.attributes.published')" wire:model.live="published" right value="1" />
    @if ($hasPublishedAt)
        <div x-data x-show="!$wire.published">
            <x-admin.shared.smart-datetime :default-date="$defaultDate" :with-time="true" />
        </div>
    @endif
</div>
