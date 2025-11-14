<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">

        {{-- Header --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-900">{{ $exam->title }}</h1>

                {{-- Timer --}}
                @if ($timeRemaining !== null)
                    <div x-data="timer({{ $timeRemaining }})" x-init="startTimer()"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg"
                        :class="remainingSeconds < 300 ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'">
                        <x-heroicon-o-clock class="w-5 h-5" />
                        <span class="font-mono font-semibold" x-text="formatTime()"></span>
                    </div>
                @endif
            </div>

            {{-- Progress Bar --}}
            <div class="mb-4">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>پیشرفت</span>
                    <span>{{ $progress['answered_questions'] }} از {{ $totalQuestions }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all"
                        style="width: {{ $progress['progress_percentage'] }}%"></div>
                </div>
            </div>

            {{-- Question Navigation --}}
            <div class="flex flex-wrap gap-2">
                @foreach ($questions as $index => $q)
                    <button wire:click="goToQuestion({{ $index }})"
                        class="w-10 h-10 rounded-lg font-semibold text-sm transition-all
                                   {{ $index === $currentQuestionIndex ? 'bg-blue-600 text-white ring-2 ring-blue-300' : '' }}
                                   {{ isset($answers[$q->id]) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Question Card --}}
        @if ($currentQuestion)
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <div class="mb-4">
                    <span class="text-sm text-gray-500">
                        سوال {{ $currentQuestionIndex + 1 }} از {{ $totalQuestions }}
                    </span>

                    @if ($currentQuestion->pivot->is_required)
                        <span class="text-xs text-red-600 mr-2">(اجباری)</span>
                    @endif

                    @if ($exam->type === \App\Enums\ExamTypeEnum::SCORED)
                        <span class="float-left text-sm text-gray-600">
                            نمره: {{ $currentQuestion->pivot->weight }}
                        </span>
                    @endif
                </div>

                {{-- Render Question Component --}}
                @php
                    $componentName = 'question-display.' . str_replace('_', '-', $currentQuestion->type->value);
                @endphp

                <livewire:is :component="$componentName" :question="$currentQuestion" :value="$answers[$currentQuestion->id] ?? null" :disabled="false" :showCorrect="false"
                    :key="'question-' . $currentQuestion->id" />
            </div>
        @endif

        {{-- Navigation Buttons --}}
        <div class="flex justify-between items-center">
            <button wire:click="previousQuestion" {{ $currentQuestionIndex === 0 ? 'disabled' : '' }}
                class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                <x-heroicon-o-arrow-right class="w-5 h-5 inline ml-1" />
                قبلی
            </button>

            @if ($currentQuestionIndex === count($questions) - 1)
                <button wire:click="submitExam" wire:confirm="آیا می‌خواهید آزمون را ثبت کنید؟"
                    class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                    ثبت نهایی آزمون
                    <x-heroicon-o-check class="w-5 h-5 inline mr-1" />
                </button>
            @else
                <button wire:click="nextQuestion" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    بعدی
                    <x-heroicon-o-arrow-left class="w-5 h-5 inline mr-1" />
                </button>
            @endif
        </div>
    </div>
</div>

<script>
    function timer(initialSeconds) {
        return {
            remainingSeconds: initialSeconds,
            interval: null,

            startTimer() {
                this.interval = setInterval(() => {
                    this.remainingSeconds--;

                    if (this.remainingSeconds <= 0) {
                        clearInterval(this.interval);
                        @this.call('handleTimeExpired');
                    }
                }, 1000);
            },

            formatTime() {
                const hours = Math.floor(this.remainingSeconds / 3600);
                const minutes = Math.floor((this.remainingSeconds % 3600) / 60);
                const seconds = this.remainingSeconds % 60;

                if (hours > 0) {
                    return `${hours}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }
                return `${minutes}:${String(seconds).padStart(2, '0')}`;
            }
        }
    }
</script>
