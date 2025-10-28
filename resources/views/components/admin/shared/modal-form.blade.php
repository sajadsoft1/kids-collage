<x-modal wire:model="showModal" :title="$this->getModalTitle()" persistent class="backdrop-blur">
    <form wire:submit="saveModal">
        <div class="grid grid-cols-1 gap-4">
            @foreach ($this->modalFields() as $field)
                @php
                    $fieldName = $field['name'];
                    $wireModel = "modalData.{$fieldName}";
                    $label = $field['label'] ?? trans("validation.attributes.{$fieldName}");
                    $placeholder = $field['placeholder'] ?? $label;
                    $required = $field['required'] ?? false;
                @endphp

                {{-- Input Field --}}
                @if ($field['type'] === 'input')
                    <x-input :label="$label" wire:model="{{ $wireModel }}" :placeholder="$placeholder" :required="$required"
                        {{ isset($field['icon']) ? "icon={$field['icon']}" : '' }} />

                    {{-- Textarea Field --}}
                @elseif ($field['type'] === 'textarea')
                    <x-textarea :label="$label" wire:model="{{ $wireModel }}" :placeholder="$placeholder" :required="$required"
                        :rows="$field['rows'] ?? 3" />

                    {{-- Toggle Field --}}
                @elseif ($field['type'] === 'toggle')
                    <x-toggle :label="$label" wire:model="{{ $wireModel }}" :right="$field['right'] ?? true" />

                    {{-- Checkbox Field --}}
                @elseif ($field['type'] === 'checkbox')
                    <x-checkbox :label="$label" wire:model="{{ $wireModel }}" :right="$field['right'] ?? false" />

                    {{-- Select Field --}}
                @elseif ($field['type'] === 'select')
                    <x-select :label="$label" wire:model="{{ $wireModel }}" :options="$field['options'] ?? []" :placeholder="$placeholder"
                        :required="$required" {{ isset($field['icon']) ? "icon={$field['icon']}" : '' }} />

                    {{-- Number Field --}}
                @elseif ($field['type'] === 'number')
                    <x-input type="number" :label="$label" wire:model="{{ $wireModel }}" :placeholder="$placeholder"
                        :required="$required" :min="$field['min'] ?? null" :max="$field['max'] ?? null" :step="$field['step'] ?? null" />

                    {{-- Email Field --}}
                @elseif ($field['type'] === 'email')
                    <x-input type="email" :label="$label" wire:model="{{ $wireModel }}" :placeholder="$placeholder"
                        :required="$required" icon="o-envelope" />

                    {{-- Date Field --}}
                @elseif ($field['type'] === 'date')
                    <x-input type="date" :label="$label" wire:model="{{ $wireModel }}" :required="$required" />

                    {{-- Color Picker --}}
                @elseif ($field['type'] === 'color')
                    <x-input type="color" :label="$label" wire:model="{{ $wireModel }}" class="w-20" />
                @endif
            @endforeach
        </div>

        {{-- Modal Actions --}}
        <x-slot:actions separator>
            <div class="flex justify-end gap-3">
                {{-- Cancel Button --}}
                <x-button :label="trans('general.cancel')" @click="$wire.showModal = false" />

                {{-- Submit Button --}}
                <x-button type="submit" class="btn-primary" :label="$this->editingId ? trans('general.update') : trans('general.create')" icon="o-check" spinner="saveModal" />
            </div>
        </x-slot:actions>
    </form>
</x-modal>
