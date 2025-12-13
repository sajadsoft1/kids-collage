{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- COURSE INFO CARD COMPACT - Compact version for sidebar --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
@php
    use App\Helpers\Constants;
    $imageUrl = $course->template->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720);
@endphp

@if ($imageUrl || $course->template->body)
    <x-card class="mb-4">
        <x-slot:title class="text-base">
            {{ __('general.course.description') }}
        </x-slot:title>
        <div class="space-y-3">
            {{-- Image Section --}}
            @if ($imageUrl)
                <div class="relative overflow-hidden rounded-lg aspect-video">
                    <img src="{{ $imageUrl }}" alt="{{ $course->template->title }}" class="w-full h-full object-cover">
                </div>
            @endif

            {{-- Description Section --}}
            @if ($course->template->body)
                <div class="prose prose-sm max-w-none text-sm">
                    {!! $course->template->body !!}
                </div>
            @endif
        </div>
    </x-card>
@endif
