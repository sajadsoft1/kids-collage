<div class="space-y-4">
    {{-- Question Title --}}
    @if ($question->title)
        <div class="text-lg font-medium text-gray-900">
            {!! nl2br(e($question->title)) !!}
        </div>
    @endif

    {{-- Question Body --}}
    @if ($question->body)
        <div class="p-4 max-w-none text-gray-700 bg-gray-50 rounded-lg prose"></div>
        {!! nl2br(e($question->body)) !!}
</div>
@endif

{{-- Hint --}}
<div class="flex items-center text-sm text-gray-600">
    <x-heroicon-o-information-circle class="ml-1 w-4 h-4" />
    می‌توانید چند گزینه را انتخاب کنید
</div>

{{-- Options --}}
<div class="space-y-2">
    @foreach ($options as $option)
        <label wire:key="option-{{ $option->id }}"
            class="flex items-start p-4 rounded-lg border-2 cursor-pointer transition-all
                          {{ in_array($option->id, $selectedOptions) ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}
                          {{ $disabled ? 'cursor-not-allowed opacity-75' : '' }}
                          {{ $showCorrect && $option->is_correct ? 'border-green-500 bg-green-50' : '' }}
                          {{ $showCorrect && in_array($option->id, $selectedOptions) && !$option->is_correct ? 'border-red-500 bg-red-50' : '' }}">

            <x-checkbox wire:click="toggleOption({{ $option->id }})" value="{{ $option->id }}" :checked="in_array($option->id, $selectedOptions)"
                :disabled="$disabled" class="mt-1 w-5 h-5" />

            <div class="flex-1 mr-3">
                <div class="text-gray-900">
                    {{ $option->content }}
                </div>

                {{-- Show correct/incorrect indicator --}}
                @if ($showCorrect)
                    @if ($option->is_correct)
                        <div class="flex items-center mt-2 text-sm text-green-700">
                            <x-heroicon-o-check-circle class="ml-1 w-4 h-4" />
                            پاسخ صحیح
                        </div>
                    @elseif(in_array($option->id, $selectedOptions))
                        <div class="flex items-center mt-2 text-sm text-red-700">
                            <x-heroicon-o-x-circle class="ml-1 w-4 h-4" />
                            نباید انتخاب می‌شد
                        </div>
                    @endif
                @endif
            </div>
        </label>
    @endforeach
</div>

{{-- Explanation --}}
@if ($showCorrect && $question->explanation)
    <div class="p-4 mt-4 bg-yellow-50 rounded border-r-4 border-yellow-400">
        <div class="flex items-start">
            <x-heroicon-o-light-bulb class="mt-0.5 ml-2 w-5 h-5 text-yellow-600" />
            <div>
                <h4 class="mb-1 font-medium text-yellow-900">توضیحات</h4>
                <div class="text-sm text-yellow-800">
                    {!! nl2br(e($question->explanation)) !!}
                </div>
            </div>
        </div>
    </div>
@endif
</div>
