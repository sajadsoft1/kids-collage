@php
    $passed = $attempt->isPassed();
    $percentage = $results['percentage'] ?? 0;
    $isScored = $attempt->exam->type === \App\Enums\ExamTypeEnum::SCORED;
@endphp

<div class="p-4 lg:p-6">
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- HEADER --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content">{{ __('exam_results.title') }}</h1>
            <p class="text-sm text-base-content/60">{{ $attempt->exam->title }}</p>
        </div>
        <div class="flex gap-2">
            <x-button :href="route('admin.exam.attempt.index', ['exam' => $exam->id])" icon="o-arrow-left" class="btn-ghost">
                {{ __('exam_attempt.back_to_list') }}
            </x-button>
            @if ($showAnswers)
                <x-button :href="route('admin.exam.attempt.review', ['exam' => $exam->id, 'attempt' => $attempt->id])"
                    icon="o-eye" class="btn-info">
                    {{ __('exam_results.review_exam') }}
                </x-button>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- TABS --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <x-tabs wire:model="selectedTab" active-class="bg-primary rounded-t-lg !text-white border-b-2 border-primary"
        label-class="px-6 py-3 font-semibold" label-div-class="border-b border-base-300 mb-6">

        {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
        {{-- TAB 1: OVERVIEW --}}
        {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
        <x-tab name="overview" :label="__('exam_results.overview_tab')" icon="o-chart-bar">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Points Scored Card --}}
                <x-card class="bg-base-100" shadow>
                    <x-slot:title>
                        <span class="text-success font-bold">{{ __('exam_results.points_scored') }}</span>
                    </x-slot:title>

                    <div class="space-y-4">
                        {{-- Percentage Label --}}
                        <div class="text-center">
                            <span
                                class="text-4xl font-bold {{ $passed ? 'text-success' : 'text-error' }}">{{ number_format($percentage, 0) }}%</span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full bg-base-200 rounded-full h-6 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $passed ? 'bg-success' : 'bg-error' }}"
                                style="width: {{ min($percentage, 100) }}%"></div>
                        </div>

                        {{-- Score Text --}}
                        <div class="text-center">
                            <span class="text-2xl font-bold text-base-content">
                                {{ $results['total_score'] }} / {{ $results['max_score'] }}
                            </span>
                            <div class="text-sm text-base-content/60">{{ __('exam_results.points_scored') }}</div>
                        </div>

                        @if ($attempt->exam->pass_score)
                            <div class="text-center text-sm text-base-content/60">
                                {{ __('exam_results.pass_score') }}: {{ $attempt->exam->pass_score }}
                            </div>
                        @endif
                    </div>
                </x-card>

                {{-- Question Performance Card --}}
                <x-card class="bg-base-100" shadow>
                    <x-slot:title>
                        <span class="font-bold text-base-content">{{ __('exam_results.question_performance') }}</span>
                    </x-slot:title>

                    <div class="space-y-4">
                        {{-- Correct --}}
                        <div class="flex items-center justify-between">
                            <span class="text-base-content/80">{{ __('exam_results.correct') }}</span>
                            <x-badge :value="$this->questionPerformance['correct']" class="badge-success badge-lg" />
                        </div>

                        {{-- Incorrect --}}
                        <div class="flex items-center justify-between">
                            <span class="text-base-content/80">{{ __('exam_results.incorrect') }}</span>
                            <x-badge :value="$this->questionPerformance['incorrect']" class="badge-error badge-lg" />
                        </div>

                        {{-- Omitted --}}
                        <div class="flex items-center justify-between">
                            <span class="text-base-content/80">{{ __('exam_results.omitted') }}</span>
                            <x-badge :value="$this->questionPerformance['omitted']" class="badge-warning badge-lg" />
                        </div>
                    </div>
                </x-card>

                {{-- Test Settings Card --}}
                <x-card class="bg-base-100" shadow>
                    <x-slot:title>
                        <span class="font-bold text-base-content">{{ __('exam_results.test_settings') }}</span>
                    </x-slot:title>

                    <div class="space-y-4">
                        {{-- Test ID --}}
                        <div class="flex items-center justify-between">
                            <span class="text-base-content/80">{{ __('exam_results.test_id') }}</span>
                            <x-badge :value="$attempt->id" class="badge-ghost badge-lg font-mono" />
                        </div>

                        {{-- Mode --}}
                        <div class="flex items-center justify-between">
                            <span class="text-base-content/80">{{ __('exam_results.mode') }}</span>
                            <div class="flex gap-1">
                                @if ($attempt->exam->show_answers_during)
                                    <x-badge value="{{ __('exam_results.tutored') }}" class="badge-info badge-lg" />
                                @endif
                                @if ($attempt->exam->duration)
                                    <x-badge value="{{ __('exam_results.timed') }}" class="badge-warning badge-lg" />
                                @else
                                    <x-badge value="{{ __('exam_results.untimed') }}" class="badge-ghost badge-lg" />
                                @endif
                            </div>
                        </div>

                        {{-- Exam Type --}}
                        <div class="flex items-center justify-between">
                            <span class="text-base-content/80">{{ __('exam_results.exam_type') }}</span>
                            <x-badge :value="$attempt->exam->type->title()" class="badge-primary badge-lg" />
                        </div>

                        {{-- Time Spent --}}
                        <div class="flex items-center justify-between">
                            <span class="text-base-content/80">{{ __('exam_results.time_spent') }}</span>
                            <x-badge :value="gmdate('H:i:s', $results['time_spent'])" class="badge-ghost badge-lg font-mono" />
                        </div>

                        {{-- Completed At --}}
                        <div class="flex items-center justify-between">
                            <span class="text-base-content/80">{{ __('exam_results.completed_at') }}</span>
                            <span class="text-sm text-base-content/60">
                                {{ $attempt->completed_at?->format('Y/m/d H:i') ?? '---' }}
                            </span>
                        </div>
                    </div>
                </x-card>
            </div>

            {{-- Result Status Banner --}}
            <div class="mt-6">
                @if ($isScored)
                    @if ($passed === true)
                        <x-alert icon="o-check-circle" class="alert-success">
                            <span class="font-bold">{{ __('exam_results.passed_message') }}</span>
                        </x-alert>
                    @elseif($passed === false)
                        <x-alert icon="o-x-circle" class="alert-error">
                            <span class="font-bold">{{ __('exam_results.failed_message') }}</span>
                        </x-alert>
                    @else
                        <x-alert icon="o-information-circle" class="alert-info">
                            <span class="font-bold">{{ __('exam_results.completed_message') }}</span>
                        </x-alert>
                    @endif
                @else
                    <x-alert icon="o-check-circle" class="alert-success">
                        <span class="font-bold">{{ __('exam_results.survey_completed') }}</span>
                    </x-alert>
                @endif
            </div>
        </x-tab>

        {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
        {{-- TAB 2: QUESTIONS LIST --}}
        {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
        <x-tab name="questions" :label="__('exam_results.questions_tab')" icon="o-clipboard-document-list">
            @if ($showAnswers && $this->answers->isNotEmpty())
                <x-card class="bg-base-100" shadow>
                    <x-table :headers="[
                        ['key' => 'question_id', 'label' => __('exam_results.question_id')],
                        ['key' => 'title', 'label' => __('exam_results.question_title')],
                        ['key' => 'type', 'label' => __('exam_results.question_type')],
                        ['key' => 'score', 'label' => __('exam_results.scored_max')],
                        ['key' => 'time_spent', 'label' => __('exam_results.time_spent')],
                        ['key' => 'actions', 'label' => ''],
                    ]" :rows="$this->answers
                        ->map(
                            fn($answer) => [
                                'id' => $answer->id,
                                'question_id' => $answer->question_id,
                                'title' => \Illuminate\Support\Str::limit($answer->question->title, 50),
                                'type' => $answer->question->type->title(),
                                'score' => $answer->score . ' / ' . $answer->max_score,
                                'time_spent' => $answer->time_spent ? gmdate('i:s', $answer->time_spent) : '0:00',
                                'is_correct' => $answer->is_correct,
                            ],
                        )
                        ->toArray()" striped>

                        @scope('cell_question_id', $row)
                            <span class="font-mono text-sm">{{ $row['question_id'] }}</span>
                        @endscope

                        @scope('cell_title', $row)
                            <span class="text-sm">{{ $row['title'] }}</span>
                        @endscope

                        @scope('cell_type', $row)
                            <x-badge :value="$row['type']" class="badge-ghost badge-sm" />
                        @endscope

                        @scope('cell_score', $row)
                            <span class="font-mono text-sm {{ $row['is_correct'] ? 'text-success' : 'text-error' }}">
                                {{ $row['score'] }}
                            </span>
                        @endscope

                        @scope('cell_time_spent', $row)
                            <span class="font-mono text-sm text-base-content/60">{{ $row['time_spent'] }}</span>
                        @endscope

                        @scope('cell_actions', $row)
                            <x-button wire:click="showQuestion({{ $row['id'] }})" icon="o-eye"
                                class="btn-ghost btn-circle btn-sm" spinner />
                        @endscope
                    </x-table>
                </x-card>
            @else
                <x-card class="bg-base-100" shadow>
                    <div class="text-center py-12">
                        <x-icon name="o-eye-slash" class="w-16 h-16 mx-auto text-base-content/30 mb-4" />
                        <p class="text-base-content/60">{{ __('exam_results.answers_not_available') }}</p>
                    </div>
                </x-card>
            @endif
        </x-tab>
    </x-tabs>

    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    {{-- QUESTION MODAL --}}
    {{-- ═══════════════════════════════════════════════════════════════════════════ --}}
    <x-modal wire:model="showQuestionModal" :title="__('exam_results.question_details')" class="backdrop-blur" box-class="max-w-4xl">
        @if ($this->selectedAnswer)
            @php
                $answer = $this->selectedAnswer;
                $question = $answer->question;
                $componentName = 'admin.pages.question-display.' . str_replace('_', '-', $question->type->value);
            @endphp

            {{-- Question Header --}}
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="text-sm text-base-content/60 mb-1">
                        {{ __('exam_results.question') }} #{{ $question->id }}
                    </div>
                    <x-badge :value="$question->type->title()" class="badge-ghost" />
                </div>
                <div class="text-{{ $answer->is_correct ? 'success' : 'error' }}">
                    <span class="text-2xl font-bold">{{ $answer->score }} / {{ $answer->max_score }}</span>
                </div>
            </div>

            {{-- Modal Tabs --}}
            <x-tabs wire:model="modalTab" active-class="bg-primary rounded-t !text-white"
                label-class="px-4 py-2 font-medium text-sm" label-div-class="border-b border-base-300 mb-4">

                {{-- Tab 1: Question & Answer --}}
                <x-tab name="question" :label="__('exam_results.question_answer_tab')" icon="o-document-text">
                    <div class="space-y-4">
                        {{-- User's Answer --}}
                        <div class="border border-base-300 rounded-lg p-4">
                            <livewire:is :component="$componentName" :question="$question" :value="$answer->answer_data" :disabled="true"
                                :showCorrect="true" :showTitle="false" :key="'modal-answer-' . $answer->id" />
                        </div>

                        {{-- Time Spent --}}
                        @if ($answer->time_spent)
                            <div class="text-sm text-base-content/60 text-center">
                                <x-icon name="o-clock" class="w-4 h-4 inline" />
                                {{ __('exam_results.time_spent') }}: {{ gmdate('i:s', $answer->time_spent) }}
                            </div>
                        @endif
                    </div>
                </x-tab>

                {{-- Tab 2: Explanation --}}
                <x-tab name="explanation" :label="__('exam_results.explanation_tab')" icon="o-light-bulb">
                    <div class="space-y-4">
                        @if ($question->explanation)
                            <div class="prose prose-sm max-w-none">
                                {!! $question->explanation !!}
                            </div>
                        @else
                            <div class="text-center py-8 text-base-content/50">
                                <x-icon name="o-document-text" class="w-12 h-12 mx-auto mb-2" />
                                <p>{{ __('exam_results.no_explanation') }}</p>
                            </div>
                        @endif
                    </div>
                </x-tab>
            </x-tabs>

            <x-slot:actions>
                <x-button wire:click="closeQuestionModal" class="btn-primary" icon="o-x-circle" spinner>
                    {{ __('general.close') }}
                </x-button>
            </x-slot:actions>
        @endif
    </x-modal>
</div>
