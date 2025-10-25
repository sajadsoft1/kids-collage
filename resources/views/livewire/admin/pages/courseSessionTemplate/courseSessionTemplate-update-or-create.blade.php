@php
    use App\Enums\BooleanEnum;
    use App\Enums\SessionType;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="col-span-2">
                <x-input :label="trans('validation.attributes.title')" wire:model="title" />
            </div>
            <div class="col-span-2">
                <x-textarea :label="trans('validation.attributes.description')" wire:model="description" />
            </div>
            <x-select :label="trans('validation.attributes.type')" :options="SessionType::options()" option-label="label" option-value="value" wire:model="type" />


            <x-input :label="trans('validation.attributes.duration_minutes')" wire:model="duration_minutes" type="number" step="1" min="1" />

        </div>
    </x-card>

    <x-card :title="trans('resource.attached_resources')" shadow separator progress-indicator="submit" class="mt-5">
        <x-slot:menu>
            <x-button wire:click="addResource" class="btn-primary btn-sm" icon="o-plus" spinner="addResource">
                {{ trans('resource.add_relationship') }}
            </x-button>
        </x-slot:menu>

        @if (count($resources) > 0)
            <div class="space-y-4">
                @foreach ($resources as $index => $resource)
                    <div class="flex items-end gap-4">
                        <!-- Resource Select -->
                        <div class="flex-1">
                            <x-choices-offline searchable single :label="trans('resource.model')" :options="$availableResources"
                                wire:model="resources.{{ $index }}.resource_id" placeholder="Select Resource"
                                option-value="value" option-label="label" />
                        </div>

                        <!-- Remove Button -->
                        <div>
                            <x-button wire:click="removeResource({{ $index }})" class="btn-error btn-sm"
                                spinner="removeResource({{ $index }})" icon="o-trash" />
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-8 text-center text-gray-500">
                <p>{{ trans('resource.no_relationships') }}</p>
                <p class="mt-2 text-sm">{{ trans('resource.click_add_to_create') }}</p>
            </div>
        @endif
    </x-card>

    <x-admin.shared.form-actions />
</form>
