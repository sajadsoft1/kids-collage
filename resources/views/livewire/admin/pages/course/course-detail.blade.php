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
                <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    {{ $course->template->title }}
                </h1>
                <p class="mt-1 text-sm md:text-base text-base-content/60">
                    {{ $course->template->description }}
                </p>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- COURSE IMAGE --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    @if ($course->template->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720))
        <div class="mb-6 md:mb-8">
            <div class="relative overflow-hidden rounded-xl">
                <img src="{{ $course->template->getFirstMediaUrl('image', Constants::RESOLUTION_1280_720) }}"
                    alt="{{ $course->template->title }}"
                    class="w-full h-auto object-cover aspect-video">
                <div class="absolute top-4 right-4">
                    <x-badge :value="$course->status->title()"
                        class="badge-lg {{ $course->status->color() }}" />
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
        {{-- MAIN CONTENT --}}
        {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Course Description --}}
            @if ($course->template->body)
                <x-card>
                    <x-slot:title>
                        {{ __('توضیحات دوره') }}
                    </x-slot:title>
                    <div class="prose max-w-none">
                        {!! $course->template->body !!}
                    </div>
                </x-card>
            @endif

            {{-- Sessions List --}}
            <x-card>
                <x-slot:title>
                    {{ __('جلسات دوره') }}
                    <span class="badge badge-primary badge-sm">
                        {{ $course->sessions->count() }} {{ __('جلسه') }}
                    </span>
                </x-slot:title>

                @if ($course->sessions->count() > 0)
                    <div class="space-y-3">
                        @foreach ($course->sessions as $index => $session)
                            <div wire:key="session-{{ $session->id }}"
                                class="p-4 rounded-lg border border-base-300 bg-base-50 hover:bg-base-100 transition-colors">
                                <div class="flex items-start gap-4">
                                    {{-- Session Number --}}
                                    <div
                                        class="flex-shrink-0 w-10 h-10 rounded-full bg-primary text-primary-content flex items-center justify-center font-bold">
                                        {{ $index + 1 }}
                                    </div>

                                    {{-- Session Content --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-4 mb-2">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-base mb-1">
                                                    {{ $session->sessionTemplate?->title ?? __('جلسه') . ' ' . ($index + 1) }}
                                                </h4>
                                                @if ($session->sessionTemplate?->description)
                                                    <p class="text-sm text-base-content/60 line-clamp-2">
                                                        {{ $session->sessionTemplate->description }}
                                                    </p>
                                                @endif
                                            </div>
                                            @php
                                                $statusColor = match($session->status->value) {
                                                    'done' => 'badge-success',
                                                    'cancelled' => 'badge-error',
                                                    default => 'badge-info',
                                                };
                                            @endphp
                                            <x-badge :value="$session->status->title()"
                                                class="badge-sm {{ $statusColor }}" />
                                        </div>

                                        {{-- Session Details --}}
                                        <div class="flex flex-wrap gap-4 mt-3 text-sm">
                                            @if ($session->date)
                                                <div class="flex items-center gap-2">
                                                    <x-icon name="o-calendar" class="w-4 h-4 text-primary" />
                                                    <span>{{ $session->date->format('Y/m/d') }}</span>
                                                </div>
                                            @endif

                                            @if ($session->start_time && $session->end_time)
                                                <div class="flex items-center gap-2">
                                                    <x-icon name="o-clock" class="w-4 h-4 text-secondary" />
                                                    <span>
                                                        {{ $session->start_time->format('H:i') }} -
                                                        {{ $session->end_time->format('H:i') }}
                                                    </span>
                                                </div>
                                            @endif

                                            @if ($session->room)
                                                <div class="flex items-center gap-2">
                                                    <x-icon name="o-map-pin" class="w-4 h-4 text-info" />
                                                    <span>{{ $session->room->name }}</span>
                                                </div>
                                            @endif

                                            @if ($session->meeting_link)
                                                <div class="flex items-center gap-2">
                                                    <x-icon name="o-link" class="w-4 h-4 text-success" />
                                                    <a href="{{ $session->meeting_link }}" target="_blank"
                                                        class="link link-primary">
                                                        {{ __('لینک جلسه') }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-alert icon="o-information-circle" class="alert-info">
                        {{ __('هنوز جلسه‌ای برای این دوره ایجاد نشده است') }}
                    </x-alert>
                @endif
            </x-card>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
        {{-- SIDEBAR --}}
        {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
        <div class="space-y-6">
            {{-- Course Info Card --}}
            <x-card>
                <x-slot:title>
                    {{ __('اطلاعات دوره') }}
                </x-slot:title>

                <div class="space-y-4">
                    {{-- Price --}}
                    <div class="flex items-center justify-between p-3 rounded-lg bg-primary/10">
                        <div>
                            <div class="text-sm text-base-content/60">{{ __('قیمت') }}</div>
                            <div class="text-xl font-bold text-primary">
                                {{ number_format($course->price) }} {{ systemCurrency() }}
                            </div>
                        </div>
                        <x-icon name="o-banknotes" class="w-8 h-8 text-primary/50" />
                    </div>

                    {{-- Teacher --}}
                    @if ($course->teacher)
                        <div class="flex items-center gap-3 p-3 rounded-lg border border-base-300">
                            <x-avatar :image="$course->teacher->avatar" class="w-12 h-12" />
                            <div class="flex-1 min-w-0">
                                <div class="text-sm text-base-content/60">{{ __('استاد') }}</div>
                                <div class="font-semibold truncate">{{ $course->teacher->full_name }}</div>
                            </div>
                        </div>
                    @endif

                    {{-- Term --}}
                    @if ($course->term)
                        <div class="p-3 rounded-lg border border-base-300">
                            <div class="text-sm text-base-content/60 mb-1">{{ __('ترم') }}</div>
                            <div class="font-semibold">{{ $course->term->title ?? $course->term->name }}</div>
                            @if ($course->term->start_date && $course->term->end_date)
                                <div class="text-xs text-base-content/50 mt-1">
                                    {{ $course->term->start_date->format('Y/m/d') }} -
                                    {{ $course->term->end_date->format('Y/m/d') }}
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Capacity --}}
                    <div class="p-3 rounded-lg border border-base-300">
                        <div class="text-sm text-base-content/60 mb-1">{{ __('ظرفیت') }}</div>
                        <div class="font-semibold">
                            @if ($course->capacity)
                                {{ $course->enrollment_count }} / {{ $course->capacity }}
                                <span class="text-xs text-base-content/50">
                                    ({{ $course->available_spots }} {{ __('خالی') }})
                                </span>
                            @else
                                {{ __('نامحدود') }}
                            @endif
                        </div>
                    </div>

                    {{-- Course Type --}}
                    <div class="p-3 rounded-lg border border-base-300">
                        <div class="text-sm text-base-content/60 mb-1">{{ __('نوع دوره') }}</div>
                        <x-badge :value="$course->template->type->title()" class="badge-sm" />
                    </div>

                    {{-- Level --}}
                    @if ($course->template->level)
                        <div class="p-3 rounded-lg border border-base-300">
                            <div class="text-sm text-base-content/60 mb-1">{{ __('سطح') }}</div>
                            <x-badge :value="$course->template->level->title()" class="badge-sm badge-outline" />
                        </div>
                    @endif

                    {{-- Schedule Info --}}
                    @if ($course->requiresSchedule() && $course->days_of_week_readable !== 'Not scheduled')
                        <div class="p-3 rounded-lg border border-base-300">
                            <div class="text-sm text-base-content/60 mb-1">{{ __('زمان‌بندی') }}</div>
                            <div class="font-semibold">{{ $course->days_of_week_readable }}</div>
                            @if ($course->start_time && $course->end_time)
                                <div class="text-xs text-base-content/50 mt-1">
                                    {{ $course->start_time->format('H:i') }} -
                                    {{ $course->end_time->format('H:i') }}
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Room --}}
                    @if ($course->room)
                        <div class="p-3 rounded-lg border border-base-300">
                            <div class="text-sm text-base-content/60 mb-1">{{ __('کلاس') }}</div>
                            <div class="font-semibold">{{ $course->room->name }}</div>
                        </div>
                    @endif
                </div>
            </x-card>

            {{-- Enrollment Status Card --}}
            @if ($this->userEnrollment)
                <x-card>
                    <x-slot:title>
                        {{ __('وضعیت ثبت‌نام') }}
                    </x-slot:title>

                    <div class="space-y-4">
                        {{-- Enrollment Status --}}
                        <div class="flex items-center justify-between p-3 rounded-lg bg-info/10">
                            <div>
                                <div class="text-sm text-base-content/60">{{ __('وضعیت') }}</div>
                                <x-badge :value="$this->enrollmentStatus" class="badge-info badge-sm mt-1" />
                            </div>
                            <x-icon name="o-check-circle" class="w-8 h-8 text-info/50" />
                        </div>

                        {{-- Progress --}}
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-base-content/60">{{ __('پیشرفت') }}</span>
                                <span class="font-semibold">{{ number_format($this->userEnrollment->progress_percent, 1) }}%</span>
                            </div>
                            <progress class="progress progress-info w-full"
                                value="{{ $this->userEnrollment->progress_percent }}" max="100"></progress>
                        </div>

                        {{-- Enrolled Date --}}
                        <div class="p-3 rounded-lg border border-base-300">
                            <div class="text-sm text-base-content/60 mb-1">{{ __('تاریخ ثبت‌نام') }}</div>
                            <div class="font-semibold">
                                {{ $this->userEnrollment->enrolled_at->format('Y/m/d H:i') }}
                            </div>
                        </div>

                        {{-- Certificate --}}
                        @if ($this->userEnrollment->hasCertificate())
                            <div class="p-3 rounded-lg bg-success/10 border border-success/20">
                                <div class="flex items-center gap-2">
                                    <x-icon name="o-academic-cap" class="w-5 h-5 text-success" />
                                    <span class="font-semibold text-success">{{ __('گواهینامه صادر شده') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </x-card>
            @else
                <x-card>
                    <x-slot:title>
                        {{ __('ثبت‌نام در دوره') }}
                    </x-slot:title>

                    <div class="space-y-4">
                        @if ($this->canEnroll)
                            <x-alert icon="o-information-circle" class="alert-info">
                                {{ __('این دوره برای ثبت‌نام در دسترس است') }}
                            </x-alert>
                        @else
                            <x-alert icon="o-exclamation-triangle" class="alert-warning">
                                @if ($course->isAtCapacity())
                                    {{ __('ظرفیت دوره تکمیل شده است') }}
                                @else
                                    {{ __('این دوره در حال حاضر برای ثبت‌نام در دسترس نیست') }}
                                @endif
                            </x-alert>
                        @endif
                    </div>
                </x-card>
            @endif

            {{-- Course Stats --}}
            <x-card>
                <x-slot:title>
                    {{ __('آمار دوره') }}
                </x-slot:title>

                <div class="space-y-3">
                    @if ($course->template->session_count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-base-content/60">{{ __('تعداد جلسات') }}</span>
                            <span class="font-semibold">{{ $course->template->session_count }}</span>
                        </div>
                    @endif

                    @if ($course->template->total_duration)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-base-content/60">{{ __('مدت زمان کل') }}</span>
                            <span class="font-semibold">{{ round($course->template->total_duration / 60, 1) }} {{ __('ساعت') }}</span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-base-content/60">{{ __('تعداد ثبت‌نام‌ها') }}</span>
                        <span class="font-semibold">{{ $course->enrollment_count }}</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
