@php
    use App\Enums\CategoryTypeEnum;
    use App\Enums\ExamTypeEnum;
@endphp

<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <x-tabs wire:model="selectedTab" active-class="bg-primary rounded !text-white" label-class="px-4 py-2 font-semibold"
        label-div-class="p-2 mx-auto rounded bg-primary/5 w-fit">
        <x-tab name="basic" :label="trans('exam.form.basic_tab')" icon="o-document-text">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="space-y-4 lg:col-span-2">
                    <x-card :title="trans('exam.form.basic_information')" shadow separator progress-indicator="submit">
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                            <x-input :label="trans('validation.attributes.title')" wire:model.live="form.title" required />
                            <x-select :label="trans('validation.attributes.category_id')" wire:model.live="form.category_id" :options="$categories"
                                option-label="label" option-value="value" :placeholder="trans('general.please_select_an_option')" placeholder-value="">
                                <x-slot:append>
                                    <x-button class="join-item btn-primary" icon="o-plus" :link="route('admin.category.create', [
                                        'type' => CategoryTypeEnum::EXAM->value,
                                    ])" external
                                        :tooltip-bottom="trans('general.page.create.title', [
                                            'model' => trans('category.model'),
                                        ])" />
                                </x-slot:append>
                            </x-select>
                            <div class="lg:col-span-2">
                                <x-textarea :label="trans('validation.attributes.description')" wire:model.live="form.description" rows="4" />
                            </div>
                        </div>
                    </x-card>

                    <x-card :title="trans('exam.form.scoring_settings')" shadow separator progress-indicator="submit">
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

                            <x-input type="number" inputmode="numeric" min="1" step="1" :label="trans('exam.duration_label')"
                                wire:model.live="form.duration" />

                            @if ($form['type'] === ExamTypeEnum::SCORED->value)
                                <x-input type="number" inputmode="decimal" step="0.5" min="1"
                                    :label="trans('validation.attributes.total_score')" wire:model.live="form.total_score" />
                                <x-input type="number" inputmode="decimal" step="0.5" min="0"
                                    :label="trans('validation.attributes.pass_score')" wire:model.live="form.pass_score" />
                            @endif

                            <x-input type="number" inputmode="numeric" min="1" :label="trans('validation.attributes.max_attempts')"
                                wire:model.live="form.max_attempts" />
                        </div>
                    </x-card>

                    <x-card :title="trans('exam.form.schedule_settings')" shadow separator progress-indicator="submit">
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                            <x-datetime :label="trans('validation.attributes.starts_at')" wire:model.live="form.starts_at" type="datetime-local" />
                            <x-datetime :label="trans('validation.attributes.ends_at')" wire:model.live="form.ends_at" type="datetime-local" />
                            <x-select class="lg:col-span-2" :label="trans('validation.attributes.status')" wire:model.live="form.status"
                                :options="$statuses" option-label="label" option-value="value" />
                        </div>
                    </x-card>
                </div>

                <div class="space-y-4">
                    <x-card :title="trans('exam.form.delivery_settings')" shadow separator progress-indicator="submit">
                        <div class="space-y-4">
                            <x-select :label="trans('validation.attributes.show_results')" wire:model.live="form.show_results" :options="$showResults"
                                option-label="label" option-value="value" />
                            <x-toggle :label="trans('validation.attributes.shuffle_questions')" wire:model.live="form.shuffle_questions" right />
                            <x-toggle :label="trans('validation.attributes.allow_review')" wire:model.live="form.allow_review" right />
                            <x-tags :label="trans('validation.attributes.tags')" wire:model.live="tags" clearable />
                        </div>
                    </x-card>

                    <x-card :title="trans('exam.form.question_overview')" shadow separator>
                        @if (($questionStats['total'] ?? 0) > 0)
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <p class="text-base-content/70">{{ trans('exam.questions_count') }}</p>
                                        <p class="font-semibold text-base-content">{{ $questionStats['total'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-base-content/70">{{ trans('exam.manual_review_count') }}</p>
                                        <p class="font-semibold text-base-content">{{ $questionStats['manual'] }}</p>
                                    </div>
                                    @if ($form['type'] === ExamTypeEnum::SCORED->value)
                                        <div class="col-span-2 text-sm">
                                            <p class="text-base-content/70">{{ trans('exam.total_weight') }}</p>
                                            <p class="font-semibold text-base-content">
                                                {{ $questionStats['weight'] }}
                                                / {{ $form['total_score'] ?? '—' }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-1">
                                    @foreach ($questionStats['breakdown'] as $row)
                                        <div class="flex justify-between items-center text-xs text-base-content/80">
                                            <span>{{ $row['label'] }}</span>
                                            <span class="font-semibold text-base-content">{{ $row['count'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <x-admin.shared.empty-view icon="o-clipboard-document-check" :title="trans('exam.form.no_questions_title')"
                                :description="trans('exam.form.no_questions_desc')" />
                        @endif
                    </x-card>
                </div>
            </div>
        </x-tab>

        <x-tab name="questions" :label="trans('exam.form.questions_tab')" icon="o-clipboard-document-check">
            <x-card shadow separator>
                @if ($exam->exists)
                    <livewire:admin.pages.exam.question-selector :exam="$exam" :total-score="$form['total_score'] ?? $exam->total_score"
                        :key="'exam-question-selector-' .
                            $exam->id .
                            '-' .
                            ($form['total_score'] ?? ($exam->total_score ?? 0))" />
                @else
                    <x-admin.shared.empty-view icon="o-clipboard-document-check" :title="trans('exam.form.questions_need_save_title')"
                        :description="trans('exam.form.questions_need_save_desc')" />
                @endif
            </x-card>
        </x-tab>

        <x-tab name="rules" :label="trans('exam.form.rules_tab')" icon="o-shield-check">
            <x-card shadow separator>
                <livewire:admin.pages.exam.exam-rule-builder :rules="$ruleBuilderState" />
            </x-card>
        </x-tab>
    </x-tabs>

    <x-admin.shared.form-actions />
</form>
