@php
    use App\Enums\BooleanEnum;
    use App\Enums\QuestionTypeEnum;
@endphp
<form wire:submit="submit">
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <div class="grid grid-cols-1 col-span-2 gap-4">
            <x-card :title="trans('question.page.content')" shadow separator progress-indicator="submit">
                <div class="grid grid-cols-1 gap-4">
                    <x-input :label="trans('question.page.title')" wire:model.live="title" required />
                    <x-textarea :label="trans('question.page.body')" wire:model.live="body" />
                    <x-textarea :label="trans('question.page.explanation')" wire:model.live="explanation" />
                </div>
            </x-card>

            <x-card :title="trans('question.page.options')" shadow separator progress-indicator="submit">
                <div wire:loading.flex wire:target="type" class="justify-center py-8">
                    <x-loading class="loading-infinity loading-lg" />
                </div>
                <div wire:loading.remove wire:target="type">
                    @if ($type)
                        @switch($type)
                            @case(QuestionTypeEnum::SINGLE_CHOICE->value)
                                <livewire:admin.pages.question-builder.single-choice :options="$options" :config="$config"
                                    :key="'single-choice-' . ($model->id ?? 'new')" />
                            @break

                            @case(QuestionTypeEnum::MULTIPLE_CHOICE->value)
                                <livewire:admin.pages.question-builder.multiple-choice :options="$options" :config="$config"
                                    :key="'multiple-choice-' . ($model->id ?? 'new')" />
                            @break

                            @case(QuestionTypeEnum::TRUE_FALSE->value)
                                <livewire:admin.pages.question-builder.true-false :config="$config" :correct_answer="$correct_answer"
                                    :key="'true-false-' . ($model->id ?? 'new')" />
                            @break

                            @case(QuestionTypeEnum::SHORT_ANSWER->value)
                                <livewire:admin.pages.question-builder.short-answer :config="$config" :correct_answer="$correct_answer"
                                    :key="'short-answer-' . ($model->id ?? 'new')" />
                            @break

                            @case(QuestionTypeEnum::ESSAY->value)
                                <livewire:admin.pages.question-builder.essay :config="$config" :correct_answer="$correct_answer"
                                    :key="'essay-' . ($model->id ?? 'new')" />
                            @break

                            @case(QuestionTypeEnum::ORDERING->value)
                                <livewire:admin.pages.question-builder.ordering :config="$config" :correct_answer="$correct_answer"
                                    :key="'ordering-' . ($model->id ?? 'new')" />
                            @break

                            @case(QuestionTypeEnum::TEXT_HIGHLIGHT->value)
                                <livewire:admin.pages.question-builder.text-highlight :config="$config" :correct_answer="$correct_answer"
                                    :key="'text-highlight-' . ($model->id ?? 'new')" />
                            @break

                            @case(QuestionTypeEnum::SINGLE_CHOICE_IMAGE->value)
                            @case(QuestionTypeEnum::MULTIPLE_CHOICE_IMAGE->value)

                            @case(QuestionTypeEnum::MATCHING->value)
                            @case(QuestionTypeEnum::DRAG_AND_DROP->value)

                            @case(QuestionTypeEnum::TEXT_SELECT->value)
                            @case(QuestionTypeEnum::WORD_CHOICE->value)

                            @case(QuestionTypeEnum::BOW_TIE->value)
                            @case(QuestionTypeEnum::MATRIX->value)

                            @case(QuestionTypeEnum::HOT_SPOT->value)
                                @include('admin.datatable-shared.empty-table', [
                                    'icon' => 'o-x-circle',
                                    'title' => trans('question.page.content_not_available'),
                                    'description' => trans('question.page.content_not_available_description'),
                                ])
                            @break

                            @default
                                @include('admin.datatable-shared.empty-table', [
                                    'icon' => 'o-x-circle',
                                    'title' => trans('question.page.content_not_available'),
                                    'description' => trans('question.page.content_not_available_description'),
                                ])
                        @endswitch
                    @else
                        @include('admin.datatable-shared.empty-table', [
                            'icon' => 'o-question-mark-circle',
                            'title' => trans('question.page.please_select_type'),
                            'description' => trans('question.page.please_select_type_description'),
                        ])
                    @endif
                </div>
            </x-card>
        </div>

        <div class="col-span-1">
            <div class="sticky top-16">
                <x-card :title="trans('question.page.basic_info')" shadow separator progress-indicator="submit">
                    <div class="grid grid-cols-1 gap-4">
                        <x-select :label="trans('validation.attributes.type')" wire:model.live="type" :options="$types" option-label="label"
                            option-value="value" :placeholder="trans('validation.attributes.type')" placeholder-value="" required />
                        <x-select :label="trans('validation.attributes.category_id')" wire:model.live="category_id" :options="$categories"
                            option-label="label" option-value="value" required>
                            <x-slot:append>
                                <x-button class="join-item btn-primary" icon="o-plus" :link="route('admin.category.create')" external
                                    :tooltip-bottom="trans('general.page.create.title', ['model' => trans('category.model')])" />
                            </x-slot:append>
                        </x-select>
                        <x-select :label="trans('validation.attributes.subject_id')" wire:model.live="subject_id" :options="$subjects"
                            option-label="label" option-value="value" required>
                            <x-slot:append>
                                <x-button class="join-item btn-primary" icon="o-plus" :link="route('admin.question-subject.index')" external
                                    :tooltip-bottom="trans('general.page.create.title', ['model' => trans('subject.model')])" />
                            </x-slot:append>
                        </x-select>
                        <x-select :label="trans('validation.attributes.competency_id')" wire:model.live="competency_id" :options="$competencies"
                            option-label="label" option-value="value" required>
                            <x-slot:append>
                                <x-button class="join-item btn-primary" icon="o-plus" :link="route('admin.question-competency.index')" external
                                    :tooltip-bottom="trans('general.page.create.title', [
                                        'model' => trans('competency.model'),
                                    ])" />
                            </x-slot:append>
                        </x-select>
                        <x-select :label="trans('validation.attributes.difficulty')" wire:model.live="difficulty" :options="$difficulties"
                            option-label="label" option-value="value" required />
                        <x-input :label="trans('validation.attributes.default_score')" wire:model.live="default_score" required />
                    </div>
                    <x-tags :label="trans('validation.attributes.tags')" wire:model.live="tags" icon="o-tag" clearable />
                </x-card>
            </div>
        </div>
    </div>





    <x-admin.shared.form-actions />
</form>
