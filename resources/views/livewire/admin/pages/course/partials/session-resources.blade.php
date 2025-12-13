{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- SESSION RESOURCES - Display Resources for Selected Session --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
@php
    use App\Enums\ResourceType;
    $resources = $this->sessionResources();
@endphp

@if ($resources->count() > 0)
    <x-card>
        <x-slot:title>
            {{ __('resource.page.session_resources') }}
            <span class="badge badge-primary badge-sm">
                {{ $resources->count() }} {{ __('resource.model') }}
            </span>
        </x-slot:title>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($resources as $resource)
                <div wire:key="resource-{{ $resource->id }}"
                    class="p-4 rounded-lg border border-base-300 bg-base-50 hover:bg-base-100 transition-colors">
                    <div class="flex items-start gap-3 mb-3">
                        {{-- Resource Icon --}}
                        <div class="flex-shrink-0">
                            @switch($resource->type)
                                @case(ResourceType::VIDEO)
                                    <x-icon name="o-video-camera" class="w-8 h-8 text-blue-500" />
                                @break

                                @case(ResourceType::IMAGE)
                                    <x-icon name="o-photo" class="w-8 h-8 text-green-500" />
                                @break

                                @case(ResourceType::PDF)
                                    <x-icon name="o-document-text" class="w-8 h-8 text-red-500" />
                                @break

                                @case(ResourceType::AUDIO)
                                    <x-icon name="o-musical-note" class="w-8 h-8 text-purple-500" />
                                @break

                                @case(ResourceType::LINK)
                                    <x-icon name="o-link" class="w-8 h-8 text-orange-500" />
                                @break

                                @default
                                    <x-icon name="o-document" class="w-8 h-8 text-gray-500" />
                            @endswitch
                        </div>

                        {{-- Resource Info --}}
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-sm mb-1 truncate">{{ $resource->title }}</h4>
                            @if ($resource->description)
                                <p class="text-xs text-base-content/60 line-clamp-2 mb-2">
                                    {{ $resource->description }}
                                </p>
                            @endif
                            <x-badge :value="$resource->type->title()" class="badge-xs badge-outline" />
                        </div>
                    </div>

                    {{-- Resource Details --}}
                    <div class="space-y-2 text-xs text-base-content/60 mb-3">
                        @if ($resource->formatted_file_size)
                            <div class="flex items-center gap-1">
                                <x-icon name="o-archive-box" class="w-3 h-3" />
                                <span>{{ $resource->formatted_file_size }}</span>
                            </div>
                        @endif
                        @if ($resource->duration && in_array($resource->type, [ResourceType::VIDEO, ResourceType::AUDIO]))
                            <div class="flex items-center gap-1">
                                <x-icon name="o-clock" class="w-3 h-3" />
                                <span>{{ gmdate('H:i:s', $resource->duration) }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Resource Action Button --}}
                    <x-button wire:click="openResourceModal({{ $resource->id }})" wire:loading.attr="disabled"
                        wire:target="openResourceModal" wire:loading.class="opacity-50" type="button"
                        class="btn-sm btn-primary w-full" icon="o-eye" spinner="openResourceModal">
                        <span wire:loading.remove wire:target="openResourceModal">{{ __('resource.page.view') }}</span>
                        <span wire:loading wire:target="openResourceModal">{{ __('resource.page.loading') }}</span>
                    </x-button>
                </div>
            @endforeach
        </div>
    </x-card>
@else
    <x-card>
        <x-alert icon="o-information-circle" class="alert-info">
            {{ __('resource.page.no_resources_for_session') }}
        </x-alert>
    </x-card>
@endif
