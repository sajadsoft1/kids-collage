@php
    use App\Models\Exam;
@endphp
<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    @can('create', Exam::class)
        <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex gap-3 items-center">
                <label for="survey-view-mode" class="text-sm font-medium text-content-muted">
                    فیلتر نمایش
                </label>
                <select id="survey-view-mode" wire:model.live="viewMode" class="w-56 select select-bordered select-sm">
                    @foreach ($viewModeOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="text-sm text-content-muted">
                تعداد نظر سنجی‌ها:
                <span class="font-semibold text-content">{{ $surveys->total() }}</span>
            </div>
        </div>
    @endcan
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($surveys as $survey)
            <div wire:key="survey-card-{{ $survey->id }}"
                class="flex min-h-[320px] flex-col justify-between rounded-xl border border-base-300 bg-base-100 p-6 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div class="flex gap-3 justify-between items-center">
                        <span
                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $survey->user_state['badge_class'] ?? 'bg-base-200 text-base-content/70' }}">
                            {{ $survey->user_state['label'] ?? 'نامشخص' }}
                        </span>

                        <span class="text-xs text-content-muted">
                            تعداد سوالات: {{ $survey->questions_count ?? $survey->getQuestionsCount() }}
                        </span>
                    </div>

                    <div>
                        <h3 class="mb-2 text-lg font-semibold text-content">
                            {{ $survey->title }}
                        </h3>

                        @if (filled($survey->description))
                            <p class="text-sm leading-6 text-content-muted">
                                {{ \Illuminate\Support\Str::limit(strip_tags($survey->description), 140) }}
                            </p>
                        @endif
                    </div>

                    @if (!empty($survey->user_state['helper']))
                        <p class="text-xs text-content-muted/80">
                            {{ $survey->user_state['helper'] }}
                        </p>
                    @endif
                </div>

                <div class="flex flex-col gap-3 mt-4">
                    @if ($survey->user_state['action_enabled'] ?? false)
                        <x-button wire:click="take({{ $survey->id }})" class="w-full btn btn-primary btn-sm" spinner
                            wire:target="take({{ $survey->id }})" wire:loading.attr="disabled" :label="$survey->user_state['action_label']" />
                    @endif

                    <div class="flex gap-3">
                        @can('update', $survey)
                            <x-button wire:click="edit({{ $survey->id }})" class="flex-1 btn btn-outline btn-sm"
                                wire:target="edit({{ $survey->id }})" wire:loading.attr="disabled" spinner
                                :label="trans('general.edit')" />
                        @endcan
                        @can('update', $survey)
                            <x-button wire:click="results({{ $survey->id }})" class="flex-1 btn btn-outline btn-sm"
                                wire:target="results({{ $survey->id }})" wire:loading.attr="disabled" spinner
                                :label="trans('general.results')" />
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div
                    class="flex flex-col justify-center items-center p-10 text-center rounded-xl border border-dashed border-base-300 bg-base-100">
                    <x-icon name="o-clipboard-document-list" class="mb-4 w-12 h-12 text-base-content/40" />
                    <p class="text-base font-semibold text-content">
                        هنوز نظر سنجی‌ای برای نمایش وجود ندارد.
                    </p>
                    <p class="mt-2 text-sm text-content-muted">
                        با انتخاب فیلتر مناسب یا ایجاد یک نظر سنجی جدید شروع کنید.
                    </p>
                </div>
            </div>
        @endforelse

        @if ($canCreateSurvey ?? false)
            <div wire:click="create"
                class="flex min-h-[320px] w-full cursor-pointer flex-col items-center justify-center rounded-xl border border-gray-200 bg-base-100 p-6 text-center text-content shadow-sm transition-all duration-150
                    hover:border-primary/60 hover:bg-primary/5 hover:shadow-md
                    active:scale-[0.98] active:shadow-none">
                <x-icon name="o-plus" class="w-14 h-14 text-primary" />
                <h3 class="mt-4 text-base font-medium text-content-muted">
                    نظر سنجی جدید
                </h3>
            </div>
        @endif
    </div>

    <div class="mt-6">
        {{ $surveys->links() }}
    </div>
</div>
