<div class="space-y-6">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <x-card title="خلاصه نتایج نظر سنجی" subtitle="{{ $exam->title }}" shadow separator>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="p-4 rounded-lg bg-base-200/60">
                <p class="text-sm text-content-muted">کل تلاش‌ها</p>
                <p class="mt-1 text-2xl font-semibold text-content">{{ number_format($summary['total_attempts']) }}</p>
            </div>
            <div class="p-4 rounded-lg bg-base-200/60">
                <p class="text-sm text-content-muted">شرکت‌کنندگان یکتا</p>
                <p class="mt-1 text-2xl font-semibold text-content">{{ number_format($summary['unique_participants']) }}
                </p>
            </div>
            <div class="p-4 rounded-lg bg-base-200/60">
                <p class="text-sm text-content-muted">نرخ اتمام</p>
                <p class="mt-1 text-2xl font-semibold text-success">{{ $summary['completion_rate'] }}%</p>
            </div>
            <div class="p-4 rounded-lg bg-base-200/60">
                <p class="text-sm text-content-muted">میانگین زمان تکمیل</p>
                <p class="mt-1 text-2xl font-semibold text-content">{{ $summary['average_completion_time'] }}</p>
            </div>
            <div class="p-4 rounded-lg bg-base-200/60">
                <p class="text-sm text-content-muted">میانگین پاسخ به سوالات</p>
                <p class="mt-1 text-2xl font-semibold text-content">{{ $summary['average_questions'] }}</p>
            </div>
            <div class="p-4 rounded-lg bg-base-200/60">
                <p class="text-sm text-content-muted">تکمیل شده</p>
                <p class="mt-1 text-2xl font-semibold text-success">{{ number_format($summary['completed_attempts']) }}
                </p>
            </div>
            <div class="p-4 rounded-lg bg-base-200/60">
                <p class="text-sm text-content-muted">در حال انجام</p>
                <p class="mt-1 text-2xl font-semibold text-warning">
                    {{ number_format($summary['in_progress_attempts']) }}</p>
            </div>
            <div class="p-4 rounded-lg bg-base-200/60">
                <p class="text-sm text-content-muted">رها شده / منقضی شده</p>
                <p class="mt-1 text-2xl font-semibold text-error">{{ number_format($summary['abandoned_attempts']) }}
                </p>
            </div>
        </div>

        <div class="mt-6 text-sm text-content-muted">
            <p>آخرین پاسخ ثبت شده: <span
                    class="font-medium text-content">{{ $summary['last_response_at'] ?? '-' }}</span></p>
        </div>
    </x-card>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            @foreach ($questionBreakdown as $question)
                <x-card shadow>
                    <x-slot:title>
                        سوال {{ $loop->iteration }}: {{ $question['title'] }}
                    </x-slot:title>
                    <x-slot:subtitle>
                        {{ \Illuminate\Support\Str::ucfirst(str_replace('_', ' ', $question['type'])) }}
                    </x-slot:subtitle>

                    @if ($question['body'])
                        <div class="p-4 mb-4 text-sm rounded-lg bg-base-200/60 text-content">
                            {!! nl2br(e($question['body'])) !!}
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-4 text-sm text-content-muted">
                        <span>تعداد پاسخ‌ها: <span
                                class="font-semibold text-content">{{ number_format($question['responses_count']) }}</span></span>
                        <span>پاسخ داده نشده: <span
                                class="font-semibold text-content">{{ number_format($question['skipped_count']) }}</span></span>
                        @if ($question['most_selected'])
                            <span>محبوب‌ترین گزینه: <span
                                    class="font-semibold text-content">{{ $question['most_selected']['content'] }}
                                    ({{ $question['most_selected']['ratio'] }}%)</span></span>
                        @endif
                    </div>

                    <div class="mt-6 space-y-4">
                        @foreach ($question['options'] as $option)
                            <div>
                                <div class="flex justify-between items-center text-sm">
                                    <span
                                        class="font-medium text-content {{ $option['is_correct'] ? 'text-success' : '' }}">
                                        {{ $option['content'] }}
                                    </span>
                                    <span class="text-content-muted">
                                        {{ number_format($option['selections']) }} پاسخ ({{ $option['ratio'] }}%)
                                    </span>
                                </div>
                                <div class="overflow-hidden mt-2 h-2 rounded-full bg-base-200">
                                    <div class="h-2 rounded-full transition-all duration-200 {{ $option['is_correct'] ? 'bg-success' : 'bg-primary' }}"
                                        style="width: {{ $option['ratio'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($question['show_explanation'] && $question['explanation'])
                        <div class="p-4 mt-6 text-sm rounded-lg border border-warning/40 bg-warning/10 text-warning">
                            <strong class="block mb-1">توضیحات:</strong>
                            {!! nl2br(e($question['explanation'])) !!}
                        </div>
                    @endif
                </x-card>
            @endforeach
        </div>

        <div class="space-y-6">
            <x-card title="آخرین تکمیل‌ها" shadow>
                @if (empty($recentAttempts))
                    <p class="text-sm text-content-muted">هنوز پاسخی ثبت نشده است.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($recentAttempts as $attempt)
                            <div class="p-3 rounded-lg border border-base-200">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="font-medium text-content">{{ $attempt['participant'] }}</span>
                                    <span class="text-content-muted">{{ $attempt['completed_at'] ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center mt-2 text-xs text-content-muted">
                                    <span>پرسش‌های پاسخ داده شده: {{ $attempt['questions_answered'] }}</span>
                                    <span>پیشرفت: {{ $attempt['progress'] }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</div>
