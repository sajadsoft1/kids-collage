@php
    use App\Helpers\Constants;
@endphp

<div class="py-4 md:py-6">
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- HEADER SECTION --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col gap-4">
            <div>
                <h1 class="text-2xl font-bold md:text-3xl">
                    {{ __('course.all_courses') }}
                </h1>
                <p class="mt-1 text-sm md:text-base text-base-content/60">{{ __('course.view_and_manage_courses') }}</p>
            </div>

            {{-- Filters Row --}}
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                {{-- Search Box --}}
                <div class="flex-1">
                    <x-input wire:model.live.debounce.300ms="search" icon="o-magnifying-glass"
                        placeholder="{{ __('course.search_in_courses') }}" class="w-full" />
                </div>

                {{-- Status Filter --}}
                <div class="w-full md:w-auto md:min-w-[180px]">
                    <x-select wire:model.live="statusFilter" :options="$this->statusFilters" option-label="label" option-value="value"
                        class="w-full" />
                </div>

                {{-- Sort Dropdown --}}
                <div class="w-full md:w-auto md:min-w-[180px]">
                    <x-select wire:model.live="sortBy" :options="[
                        ['value' => 'latest', 'label' => __('course.sort_by_latest')],
                        ['value' => 'oldest', 'label' => __('course.sort_by_oldest')],
                        ['value' => 'title_asc', 'label' => __('course.sort_by_title_asc')],
                        ['value' => 'title_desc', 'label' => __('course.sort_by_title_desc')],
                    ]" option-label="label" option-value="value"
                        class="w-full" />
                </div>

                {{-- Category Filter --}}
                <div class="w-full md:w-auto md:min-w-[180px]">
                    <x-select wire:model.live="categoryId" :options="$this->categories" option-label="label" option-value="value"
                        placeholder="{{ __('course.all_category') }}" placeholder-value="" class="w-full" />
                </div>

                {{-- Level Filter --}}
                <div class="w-full md:w-auto md:min-w-[180px]">
                    <x-select wire:model.live="level" :options="$this->levels" option-label="label" option-value="value"
                        placeholder="{{ __('course.all_level') }}" placeholder-value="" class="w-full" />
                </div>

                {{-- Type Filter --}}
                <div class="w-full md:w-auto md:min-w-[180px]">
                    <x-select wire:model.live="type" :options="$this->types" option-label="label" option-value="value"
                        placeholder="{{ __('course.all_type') }}" placeholder-value="" class="w-full" />
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- STATISTICS SECTION --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-3 mb-6 md:gap-4 md:mb-8 md:grid-cols-3">
        <x-stat title="{{ __('course.all_courses') }}" value="{{ $this->stats['all_count'] }}" icon="o-academic-cap"
            color="text-primary" class="border border-primary/20 bg-primary/5" />

        <x-stat title="{{ __('general.in_progress') }}" value="{{ $this->stats['in_progress_count'] }}"
            icon="o-book-open" color="text-info" class="border border-info/20 bg-info/5" />

        <x-stat title="{{ __('general.completed') }}" value="{{ $this->stats['completed_count'] }}"
            icon="o-check-circle" color="text-success" class="border border-success/20 bg-success/5" />
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- COURSES LIST --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    @if ($this->allCourses->count() > 0)
        <div class="grid grid-cols-1 gap-4 md:gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($this->allCourses as $enrollment)
                @php
                    $course = $enrollment->course;
                    $isCompleted = $enrollment->progress_percent >= 100.0;
                @endphp
                <div wire:key="course-{{ $enrollment->id }}"
                    class="border shadow-md transition-all duration-300 card bg-base-100 hover:shadow-xl {{ $isCompleted ? 'border-success/30' : 'border-info/30' }}">

                    {{-- Progress Bar Header --}}
                    @if (!$isCompleted)
                        <div class="overflow-hidden h-2 rounded-t-2xl bg-base-200">
                            <div class="h-full transition-all duration-500 bg-info"
                                style="width: {{ min($enrollment->progress_percent, 100) }}%"></div>
                        </div>
                    @else
                        <div class="h-1 rounded-t-2xl bg-success"></div>
                    @endif

                    {{-- Card Body --}}
                    <div class="p-4 card-body md:p-5">
                        {{-- Course Image --}}
                        @if ($course->template->getFirstMediaUrl('image', Constants::RESOLUTION_854_480))
                            <div class="overflow-hidden relative mb-4 rounded-lg">
                                <img src="{{ $course->template->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) }}"
                                    alt="{{ $course->template->title }}" class="object-cover w-full aspect-video">
                                @if ($isCompleted)
                                    <x-badge value="{{ __('general.completed') }}"
                                        class="absolute top-2 right-2 badge-success badge-sm" />
                                @else
                                    <x-badge :value="$enrollment->status->title()"
                                        class="badge-{{ $enrollment->status->color() }} badge-sm absolute top-2 right-2" />
                                @endif
                            </div>
                        @endif

                        {{-- Header: Title & Badge --}}
                        <div class="flex gap-3 items-start mb-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="mb-1 text-base card-title md:text-lg line-clamp-2">
                                    {{ $course->template->title }}
                                </h3>
                                @if ($course->template->category)
                                    <span
                                        class="text-xs text-base-content/50">{{ $course->template->category->title }}</span>
                                @endif
                            </div>
                            @if (!$isCompleted)
                                <x-badge :value="$enrollment->status->title()"
                                    class="badge-{{ $enrollment->status->color() }} badge-sm shrink-0" />
                            @endif
                        </div>


                        <div class="flex justify-between items-center p-3 mb-4 rounded-lg bg-info/10">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-info">
                                    {{ number_format($enrollment->progress_percent, 1) }}%</div>
                                <div class="text-xs text-base-content/60">{{ __('course.progress') }}</div>
                            </div>
                            <div class="divider divider-horizontal"></div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-base-content">
                                    {{ $enrollment->enrolled_at->diffForHumans() }}</div>
                                <div class="text-xs text-base-content/60">{{ __('course.enrolled_at') }}</div>
                            </div>
                        </div>

                        {{-- Course Info --}}
                        <div class="mb-4 space-y-2 text-sm">
                            @if ($course->teacher)
                                <div class="flex justify-between">
                                    <span class="text-base-content/60">{{ __('course.teacher') }}</span>
                                    <span class="font-medium">{{ $course->teacher->full_name }}</span>
                                </div>
                            @endif
                            @if ($course->term)
                                <div class="flex justify-between">
                                    <span class="text-base-content/60">{{ __('course.term') }}</span>
                                    <span class="font-medium">{{ $course->term->title ?? $course->term->name }}</span>
                                </div>
                            @endif
                            @if ($isCompleted)
                                <div class="flex justify-between">
                                    <span class="text-base-content/60">{{ __('course.completed_at') }}</span>
                                    <span
                                        class="text-xs font-medium">{{ $enrollment->updated_at?->format('Y/m/d') ?? '-' }}</span>
                                </div>
                                @if ($enrollment->hasCertificate())
                                    <div class="flex justify-between">
                                        <span class="text-base-content/60">{{ __('course.certificate') }}</span>
                                        <x-badge value="{{ __('course.certificate_issued') }}"
                                            class="badge-success badge-xs" />
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Action Button --}}
                        <div class="mt-auto card-actions">
                            <x-button :link="route('admin.course.show', [
                                'courseTemplate' => $course->template->id,
                                'course' => $course->id,
                            ])"
                                class="w-full {{ $isCompleted ? 'btn-outline btn-primary' : 'btn-info' }}"
                                icon="o-eye" wire:navigate>
                                {{ $isCompleted ? __('course.view_details') : __('course.view_course') }}
                            </x-button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <x-card class="py-12 text-center md:py-16">
            <div class="flex flex-col items-center">
                <div
                    class="flex justify-center items-center mb-4 w-24 h-24 bg-gradient-to-br rounded-full md:w-32 md:h-32 md:mb-6 from-base-200 to-base-300">
                    <x-icon name="o-academic-cap" class="w-12 h-12 md:w-16 md:h-16 text-base-content/30" />
                </div>
                <h3 class="mb-2 text-lg font-bold md:text-xl text-base-content/70">
                    {{ __('course.no_course_found') }}
                </h3>
                <p class="max-w-md text-sm text-center text-base-content/50 md:text-base">
                    {{ __('course.no_course_enrolled') }}</p>
            </div>
        </x-card>
    @endif
</div>
