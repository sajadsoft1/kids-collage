{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- COURSE INFO CARD - Image and Description Combined --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
@php
    use App\Helpers\Constants;
    $imageUrl = $course->template->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720);
@endphp

@if ($imageUrl || $course->template->body)
    <x-card class="mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            {{-- Image Section --}}
            @if ($imageUrl)
                <div class="flex-shrink-0 w-full md:w-48 lg:w-64">
                    <div class="relative overflow-hidden rounded-lg aspect-video md:aspect-[4/3]">
                        <img src="{{ $imageUrl }}" alt="{{ $course->template->title }}"
                            class="w-full h-full object-cover">
                    </div>
                </div>
            @endif

            {{-- Description Section --}}
            @if ($course->template->body)
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold mb-2">{{ __('توضیحات دوره') }}</h3>
                    <div class="prose max-w-none text-sm md:text-base">
                        {!! $course->template->body !!}
                    </div>
                </div>
            @endif
        </div>
    </x-card>
@endif
