@php
    use App\Enums\BooleanEnum;
    use App\Enums\ExamStatusEnum;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <x-tabs wire:model="selectedTab" active-class="bg-primary rounded !text-white" label-class="px-4 py-3 font-semibold"
        label-div-class="p-2 rounded bg-base-100">
        <x-tab name="basic" label="اطلاعات پایه" icon="o-document-text">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="grid grid-cols-1 col-span-2 gap-4">
                    <x-card :title="trans('general.page_sections.data')" shadow separator progress-indicator="submit">
                        <div class="grid grid-cols-1 gap-4">
                            <x-input :label="trans('validation.attributes.title')" wire:model="title" required />
                            <x-textarea :label="trans('validation.attributes.description')" wire:model="description" />

                        </div>
                    </x-card>
                </div>
                <div class="col-span-1">
                    <div class="sticky top-16">
                        <x-card :title="trans('general.page_sections.publish_config')" shadow separator progress-indicator="submit">
                            <div class="grid grid-cols-1 gap-4">
                                <x-admin.shared.smart-datetime :label="trans('validation.attributes.start_date')" :default-date="$starts_at"
                                    wire-property-name="starts_at" required />
                                <x-admin.shared.smart-datetime :label="trans('validation.attributes.end_date')" :default-date="$ends_at"
                                    wire-property-name="ends_at" required />
                                <x-select :label="trans('validation.attributes.status')" wire:model="status" :options="ExamStatusEnum::formatedCases()" option-value="value"
                                    required option-label="label" :placeholder="trans('general.please_select_an_option')" placeholder-value="" />
                            </div>
                        </x-card>
                    </div>
                </div>
            </div>
        </x-tab>

        <x-tab name="questions" label="سوالات" icon="o-question-mark-circle">
            <div wire:key="questions-tab-wrapper">
                <x-card title="سوالات نظر سنجی" shadow separator>
                    <x-slot:menu>
                        <x-button wire:click="addQuestion" label="افزودن سوال" icon="o-plus"
                            wire:loading.attr="disabled" wire:target="addQuestion" spinner="addQuestion"
                            class="btn-sm btn-primary" />
                    </x-slot:menu>
                    <div class="space-y-4">
                        <div class="space-y-6">
                            @foreach ($questions as $questionIndex => $question)
                                <x-card class="border border-base-300">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex gap-2 items-center">
                                            <span class="text-sm font-medium">سوال {{ $questionIndex + 1 }}</span>
                                            <div class="flex gap-1">
                                                @if ($questionIndex > 0)
                                                    <x-button wire:click="moveQuestion({{ $questionIndex }}, 'up')"
                                                        icon="o-arrow-up" class="btn-xs btn-ghost"
                                                        wire:loading.attr="disabled"
                                                        wire:target="moveQuestion({{ $questionIndex }}, 'up')"
                                                        spinner="moveQuestion({{ $questionIndex }}, 'up')" />
                                                @endif
                                                @if ($questionIndex < count($questions) - 1)
                                                    <x-button wire:click="moveQuestion({{ $questionIndex }}, 'down')"
                                                        icon="o-arrow-down" class="btn-xs btn-ghost"
                                                        wire:loading.attr="disabled"
                                                        wire:target="moveQuestion({{ $questionIndex }}, 'down')"
                                                        spinner="moveQuestion({{ $questionIndex }}, 'down')" />
                                                @endif
                                            </div>
                                        </div>
                                        <x-button wire:click="removeQuestion({{ $questionIndex }})" label="حذف سوال"
                                            icon="o-trash" class="btn-sm btn-error" wire:loading.attr="disabled"
                                            wire:target="removeQuestion({{ $questionIndex }})"
                                            spinner="removeQuestion({{ $questionIndex }})" />
                                    </div>

                                    <div class="grid grid-cols-1 gap-4">
                                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                                            <x-select :label="trans('validation.attributes.type')"
                                                wire:model.live="questions.{{ $questionIndex }}.type"
                                                :options="$questionTypes" option-label="label" option-value="value"
                                                :placeholder="trans('general.please_select_an_option')" placeholder-value="" required />

                                            <x-input :label="trans('validation.attributes.title')"
                                                wire:model="questions.{{ $questionIndex }}.title" required />

                                            <x-textarea :label="trans('validation.attributes.body')"
                                                wire:model="questions.{{ $questionIndex }}.body" />

                                            <x-textarea :label="trans('validation.attributes.explanation')"
                                                wire:model="questions.{{ $questionIndex }}.explanation" />
                                        </div>

                                        @if ($question['type'] ?? '')
                                            <div wire:loading.flex wire:target="questions.{{ $questionIndex }}.type"
                                                class="justify-center py-8">
                                                <x-loading class="loading-infinity loading-lg" />
                                            </div>
                                            <div wire:loading.remove wire:target="questions.{{ $questionIndex }}.type">
                                                <div wire:key="question-builder-{{ $questionIndex }}-{{ $question['type'] ?? '' }}"
                                                    x-data="{ questionIndex: {{ $questionIndex }} }"
                                                    @options-updated.window="
                                                    if ($event.detail && $event.detail.index === {{ $questionIndex }}) {
                                                        $wire.set('questions.{{ $questionIndex }}.options', $event.detail.options || $event.detail);
                                                    }
                                                "
                                                    @config-updated.window="
                                                    if ($event.detail && $event.detail.index === {{ $questionIndex }}) {
                                                        $wire.set('questions.{{ $questionIndex }}.config', $event.detail.config || $event.detail);
                                                    }
                                                "
                                                    @correct-answer-updated.window="
                                                    if ($event.detail && $event.detail.index === {{ $questionIndex }}) {
                                                        $wire.set('questions.{{ $questionIndex }}.correct_answer', $event.detail.correct_answer || $event.detail);
                                                    }
                                                ">
                                                    @switch($question['type'])
                                                        @case(\App\Enums\QuestionTypeEnum::SINGLE_CHOICE->value)
                                                            <livewire:admin.pages.question-builder.single-choice
                                                                :options="$question['options'] ?? []" :config="$question['config'] ?? []" :question-index="$questionIndex"
                                                                :has-correct-answer="(bool) ($question['config'][
                                                                    'has_correct_answer'
                                                                ] ?? false)" :key="'survey-single-choice-' . $questionIndex" />
                                                        @break

                                                        @case(\App\Enums\QuestionTypeEnum::MULTIPLE_CHOICE->value)
                                                            <livewire:admin.pages.question-builder.multiple-choice
                                                                :options="$question['options'] ?? []" :config="$question['config'] ?? []" :question-index="$questionIndex"
                                                                :has-correct-answer="(bool) ($question['config'][
                                                                    'has_correct_answer'
                                                                ] ?? false)" :key="'survey-multiple-choice-' . $questionIndex" />
                                                        @break

                                                        @default
                                                            <div class="p-4 text-center text-base-content/60">
                                                                <p>سوالات از نوع {{ $question['type'] }} هنوز پشتیبانی
                                                                    نمی‌شوند.
                                                                </p>
                                                            </div>
                                                    @endswitch
                                                </div>
                                            </div>
                                        @else
                                            <div class="p-4 text-center text-base-content/60">
                                                <p>لطفاً نوع سوال را انتخاب کنید.</p>
                                            </div>
                                        @endif
                                    </div>
                                </x-card>
                            @endforeach
                        </div>

                        @if (empty($questions))
                            @include('components.admin.shared.empty-view', [
                                'title' => 'هیچ سوالی اضافه نشده است.',
                                'description' => 'برای شروع، یک سوال اضافه کنید.',
                            ])
                        @endif
                    </div>
                </x-card>
            </div>
        </x-tab>

        <x-tab name="rules" label="قوانین شرکت" icon="o-shield-check">
            <div wire:key="rules-tab-wrapper">
                <x-card shadow>
                    <div wire:key="rules-tab-content">
                        @if (isset($rules))
                            <livewire:admin.pages.exam.exam-rule-builder :rules="$rules"
                                wire:key="rule-builder-{{ $model->id ?? 'new' }}" />
                        @else
                            <div class="py-8 text-center text-base-content/60">
                                <p>در حال بارگذاری...</p>
                            </div>
                        @endif
                    </div>
                </x-card>
            </div>
        </x-tab>
    </x-tabs>

    <x-admin.shared.form-actions />
</form>
