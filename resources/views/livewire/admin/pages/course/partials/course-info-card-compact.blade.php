{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- COURSE INFO CARD COMPACT - Compact version for sidebar --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
@php
    use App\Helpers\Constants;
    $imageUrl = $course->template->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720);
@endphp

@if ($imageUrl || $course->template->description)
    <x-card class="mb-4">
        <x-slot:actions>
            <x-button class="btn-xs btn-outline btn-primary" icon="o-link" :link="config('app.frontend_url') . '/course/' . $course->template->slug" external no-wire-navigate>
                {{ __('validation.attributes.view_in_website') }}
            </x-button>
        </x-slot:actions>
        <div class="space-y-3">
            {{-- Image Section --}}
            @if ($imageUrl)
                <div class="overflow-hidden relative rounded-lg aspect-video">
                    <img src="{{ $imageUrl }}" alt="{{ $course->template->title }}" class="object-cover w-full h-full">
                </div>
            @endif

            {{-- Description Section --}}
            @if ($course->template->description)
                <div class="max-w-none text-sm prose prose-sm">
                    {!! $course->template->description !!}
                </div>
            @endif
        </div>
    </x-card>
@endif
