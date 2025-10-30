<div class="space-y-4">
    @if ($question->title)
        <div class="text-lg font-medium text-gray-900">{!! nl2br(e($question->title)) !!}</div>
    @endif

    @if ($question->body)
        <div class="p-4 max-w-none text-gray-700 bg-gray-50 rounded-lg prose">
            {!! nl2br(e($question->body)) !!}
        </div>
    @endif

    <div class="space-y-2">
        @foreach ($order as $idx => $optionId)
            @php $opt = $options->firstWhere('id', $optionId); @endphp
            <div wire:key="ord-{{ $optionId }}" class="flex items-center gap-2 p-3 rounded border-2 {{ $disabled ? 'opacity-75' : '' }}">
                <div class="flex items-center gap-1">
                    <x-button size="xs" icon="o-chevron-up" wire:click="moveUp({{ $idx }})" class="btn-ghost" :disabled="$disabled" />
                    <x-button size="xs" icon="o-chevron-down" wire:click="moveDown({{ $idx }})" class="btn-ghost" :disabled="$disabled" />
                </div>
                <div class="w-8 text-center text-xs">{{ $idx + 1 }}</div>
                <div class="flex-1">{{ $opt->content }}</div>
            </div>
        @endforeach
    </div>

    @if ($showCorrect)
        <div class="p-3 mt-2 bg-green-50 rounded text-sm text-green-800">
            ترتیب صحیح پس از اتمام نمایش داده می‌شود.
        </div>
    @endif
</div>

<div x-data="orderingQuestion()" x-init="init()">
    {{-- Question Title --}}
    @if ($question->title)
        <div class="text-lg font-medium text-gray-900 mb-4">
            {!! nl2br(e($question->title)) !!}
        </div>
    @endif

    {{-- Hint --}}
    <div class="mb-4 text-sm text-gray-600 flex items-center">
        <x-heroicon-o-information-circle class="w-4 h-4 ml-1" />
        آیتم‌ها را بکشید و در ترتیب صحیح قرار دهید
    </div>

    {{-- Options List (Sortable) --}}
    <div class="space-y-2" x-ref="container">
        @foreach ($orderedOptions as $index => $option)
            <div wire:key="option-{{ $option['id'] }}" x-data="{ id: {{ $option['id'] }} }"
                draggable="{{ $disabled ? 'false' : 'true' }}" @dragstart="dragStart($event, {{ $index }})"
                @dragover.prevent @drop="drop($event, {{ $index }})"
                class="flex items-center gap-3 p-4 bg-white border-2 rounded-lg transition-all
                        {{ $disabled ? 'cursor-not-allowed opacity-75' : 'cursor-move hover:border-blue-300 hover:shadow-md' }}
                        {{ $showCorrect && isset($option['is_correct_position']) && $option['is_correct_position'] ? 'border-green-500 bg-green-50' : 'border-gray-200' }}
                        {{ $showCorrect && isset($option['is_correct_position']) && !$option['is_correct_position'] ? 'border-red-500 bg-red-50' : '' }}">

                {{-- Drag Handle --}}
                @if (!$disabled)
                    <div>
                        <x-heroicon-o-bars-3 class="w-5 h-5 text-gray-400" />
                    </div>
                @endif

                {{-- Order Number --}}
                <div>
                    <span
                        class="inline-flex items-center justify-center w-8 h-8 rounded-full
                                 {{ $showCorrect && isset($option['is_correct_position']) && $option['is_correct_position'] ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}
                                 font-semibold text-sm">
                        {{ $index + 1 }}
                    </span>
                </div>

                {{-- Content --}}
                <div class="flex-1 text-gray-900">
                    {{ $option['content'] }}
                </div>

                {{-- Correct/Incorrect Indicator --}}
                @if ($showCorrect && isset($option['is_correct_position']))
                    @if ($option['is_correct_position'])
                        <x-heroicon-o-check-circle class="w-6 h-6 text-green-600" />
                    @else
                        <x-heroicon-o-x-circle class="w-6 h-6 text-red-600" />
                    @endif
                @endif
            </div>
        @endforeach
    </div>

    {{-- Explanation --}}
    @if ($showCorrect && $question->explanation)
        <div class="mt-4 p-4 bg-yellow-50 border-r-4 border-yellow-400 rounded">
            <div class="flex items-start">
                <x-heroicon-o-light-bulb class="w-5 h-5 text-yellow-600 ml-2 mt-0.5" />
                <div>
                    <h4 class="font-medium text-yellow-900 mb-1">توضیحات</h4>
                    <div class="text-sm text-yellow-800">
                        {!! nl2br(e($question->explanation)) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function orderingQuestion() {
        return {
            draggedIndex: null,

            init() {
                // Nothing needed for basic functionality
            },

            dragStart(event, index) {
                this.draggedIndex = index;
                event.dataTransfer.effectAllowed = 'move';
            },

            drop(event, dropIndex) {
                if (this.draggedIndex !== null && this.draggedIndex !== dropIndex) {
                    @this.call('reorder', [this.draggedIndex, dropIndex]);
                }
                this.draggedIndex = null;
            }
        }
    }
</script>
