{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- COURSE HEADER - Minimal Header with Key Information --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<div class="mb-6">
    <div class="flex flex-wrap gap-4 items-center p-4 rounded-lg border md:gap-6 bg-base-100 border-base-300">
        {{-- Course Title --}}
        <div class="flex-1 min-w-0">
            <h1 class="text-xl font-bold truncate md:text-2xl">
                {{ $course->template->title }}
            </h1>
        </div>

        {{-- Teacher --}}
        @if ($course->teacher)
            <div class="flex gap-2 items-center">
                <x-avatar :image="$course->teacher->avatar" class="w-8 h-8" />
                <div class="hidden sm:block">
                    <div class="text-xs text-base-content/60">{{ __('validation.attributes.teacher') }}</div>
                    <div class="text-sm font-semibold truncate max-w-[120px]">{{ $course->teacher->full_name }}</div>
                </div>
            </div>
        @endif

        {{-- Price --}}
        <div class="flex gap-2 items-center">
            <x-icon name="o-banknotes" class="w-5 h-5 text-primary" />
            <div>
                <div class="text-xs text-base-content/60">{{ __('validation.attributes.price') }}</div>
                <div class="text-sm font-bold text-primary">
                    {{ number_format($course->price) }} {{ systemCurrency() }}
                </div>
            </div>
        </div>

        {{-- Capacity --}}
        <div class="flex gap-2 items-center">
            <x-icon name="o-users" class="w-5 h-5 text-info" />
            <div>
                <div class="text-xs text-base-content/60">{{ __('validation.attributes.capacity') }}</div>
                <div class="text-sm font-semibold">
                    @if ($course->capacity)
                        {{ $course->enrollment_count }} / {{ $course->capacity }}
                    @else
                        {{ __('validation.attributes.unlimited') }}
                    @endif
                </div>
            </div>
        </div>

        {{-- Status Badge --}}
        <x-badge :value="$course->status->title()" class="badge-sm {{ $course->status->color() }}" />

        {{-- Course Type Badge --}}
        <x-badge :value="$course->template->type->title()" class="badge-sm badge-outline" />
    </div>
</div>
