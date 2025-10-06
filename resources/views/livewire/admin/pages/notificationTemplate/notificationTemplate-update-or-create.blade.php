@php
    use App\Enums\BooleanEnum;
@endphp
<form wire:submit="submit">
    <!-- Breadcrumbs -->
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <!-- Main Layout: 2 columns for content, 1 for sidebar -->
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

        <!-- Main Content Section (2 columns) -->
        <div class="grid grid-cols-1 col-span-2 gap-4">

            <!-- Basic Information Card -->
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">

                    <!-- Template Name -->
                    <x-input :label="trans('validation.attributes.name')" wire:model.blur="name" :hint="trans('general.notification_template_hints.name')" required />

                    <!-- Channel Selection -->
                    <x-select :label="trans('validation.attributes.channel')" wire:model="channel" :options="$channelOptions" :hint="trans('general.notification_template_hints.channel')" required />

                    <!-- Message Template (Textarea) -->
                    <x-textarea :label="trans('validation.attributes.message_template')" wire:model.blur="message_template" :hint="trans('general.notification_template_hints.message_template')" rows="8"
                        required />

                    <!-- Languages (Multi-select) -->
                    <x-choices-offline :label="trans('validation.attributes.languages')" wire:model="languages" :options="$languageOptions" :hint="trans('general.notification_template_hints.languages')"
                        searchable />

                    <!-- Template Variables/Inputs -->
                    <div class="form-control">
                        <label class="label">
                            <span
                                class="font-semibold label-text">{{ trans('validation.attributes.template_inputs') }}</span>
                        </label>
                        <x-tags wire:model="inputs" icon="o-variable" :hint="trans('general.notification_template_hints.inputs')" clearable />
                        <div class="mt-1 text-sm text-gray-500">
                            {{ trans('general.notification_template_hints.inputs_example') }}
                        </div>
                    </div>

                </div>
            </x-card>

        </div>

        <!-- Sidebar Section (1 column) -->
        <div class="col-span-1">
            <div class="sticky top-16">

                <!-- Publish Configuration Card -->
                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit">
                    <div class="grid grid-cols-1 gap-4">

                        <!-- Published Toggle -->
                        <x-toggle :label="trans('validation.attributes.published')" wire:model="published" :hint="trans('general.notification_template_hints.published')" right />

                        <!-- Status Badge -->
                        <div class="flex gap-2 items-center">
                            <span class="text-sm font-medium">{{ trans('datatable.status') }}:</span>
                            @if ($published)
                                <span class="badge badge-success badge-sm">{{ trans('general.active') }}</span>
                            @else
                                <span class="badge badge-ghost badge-sm">{{ trans('general.inactive') }}</span>
                            @endif
                        </div>

                    </div>
                </x-card>

                <!-- Template Information Card -->
                <x-card :title="trans('general.page_sections.information')" shadow separator class="mt-5">
                    <div class="space-y-3 text-sm">

                        <!-- Template Variables Info -->
                        <div>
                            <h4 class="mb-2 font-semibold text-gray-700">{{ trans('general.available_variables') }}:
                            </h4>
                            <ul class="space-y-1 list-disc list-inside text-gray-600">
                                <li><code class="px-1 bg-gray-100 rounded">{user_name}</code> -
                                    {{ trans('general.variables.user_name') }}</li>
                                <li><code class="px-1 bg-gray-100 rounded">{user_email}</code> -
                                    {{ trans('general.variables.user_email') }}</li>
                                <li><code class="px-1 bg-gray-100 rounded">{order_id}</code> -
                                    {{ trans('general.variables.order_id') }}</li>
                                <li><code class="px-1 bg-gray-100 rounded">{course_name}</code> -
                                    {{ trans('general.variables.course_name') }}</li>
                            </ul>
                        </div>

                        <!-- Usage Example -->
                        <div class="pt-3 border-t">
                            <h4 class="mb-2 font-semibold text-gray-700">{{ trans('general.example') }}:</h4>
                            <div class="p-3 font-mono text-xs bg-gray-50 rounded">
                                Hello {user_name}, your order #{order_id} has been confirmed.
                            </div>
                        </div>

                    </div>
                </x-card>

            </div>
        </div>

    </div>

    <!-- Form Actions (Submit/Cancel buttons) -->
    <x-admin.shared.form-actions />

</form>
