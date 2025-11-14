@php
    use App\Enums\NotificationChannelEnum;
@endphp
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

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Event -->
                        <x-select :label="trans('notificationTemplate.fields.event')" wire:model="event" :options="$eventOptions" option-label="name"
                            :placeholder="trans('general.please_select_an_option')" placeholder-value="" option-value="id" searchable required />

                        <!-- Channel -->
                        <x-select :label="trans('notificationTemplate.fields.channel')" wire:model="channel" :options="$channelOptions" option-label="name"
                            :placeholder="trans('general.please_select_an_option')" placeholder-value="" option-value="id" required />

                        <!-- Locale -->
                        <x-select :label="trans('notificationTemplate.fields.locale')" wire:model="locale" :options="$localeOptions" option-label="name"
                            :placeholder="trans('general.please_select_an_option')" placeholder-value="" option-value="id" required />
                    </div>


                    @if ($channel === NotificationChannelEnum::EMAIL->value)
                        <!-- Subject (Email only hint) -->
                        <x-input :label="trans('notificationTemplate.fields.subject')" wire:model.blur="subject" :hint="trans('notificationTemplate.hints.subject')" :hidden="$channel !== NotificationChannelEnum::EMAIL->value" />
                    @endif
                    @if (in_array($channel, [NotificationChannelEnum::EMAIL->value, NotificationChannelEnum::DATABASE->value]))
                        <!-- Title -->
                        <x-input :label="trans('notificationTemplate.fields.title')" wire:model.blur="title" />

                        <!-- Subtitle / Description -->
                        <x-input :label="trans('notificationTemplate.fields.subtitle')" wire:model.blur="subtitle" />
                    @endif


                    <!-- Body -->
                    <x-textarea :label="trans('notificationTemplate.fields.body')" wire:model.defer="body" rows="8" :hint="trans('notificationTemplate.examples.body_hint')" />

                    <!-- Placeholders -->
                    <x-tags wire:model.live="placeholders" :label="trans('notificationTemplate.fields.placeholders')" icon="o-hashtag" :hint="trans('notificationTemplate.hints.placeholders')"
                        clearable />
                    <p> {{ collect($placeholders)->implode(', ') }} </p>

                    <!-- CTA -->
                    @if ($channel === NotificationChannelEnum::EMAIL->value)
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <x-input :label="trans('notificationTemplate.fields.cta_label')" wire:model.blur="cta_label" />
                            <x-input :label="trans('notificationTemplate.fields.cta_url')" wire:model.blur="cta_url" />
                        </div>
                    @endif

                </div>
            </x-card>

        </div>

        <!-- Sidebar Section (1 column) -->
        <div class="col-span-1">
            <div class="sticky top-16">

                <!-- Publish Configuration Card -->
                <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit">
                    <div class="grid grid-cols-1 gap-4">
                        <x-toggle :label="trans('notificationTemplate.fields.is_active')" wire:model="is_active" right />
                    </div>
                </x-card>

                <!-- Template Information Card -->
                <x-card :title="trans('notificationTemplate.fields.available_variables')" shadow separator class="mt-5">
                    <div class="space-y-3 text-sm">
                        <div>
                            <ul class="space-y-1 list-disc list-inside text-gray-600">
                                @foreach ($placeholders as $placeholder)
                                    <li><code class="px-1 bg-gray-100 rounded">{{ '{' . $placeholder . '}' }}</code>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </x-card>

            </div>
        </div>

    </div>

    <!-- Form Actions (Submit/Cancel buttons) -->
    <x-admin.shared.form-actions />

</form>
