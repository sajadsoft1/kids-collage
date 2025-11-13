<form wire:submit="submit">
    <!-- Breadcrumbs -->
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <!-- Main Layout: 2 columns for content, 1 for sidebar -->
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

        <!-- Main Content Section (2 columns) -->
        <div class="grid grid-cols-1 col-span-2 gap-4">

            <!-- Definitions Card -->
            <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">

                    <!-- Event -->
                    <x-select :label="trans('notificationTemplate.fields.event')" wire:model="event" :options="$eventOptions" option-label="name" option-value="id"
                        searchable required />

                    <!-- Channel -->
                    <x-select :label="trans('notificationTemplate.fields.channel')" wire:model="channel" :options="$channelOptions" option-label="name"
                        option-value="id" required />

                    <!-- Locale -->
                    <x-select :label="trans('notificationTemplate.fields.locale')" wire:model="locale" :options="$localeOptions" option-label="name"
                        option-value="id" required />

                    <!-- Template Name -->
                    <x-input :label="trans('notificationTemplate.fields.name')" wire:model.blur="name" required />

                    <!-- Icon -->
                    <x-input :label="trans('notificationTemplate.fields.icon')" wire:model.blur="icon" :hint="trans('notificationTemplate.hints.icon')" />

                    <!-- Subject (Email only hint) -->
                    <x-input :label="trans('notificationTemplate.fields.subject')" wire:model.blur="subject" :hint="trans('notificationTemplate.hints.subject')" />

                    <!-- Title -->
                    <x-input :label="trans('notificationTemplate.fields.title')" wire:model.blur="title" />

                    <!-- Subtitle / Description -->
                    <x-input :label="trans('notificationTemplate.fields.subtitle')" wire:model.blur="subtitle" />

                    <!-- Body -->
                    <x-textarea :label="trans('notificationTemplate.fields.body')" wire:model.defer="body" rows="8" />

                    <!-- Placeholders -->
                    <x-tags wire:model="placeholders" :label="trans('notificationTemplate.fields.placeholders')" icon="o-hashtag" :hint="trans('notificationTemplate.hints.placeholders')"
                        clearable />

                    <!-- CTA -->
                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <x-input :label="trans('notificationTemplate.fields.cta_label')" wire:model.blur="cta_label" />
                        <x-input :label="trans('notificationTemplate.fields.cta_url')" wire:model.blur="cta_url" />
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
                        <x-toggle :label="trans('notificationTemplate.fields.is_active')" wire:model="is_active" right />

                        <!-- Status Badge -->
                        <div class="flex gap-2 items-center">
                            <span class="text-sm font-medium">{{ trans('datatable.status') }}:</span>
                            @if ($is_active)
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
                                @foreach ($placeholders as $placeholder)
                                    <li><code class="px-1 bg-gray-100 rounded">{{ '{' . $placeholder . '}' }}</code>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Usage Example -->
                        <div class="pt-3 border-t">
                            <h4 class="mb-2 font-semibold text-gray-700">{{ trans('general.example') }}:</h4>
                            <div class="p-3 font-mono text-xs bg-gray-50 rounded">
                                {{ trans('notificationTemplate.examples.body_hint') }}
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
