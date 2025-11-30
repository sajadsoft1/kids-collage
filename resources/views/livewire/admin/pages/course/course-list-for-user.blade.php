@php
    use App\Helpers\Constants;
@endphp

<div class="py-4 md:py-6" x-data="{ activeTab: @entangle('activeTab') }">
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- HEADER SECTION --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col gap-4">
            <div>
                <h1
                    class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    {{ __('دوره‌های من') }}
                </h1>
                <p class="mt-1 text-sm md:text-base text-base-content/60">{{ __('مشاهده و مدیریت دوره‌های شما') }}</p>
            </div>
            {{-- Search Box --}}
            <x-input wire:model.live.debounce.300ms="search" icon="o-magnifying-glass"
                placeholder="{{ __('جستجوی دوره...') }}" class="w-full" />
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- STATISTICS SECTION --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 gap-3 md:gap-4 mb-6 md:mb-8 md:grid-cols-3">
        <x-stat title="{{ __('دوره‌های موجود') }}" value="{{ $this->stats['available_count'] }}" icon="o-academic-cap"
            color="text-primary" class="border border-primary/20 bg-primary/5" />

        <x-stat title="{{ __('دوره‌های ثبت‌نام شده') }}" value="{{ $this->stats['enrolled_count'] }}" icon="o-book-open"
            color="text-info" class="border border-info/20 bg-info/5" />

        <x-stat title="{{ __('دوره‌های تکمیل شده') }}" value="{{ $this->stats['completed_count'] }}"
            icon="o-check-circle" color="text-success" class="border border-success/20 bg-success/5" />
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- TABS --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <x-tabs wire:model="activeTab" active-class="bg-primary rounded-lg !text-white"
        label-class="px-3 md:px-6 py-2 md:py-3 font-semibold text-sm md:text-base"
        label-div-class="p-1 bg-base-200 rounded-xl mb-6 md:mb-8 flex justify-center">

        {{-- TAB 1: AVAILABLE COURSES --}}
        <x-tab name="available" :label="__('دوره‌های موجود')" icon="o-academic-cap">
            @if ($this->availableCourses->count() > 0)
                <div class="grid grid-cols-1 gap-4 md:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($this->availableCourses as $course)
                        <div wire:key="course-{{ $course->id }}"
                            class="card bg-base-100 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-base-200">

                            {{-- Card Body --}}
                            <div class="card-body p-4 md:p-5">
                                {{-- Course Image --}}
                                @if ($course->template->getFirstMediaUrl('image', Constants::RESOLUTION_854_480))
                                    <div class="relative overflow-hidden rounded-lg mb-4">
                                        <img src="{{ $course->template->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) }}"
                                            alt="{{ $course->template->title }}"
                                            class="aspect-video w-full object-cover">
                                        <x-badge :value="$course->status->title()"
                                            class="badge-sm absolute top-2 right-2 {{ $course->status->color() }}" />
                                    </div>
                                @endif

                                {{-- Header: Title & Category --}}
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="card-title text-base md:text-lg line-clamp-2 mb-1">
                                            {{ $course->template->title }}
                                        </h3>
                                        @if ($course->template->category)
                                            <span
                                                class="text-xs text-base-content/50">{{ $course->template->category->title }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Description --}}
                                @if ($course->template->description)
                                    <p class="text-sm text-base-content/60 line-clamp-2 mb-3">
                                        {{ $course->template->description }}
                                    </p>
                                @endif

                                {{-- Stats Row --}}
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if ($course->template->session_count)
                                        <div class="flex items-center gap-1 text-xs bg-base-200 px-2 py-1 rounded-md">
                                            <x-icon name="o-document-text" class="w-3.5 h-3.5 text-primary" />
                                            <span>{{ $course->template->session_count }} {{ __('جلسه') }}</span>
                                        </div>
                                    @endif
                                    @if ($course->template->total_duration)
                                        <div class="flex items-center gap-1 text-xs bg-base-200 px-2 py-1 rounded-md">
                                            <x-icon name="o-clock" class="w-3.5 h-3.5 text-secondary" />
                                            <span>{{ round($course->template->total_duration / 60, 1) }}
                                                {{ __('ساعت') }}</span>
                                        </div>
                                    @endif
                                    @if ($course->teacher)
                                        <div class="flex items-center gap-1 text-xs bg-base-200 px-2 py-1 rounded-md">
                                            <x-icon name="o-user" class="w-3.5 h-3.5 text-info" />
                                            <span>{{ $course->teacher->full_name }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Price and Capacity --}}
                                <div class="flex items-center justify-between mb-4 p-3 bg-primary/10 rounded-lg">
                                    <div>
                                        <div class="text-lg font-bold text-primary">
                                            {{ number_format($course->price) }} {{ systemCurrency() }}
                                        </div>
                                    </div>
                                    <div class="text-xs text-base-content/60">
                                        @if ($course->capacity)
                                            {{ $course->available_spots }} / {{ $course->capacity }}
                                            {{ __('ظرفیت') }}
                                        @else
                                            {{ __('نامحدود') }}
                                        @endif
                                    </div>
                                </div>

                                {{-- Action Button --}}
                                <div class="card-actions mt-auto">
                                    <x-button :link="route('admin.course.show', [
                                        'courseTemplate' => $course->template->id,
                                        'course' => $course->id,
                                    ])" class="btn-primary w-full" icon="o-eye" wire:navigate>
                                        {{ __('مشاهده جزئیات') }}
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <x-card class="text-center py-12 md:py-16">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-24 h-24 md:w-32 md:h-32 mb-4 md:mb-6 rounded-full bg-gradient-to-br from-base-200 to-base-300 flex items-center justify-center">
                            <x-icon name="o-academic-cap" class="w-12 h-12 md:w-16 md:h-16 text-base-content/30" />
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-base-content/70 mb-2">
                            {{ __('دوره‌ای موجود نیست') }}</h3>
                        <p class="text-base-content/50 text-center text-sm md:text-base max-w-md">
                            {{ __('در حال حاضر دوره‌ای برای ثبت‌نام موجود نیست') }}</p>
                    </div>
                </x-card>
            @endif
        </x-tab>

        {{-- TAB 2: ENROLLED COURSES --}}
        <x-tab name="enrolled" :label="__('دوره‌های ثبت‌نام شده')" icon="o-book-open">
            @if ($this->enrolledCourses->count() > 0)
                <div class="grid grid-cols-1 gap-4 md:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($this->enrolledCourses as $enrollment)
                        @php
                            $course = $enrollment->course;
                        @endphp
                        <div wire:key="enrolled-{{ $enrollment->id }}"
                            class="card bg-base-100 shadow-md hover:shadow-xl transition-all duration-300 border-2 border-info/30">

                            {{-- Progress Bar Header --}}
                            <div class="h-2 bg-base-200 rounded-t-2xl overflow-hidden">
                                <div class="h-full bg-info transition-all duration-500"
                                    style="width: {{ min($enrollment->progress_percent, 100) }}%"></div>
                            </div>

                            {{-- Card Body --}}
                            <div class="card-body p-4 md:p-5">
                                {{-- Course Image --}}
                                @if ($course->template->getFirstMediaUrl('image', Constants::RESOLUTION_854_480))
                                    <div class="relative overflow-hidden rounded-lg mb-4">
                                        <img src="{{ $course->template->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) }}"
                                            alt="{{ $course->template->title }}"
                                            class="aspect-video w-full object-cover">
                                    </div>
                                @endif

                                {{-- Header: Title & Badge --}}
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="card-title text-base md:text-lg line-clamp-2 mb-1">
                                            {{ $course->template->title }}
                                        </h3>
                                        @if ($course->template->category)
                                            <span
                                                class="text-xs text-base-content/50">{{ $course->template->category->title }}</span>
                                        @endif
                                    </div>
                                    <x-badge :value="$enrollment->status->title()" class="badge-info badge-sm shrink-0" />
                                </div>

                                {{-- Progress Info --}}
                                <div class="flex items-center justify-between mb-4 p-3 bg-info/10 rounded-lg">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-info">
                                            {{ number_format($enrollment->progress_percent, 1) }}%</div>
                                        <div class="text-xs text-base-content/60">{{ __('پیشرفت') }}</div>
                                    </div>
                                    <div class="divider divider-horizontal"></div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-base-content">
                                            {{ $enrollment->enrolled_at->diffForHumans() }}</div>
                                        <div class="text-xs text-base-content/60">{{ __('ثبت‌نام شده') }}</div>
                                    </div>
                                </div>

                                {{-- Course Info --}}
                                <div class="space-y-2 text-sm mb-4">
                                    @if ($course->teacher)
                                        <div class="flex justify-between">
                                            <span class="text-base-content/60">{{ __('استاد') }}</span>
                                            <span class="font-medium">{{ $course->teacher->full_name }}</span>
                                        </div>
                                    @endif
                                    @if ($course->term)
                                        <div class="flex justify-between">
                                            <span class="text-base-content/60">{{ __('ترم') }}</span>
                                            <span
                                                class="font-medium">{{ $course->term->title ?? $course->term->name }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Action Button --}}
                                <div class="card-actions mt-auto">
                                    <x-button :link="route('admin.course.show', [
                                        'courseTemplate' => $course->template->id,
                                        'course' => $course->id,
                                    ])" class="btn-info w-full" icon="o-eye" wire:navigate>
                                        {{ __('مشاهده دوره') }}
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <x-card class="text-center py-12 md:py-16">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-24 h-24 md:w-32 md:h-32 mb-4 md:mb-6 rounded-full bg-gradient-to-br from-info/20 to-base-300 flex items-center justify-center">
                            <x-icon name="o-book-open" class="w-12 h-12 md:w-16 md:h-16 text-info/50" />
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-base-content/70 mb-2">
                            {{ __('دوره‌ای ثبت‌نام نشده') }}
                        </h3>
                        <p class="text-base-content/50 text-center text-sm md:text-base max-w-md">
                            {{ __('شما در هیچ دوره‌ای ثبت‌نام نکرده‌اید') }}</p>
                        <x-button wire:click="switchTab('available')" class="btn-primary mt-4" icon="o-academic-cap">
                            {{ __('مشاهده دوره‌های موجود') }}
                        </x-button>
                    </div>
                </x-card>
            @endif
        </x-tab>

        {{-- TAB 3: COMPLETED COURSES --}}
        <x-tab name="completed" :label="__('دوره‌های تکمیل شده')" icon="o-check-circle">
            @if ($this->completedCourses->count() > 0)
                <div class="grid grid-cols-1 gap-4 md:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($this->completedCourses as $enrollment)
                        @php
                            $course = $enrollment->course;
                        @endphp
                        <div wire:key="completed-{{ $enrollment->id }}"
                            class="card bg-base-100 shadow-md hover:shadow-xl transition-all duration-300 border border-base-200">

                            {{-- Status Color Bar --}}
                            <div class="h-1 bg-success rounded-t-2xl">
                            </div>

                            {{-- Card Body --}}
                            <div class="card-body p-4 md:p-5">
                                {{-- Course Image --}}
                                @if ($course->template->getFirstMediaUrl('image', Constants::RESOLUTION_854_480))
                                    <div class="relative overflow-hidden rounded-lg mb-4">
                                        <img src="{{ $course->template->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) }}"
                                            alt="{{ $course->template->title }}"
                                            class="aspect-video w-full object-cover">
                                    </div>
                                @endif

                                {{-- Header --}}
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="card-title text-base md:text-lg line-clamp-2 mb-1">
                                            {{ $course->template->title }}
                                        </h3>
                                        <x-badge value="{{ __('تکمیل شده') }}" class="badge-success badge-xs" />
                                    </div>
                                </div>

                                {{-- Progress Circle --}}
                                <div class="flex justify-center my-4">
                                    <div class="radial-progress text-success"
                                        style="--value:100; --size:5rem; --thickness:6px;" role="progressbar">
                                        <span class="text-lg font-bold">100%</span>
                                    </div>
                                </div>

                                {{-- Details --}}
                                <div class="space-y-2 text-sm">
                                    @if ($course->teacher)
                                        <div class="flex justify-between">
                                            <span class="text-base-content/60">{{ __('استاد') }}</span>
                                            <span class="font-medium">{{ $course->teacher->full_name }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-base-content/60">{{ __('تکمیل شده در') }}</span>
                                        <span
                                            class="font-medium text-xs">{{ $enrollment->updated_at?->format('Y/m/d') ?? '-' }}</span>
                                    </div>
                                    @if ($enrollment->hasCertificate())
                                        <div class="flex justify-between">
                                            <span class="text-base-content/60">{{ __('گواهینامه') }}</span>
                                            <x-badge value="{{ __('صادر شده') }}" class="badge-success badge-xs" />
                                        </div>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="card-actions mt-4 pt-4 border-t border-base-200">
                                    <x-button :link="route('admin.course.show', [
                                        'courseTemplate' => $course->template->id,
                                        'course' => $course->id,
                                    ])" class="btn-outline btn-primary flex-1 btn-sm"
                                        icon="o-eye" wire:navigate>
                                        {{ __('مشاهده جزئیات') }}
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <x-card class="text-center py-12 md:py-16">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-24 h-24 md:w-32 md:h-32 mb-4 md:mb-6 rounded-full bg-gradient-to-br from-base-200 to-base-300 flex items-center justify-center">
                            <x-icon name="o-check-circle" class="w-12 h-12 md:w-16 md:h-16 text-base-content/30" />
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-base-content/70 mb-2">
                            {{ __('دوره‌ای تکمیل نشده') }}</h3>
                        <p class="text-base-content/50 text-center text-sm md:text-base max-w-md">
                            {{ __('شما هنوز هیچ دوره‌ای را تکمیل نکرده‌اید') }}</p>
                    </div>
                </x-card>
            @endif
        </x-tab>
    </x-tabs>
</div>
