<div>
    <div class="mb-6">
        <h2 class="mb-2 text-2xl font-bold text-gray-900">
            مدیریت سوالات: {{ $exam->title }}
        </h2>
        <p class="text-gray-600">
            {{ $exam->questions->count() }} سوال انتخاب شده
            @if ($exam->type === \App\Enums\ExamTypeEnum::SCORED)
                | مجموع نمره: {{ $exam->questions->sum('pivot.weight') }} از {{ $exam->total_score ?? 0 }}
            @endif
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Selected Questions (Right Side) --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">سوالات انتخاب شده</h3>

                @if ($exam->type === \App\Enums\ExamTypeEnum::SCORED && !$exam->validateTotalWeight())
                    <span class="flex items-center text-sm text-red-600">
                        <x-heroicon-o-exclamation-triangle class="ml-1 w-4 h-4" />
                        مجموع وزن‌ها برابر نیست!
                    </span>
                @endif
            </div>

            @if ($currentQuestions->isEmpty())
                <div class="py-12 text-center text-gray-500">
                    <x-heroicon-o-question-mark-circle class="mx-auto mb-3 w-12 h-12 text-gray-400" />
                    <p>هنوز سوالی انتخاب نشده</p>
                </div>
            @else
                <div class="space-y-3" x-data="{ reordering: false }">
                    @foreach ($currentQuestions->sortBy('pivot.order') as $question)
                        <div wire:key="selected-{{ $question->id }}"
                            class="p-4 bg-gray-50 rounded-lg border-2 border-gray-200">

                            <div class="flex gap-3 items-start">
                                {{-- Order Handle --}}
                                <div class="pt-1">
                                    <x-heroicon-o-bars-3 class="w-5 h-5 text-gray-400 cursor-move" />
                                </div>

                                {{-- Question Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate">
                                        {{ $question->title }}
                                    </div>
                                    <div class="flex gap-2 items-center mt-1 text-xs text-gray-500">
                                        <span
                                            class="px-2 py-0.5 rounded bg-{{ $question->type->color() }}-100 text-{{ $question->type->color() }}-800">
                                            {{ $question->type->title() }}
                                        </span>
                                        @if ($question->difficulty)
                                            <span class="px-2 py-0.5 rounded {{ $question->difficulty->bgColor() }}">
                                                {{ $question->difficulty->title() }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Weight Input --}}
                                    @if ($exam->type === \App\Enums\ExamTypeEnum::SCORED)
                                        <div class="mt-2">
                                            <label class="text-xs text-gray-600">وزن:</label>
                                            <input type="number" step="0.25"
                                                wire:model.blur="weights.{{ $question->id }}"
                                                wire:change="updateWeight({{ $question->id }}, $event.target.value)"
                                                class="px-2 py-1 w-20 text-sm rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    @endif
                                </div>

                                {{-- Remove Button --}}
                                <button wire:click="toggleQuestion({{ $question->id }})"
                                    class="p-2 text-red-600 rounded hover:bg-red-50">
                                    <x-heroicon-o-x-mark class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Available Questions (Left Side) --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <h3 class="mb-4 text-lg font-semibold">بانک سوالات</h3>

            {{-- Search & Filters --}}
            <div class="mb-4 space-y-3">
                <x-input wire:model.live.debounce.300ms="search" placeholder="جستجو..." />

                <x-select wire:model.live="typeFilter">
                    <option value="">همه انواع</option>
                    @foreach (\App\Enums\QuestionTypeEnum::cases() as $type)
                        <option value="{{ $type->value }}">{{ $type->title() }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Questions List --}}
            <div class="overflow-y-auto space-y-2 max-h-96">
                @forelse($questions as $question)
                    <div wire:key="available-{{ $question->id }}"
                        class="p-3 border rounded-lg hover:bg-gray-50 transition-colors
                                {{ in_array($question->id, $selectedQuestions) ? 'bg-blue-50 border-blue-300' : 'border-gray-200' }}">

                        <div class="flex gap-3 items-start">
                            {{-- Checkbox --}}
                            <div class="pt-1">
                                <x-checkbox wire:click="toggleQuestion({{ $question->id }})" :checked="in_array($question->id, $selectedQuestions)" />
                            </div>

                            {{-- Question Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 line-clamp-2">
                                    {{ $question->title }}
                                </div>
                                <div class="flex gap-2 items-center mt-1 text-xs text-gray-500">
                                    <span
                                        class="px-2 py-0.5 rounded bg-{{ $question->type->color() }}-100 text-{{ $question->type->color() }}-800">
                                        {{ $question->type->title() }}
                                    </span>
                                    <span>نمره: {{ $question->default_score }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-500">
                        سوالی یافت نشد
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($questions->hasPages())
                <div class="mt-4">
                    {{ $questions->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex justify-between mt-6">
        <a href="{{ route('exams.index') }}"
            class="px-4 py-2 text-gray-700 bg-white rounded-lg border border-gray-300 hover:bg-gray-50">
            بازگشت
        </a>

        <div class="flex gap-3">
            @if ($exam->status === \App\Enums\ExamStatusEnum::DRAFT)
                <button wire:click="$dispatch('publish-exam')" {{ $exam->questions->isEmpty() ? 'disabled' : '' }}
                    class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    انتشار آزمون
                </button>
            @endif
        </div>
    </div>
</div>
