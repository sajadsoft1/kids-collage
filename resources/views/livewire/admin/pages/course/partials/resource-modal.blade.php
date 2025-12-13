{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- RESOURCE MODAL - Display Resource Details in Modal --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
@php
    use App\Enums\ResourceType;
    $resource = $this->modalResource();
@endphp

<x-modal wire:model="showResourceModal" max-width="5xl" :title="$resource?->title ?? __('resource.model')" separator>
    <div wire:loading.delay.shortest wire:target="openResourceModal" class="py-8 text-center">
        <x-loading class="loading-spinner loading-lg" />
        <p class="mt-4 text-sm text-base-content/60">{{ __('resource.page.loading_resource') }}</p>
    </div>

    <div wire:loading.remove.delay.shortest wire:target="openResourceModal">
        @if ($resource)
            <div class="space-y-4">
                {{-- Resource Description --}}
                @if ($resource->description)
                    <div>
                        <h3 class="text-base font-semibold mb-2">{{ __('resource.page.description') }}</h3>
                        <div class="prose prose-sm max-w-none text-sm">
                            {!! $resource->description !!}
                        </div>
                    </div>
                @endif

                {{-- Resource Details --}}
                <div class="flex items-center gap-4 text-sm text-base-content/60">
                    <x-badge :value="$resource->type->title()" class="badge-sm badge-outline" />
                    @if ($resource->formatted_file_size)
                        <div class="flex items-center gap-1">
                            <x-icon name="o-archive-box" class="w-4 h-4" />
                            <span>{{ $resource->formatted_file_size }}</span>
                        </div>
                    @endif
                    @if ($resource->duration && in_array($resource->type, [ResourceType::VIDEO, ResourceType::AUDIO]))
                        <div class="flex items-center gap-1">
                            <x-icon name="o-clock" class="w-4 h-4" />
                            <span>{{ gmdate('H:i:s', $resource->duration) }}</span>
                        </div>
                    @endif
                </div>

                {{-- Resource Content Display --}}
                <div class="mt-4">
                    @if ($resource->type === ResourceType::VIDEO)
                        {{-- Video Player --}}
                        <div class="w-full">
                            <video controls class="w-full rounded-lg" preload="metadata">
                                <source src="{{ route('resources.download', $resource) }}" type="video/mp4">
                                {{ __('resource.page.browser_no_video_support') }}
                            </video>
                        </div>
                    @elseif ($resource->type === ResourceType::AUDIO)
                        {{-- Audio Player --}}
                        <div class="w-full">
                            <audio controls class="w-full">
                                <source src="{{ route('resources.download', $resource) }}" type="audio/mpeg">
                                {{ __('resource.page.browser_no_audio_support') }}
                            </audio>
                        </div>
                    @elseif ($resource->type === ResourceType::IMAGE)
                        {{-- Image Display --}}
                        <div class="w-full flex justify-center">
                            <img src="{{ route('resources.download', $resource) }}" alt="{{ $resource->title }}"
                                class="max-w-full rounded-lg object-contain max-h-[70vh]">
                        </div>
                    @elseif ($resource->type === ResourceType::LINK)
                        {{-- External Link --}}
                        <div class="text-center py-8">
                            <x-button link="{{ $resource->url }}" target="_blank" class="btn-primary btn-lg"
                                icon="o-arrow-top-right-on-square">
                                {{ __('resource.page.open_link') }}
                            </x-button>
                        </div>
                    @else
                        {{-- Download Button for PDF, File, etc. --}}
                        <div class="text-center py-8">
                            <x-button link="{{ route('resources.download', $resource) }}" class="btn-primary btn-lg"
                                icon="o-arrow-down-tray">
                                {{ __('resource.page.download_file') }}
                            </x-button>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="py-8 text-center">
                <x-loading class="loading-spinner loading-lg" />
                <p class="mt-4 text-sm text-base-content/60">{{ __('resource.page.loading_resource') }}</p>
            </div>
        @endif
    </div>
</x-modal>
