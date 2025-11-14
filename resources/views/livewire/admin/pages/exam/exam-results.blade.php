<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">

        {{-- Results Header --}}
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <div class="text-center">
                @if ($attempt->exam->type === \App\Enums\ExamTypeEnum::SCORED)
                    @php
                        $passed = $attempt->isPassed();
                    @endphp

                    <div class="mb-4">
                        @if ($passed === true)
                            <x-heroicon-o-check-circle class="w-20 h-20 mx-auto text-green-500" />
                        @elseif($passed === false)
                            <x-heroicon-o-x-circle class="w-20 h-20 mx-auto text-red-500" />
                        @else
                            <x-heroicon-o-clock class="w-20 h-20 mx-auto text-blue-500" />
                        @endif
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        @if ($passed === true)
                            تبریک! قبول شدید
                        @elseif($passed === false)
                            متاسفانه قبول نشدید
                        @else
                            آزمون تکمیل شد
                        @endif
                    </h1>

                    <div class="text-5xl font-bold mb-4" :class="$passed ? 'text-green-600' : 'text-red-600'">
                        {{ $results['total_score'] }} / {{ $results['max_score'] }}
                    </div>

                    <div class="text-xl text-gray-600">
                        درصد: {{ number_format($results['percentage'], 2) }}%
                    </div>

                    @if ($attempt->exam->pass_score)
                        <div class="mt-2 text-sm text-gray-500">
                            حد قبولی: {{ $attempt->exam->pass_score }}
                        </div>
                    @endif
                @else
                    <x-heroicon-o-check-circle class="w-20 h-20 mx-auto text-green-500 mb-4" />
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        پاسخ‌های شما ثبت شد
                    </h1>
                    <p class="text-gray-600">
                        از شرکت شما در این نظرسنجی متشکریم
                    </p>
                @endif
            </div>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-600">تعداد سوالات</div>
                <div class="text-2xl font-bold text-gray-900">{{ $results['total_questions'] }}</div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-600">پاسخ داده شده</div>
                <div class="text-2xl font-bold text-blue-600">{{ $results['answered_questions'] }}</div>
            </div>

            @if ($attempt->exam->type === \App\Enums\ExamTypeEnum::SCORED)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-600">پاسخ‌های صحیح</div>
                    <div class="text-2xl font-bold text-green-600">{{ $results['correct_answers'] }}</div>
                </div>

                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-600">پاسخ‌های اشتباه</div>
                    <div class="text-2xl font-bold text-red-600">{{ $results['incorrect_answers'] }}</div>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-600">زمان صرف شده</div>
                <div class="text-2xl font-bold text-gray-900">
                    {{ gmdate('i:s', $results['time_spent']) }}
                </div>
            </div>
        </div>

        {{-- Answers Review --}}
        @if ($showAnswers && $answers->isNotEmpty())
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">بررسی پاسخ‌ها</h2>

                <div class="space-y-6">
                    @foreach ($answers as $answer)
                        <div class="border-b border-gray-200 last:border-0 pb-6 last:pb-0">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="text-sm text-gray-500 mb-1">
                                        سوال {{ $loop->iteration }}
                                    </div>
                                    <div class="font-medium text-gray-900">
                                        {{ $answer->question->title }}
                                    </div>
                                </div>

                                @if ($attempt->exam->type === \App\Enums\ExamTypeEnum::SCORED)
                                    <div class="text-left">
                                        <div class="text-sm text-gray-600">نمره</div>
                                        <div
                                            class="text-lg font-bold {{ $answer->is_correct ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $answer->score }} / {{ $answer->max_score }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Render Question with Answer --}}
                            @php
                                $componentName =
                                    'question-display.' . str_replace('_', '-', $answer->question->type->value);
                            @endphp

                            <livewire:is :component="$componentName" :question="$answer->question" :value="$answer->answer_data" :disabled="true"
                                :showCorrect="true" :key="'answer-' . $answer->id" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="mt-6 flex justify-center gap-4">
            <a href="{{ route('exams.index') }}"
                class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                بازگشت به لیست آزمون‌ها
            </a>

            @if ($attempt->exam->allow_review && $showAnswers)
                <button onclick="window.print()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <x-heroicon-o-printer class="w-5 h-5 inline ml-1" />
                    چاپ نتایج
                </button>
            @endif
        </div>
    </div>
</div>
