<form wire:submit="submit" x-data="{ resourceType: @entangle('type') }">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="col-span-2">
                <x-input :label="trans('validation.attributes.title')" wire:model="title" placeholder="Enter resource title" />
            </div>

            <x-input :label="trans('resource.order')" wire:model="order" type="number" min="0" placeholder="Display order" />

            <x-select :label="trans('resource.is_public')" wire:model="is_public" :options="\App\Enums\BooleanEnum::formatedCases()" option-value="value"
                option-label="label" placeholder="Select if the resource is public" />

            <div class="col-span-2">
                <x-textarea :label="trans('validation.attributes.description')" wire:model="description" placeholder="Enter resource description"
                    rows="3" />
            </div>

        </div>


    </x-card>

    <x-card :title="trans('resource.upload_file')" shadow separator progress-indicator="submit" class="mt-5">
        <!-- Resource Type -->
        <x-select :label="trans('resource.type')" wire:model="type" :options="$this->resourceTypes" option-value="value" option-label="label"
            placeholder="Select resource type" />

        <!-- Dynamic Fields based on Resource Type -->
        <div class="mt-6" x-show="resourceType !== ''">
            <!-- Link URL Field (only for LINK type) -->
            <div x-show="resourceType === 'link'" class="mb-4">
                <x-input :label="trans('resource.url')" wire:model="path" placeholder="https://example.com" type="url" />
                <div class="mt-1 text-sm text-gray-500">
                    Enter the external URL for this link resource
                </div>
            </div>

            <!-- File Upload Field (for all other types) -->
            <div x-show="resourceType !== 'link'" class="mb-4">
                <!-- PDF Files -->
                <div x-show="resourceType === 'pdf'">
                    <x-file wire:model="file" accept="application/pdf" :label="trans('resource.file')">
                        <div
                            class="flex justify-center items-center h-32 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed">
                            <div class="text-center">
                                <x-icon name="o-document-text" class="mx-auto w-12 h-12 text-red-500" />
                                <p class="mt-2 text-sm text-gray-600">PDF Document</p>
                                <p class="text-xs text-gray-500">Click to upload PDF file</p>
                            </div>
                        </div>
                    </x-file>
                </div>

                <!-- Video Files -->
                <div x-show="resourceType === 'video'">
                    <x-file wire:model="file" accept="video/mp4,video/avi,video/mov,video/wmv,video/mkv"
                        :label="trans('resource.file')">
                        <div
                            class="flex justify-center items-center h-32 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed">
                            <div class="text-center">
                                <x-icon name="o-video-camera" class="mx-auto w-12 h-12 text-blue-500" />
                                <p class="mt-2 text-sm text-gray-600">Video File</p>
                                <p class="text-xs text-gray-500">Click to upload video file</p>
                            </div>
                        </div>
                    </x-file>
                </div>

                <!-- Image Files -->
                <div x-show="resourceType === 'image'">
                    <x-file wire:model="file" accept="image/jpeg,image/png,image/gif,image/webp" :label="trans('resource.file')">
                        <div
                            class="flex justify-center items-center h-32 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed">
                            <div class="text-center">
                                <x-icon name="o-photo" class="mx-auto w-12 h-12 text-green-500" />
                                <p class="mt-2 text-sm text-gray-600">Image File</p>
                                <p class="text-xs text-gray-500">Click to upload image file</p>
                            </div>
                        </div>
                    </x-file>

                    <!-- Image Preview -->
                    @if ($edit_mode && $model->type === \App\Enums\ResourceType::IMAGE)
                        <div class="mt-4">
                            <p class="mb-2 text-sm font-medium text-gray-700">Current Image:</p>
                            <img src="{{ $model->url }}" alt="{{ $model->title }}"
                                class="object-cover w-32 h-32 rounded-lg border">
                        </div>
                    @endif
                </div>

                <!-- Audio Files -->
                <div x-show="resourceType === 'audio'">
                    <x-file wire:model="file" accept="audio/mp3,audio/wav,audio/ogg,audio/m4a" :label="trans('resource.file')">
                        <div
                            class="flex justify-center items-center h-32 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed">
                            <div class="text-center">
                                <x-icon name="o-musical-note" class="mx-auto w-12 h-12 text-purple-500" />
                                <p class="mt-2 text-sm text-gray-600">Audio File</p>
                                <p class="text-xs text-gray-500">Click to upload audio file</p>
                            </div>
                        </div>
                    </x-file>
                </div>

                <!-- General Files -->
                <div x-show="resourceType === 'file'">
                    <x-file wire:model="file" accept=".zip,.rar,.txt,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                        :label="trans('resource.file')">
                        <div
                            class="flex justify-center items-center h-32 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed">
                            <div class="text-center">
                                <x-icon name="o-document" class="mx-auto w-12 h-12 text-gray-500" />
                                <p class="mt-2 text-sm text-gray-600">Document File</p>
                                <p class="text-xs text-gray-500">Click to upload document file</p>
                            </div>
                        </div>
                    </x-file>
                </div>
            </div>
        </div>

        <!-- File Preview (for edit mode) -->
        @if ($edit_mode && $model->isUploadedFile())
            <div class="p-4 mt-4 bg-gray-50 rounded-lg">
                <h4 class="mb-2 text-sm font-medium text-gray-700">Current File:</h4>
                <div class="flex items-center space-x-4">
                    @if ($model->type === \App\Enums\ResourceType::IMAGE)
                        <img src="{{ $model->url }}" alt="{{ $model->title }}"
                            class="object-cover w-16 h-16 rounded-lg border">
                    @else
                        <div class="flex justify-center items-center w-16 h-16 bg-gray-200 rounded-lg border">
                            @switch($model->type)
                                @case(\App\Enums\ResourceType::PDF)
                                    <x-icon name="o-document-text" class="w-8 h-8 text-red-500" />
                                @break

                                @case(\App\Enums\ResourceType::VIDEO)
                                    <x-icon name="o-video-camera" class="w-8 h-8 text-blue-500" />
                                @break

                                @case(\App\Enums\ResourceType::AUDIO)
                                    <x-icon name="o-musical-note" class="w-8 h-8 text-purple-500" />
                                @break

                                @default
                                    <x-icon name="o-document" class="w-8 h-8 text-gray-500" />
                            @endswitch
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $model->title }}</p>
                        <p class="text-xs text-gray-500">{{ $model->type->title() }} •
                            {{ $model->formatted_file_size ?? 'Unknown size' }}</p>
                        <a href="{{ $model->url }}" target="_blank"
                            class="text-sm text-blue-600 hover:text-blue-800">
                            View File
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </x-card>

    <x-card :title="trans('resource.attached_resources')" shadow separator progress-indicator="submit" class="mt-5">
        <x-slot:menu>
            <x-button wire:click="addRelationship" class="btn-primary btn-sm" icon="o-plus"
                spinner="addRelationship">
                {{ trans('resource.add_relationship') }}
            </x-button>
        </x-slot:menu>

        @if (count($relationships) > 0)
            <div class="space-y-4">
                @foreach ($relationships as $index => $relationship)
                    <div class="flex gap-4 items-end">
                        <!-- Course Template Select -->
                        <div class="flex-1">
                            <x-select :label="trans('coursetemplate.model')" :options="$courseTemplates"
                                wire:model.live="relationships.{{ $index }}.course_template_id"
                                placeholder="Select Course Template" option-value="value" option-label="label" />
                        </div>

                        <!-- Course Session Template Select (Dynamic based on selected Course Template) -->
                        <div class="flex-1">
                            <x-select :label="trans('courseSessionTemplate.model')" :options="$this->getCourseSessionTemplatesForTemplate($relationship['course_template_id'])"
                                wire:model="relationships.{{ $index }}.course_session_template_id"
                                placeholder="Select Session Template" option-value="value" option-label="label"
                                :disabled="$relationship['disabled']" :disabled="!$relationship['course_template_id']" />
                        </div>

                        <!-- Remove Button -->
                        <div>
                            <x-button wire:click="removeRelationship({{ $index }})" class="btn-error btn-sm"
                                spinner="removeRelationship({{ $index }})" icon="o-trash" />
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
