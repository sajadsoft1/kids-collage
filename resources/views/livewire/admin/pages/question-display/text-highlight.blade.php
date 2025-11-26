<div class="space-y-4"
     x-data="{
        selecting: false,
        start: 0,
        end: 0,
        minSelections: @js($this->getMinSelections()),
        maxSelections: @js($this->getMaxSelections()),
        get currentCount() {
            return $wire.value.selections.length;
        },
        get canSelectMore() {
            if (this.maxSelections === null) return true;
            return this.currentCount < this.maxSelections;
        },
        addSelection() {
            if (this.end > this.start && this.canSelectMore) {
                $wire.addSelection(this.start, this.end);
            }
        },
        clear() { 
            $wire.clearSelections();
        },
        onMouseUp(e) {
            const sel = window.getSelection();
            if (!sel || sel.rangeCount === 0) return;
            const range = sel.getRangeAt(0);
            const pre = document.getElementById('th-body');
            if (!pre || !pre.contains(range.commonAncestorContainer)) return;
            const text = pre.innerText;
            const selectedText = sel.toString();
            if (!selectedText) return;
            const full = text;
            const start = full.indexOf(selectedText);
            const end = start + selectedText.length;
            this.start = start; this.end = end;
            this.addSelection();
            sel.removeAllRanges();
        }
     }"
     x-on:mouseup="onMouseUp($event)">

    @if ($question->title)
        <div class="text-lg font-medium text-gray-900">{!! nl2br(e($question->title)) !!}</div>
    @endif

    @if ($question->body)
        <div id="th-body" class="p-4 max-w-none text-gray-700 bg-gray-50 rounded-lg whitespace-pre-wrap cursor-text select-text">{!! e($question->body) !!}</div>
    @endif

    {{-- Selection Info --}}
    <div class="flex items-center justify-between gap-2 text-sm">
        <div class="flex items-center gap-2">
            <x-badge :value="count($value['selections']) . ' انتخاب'" 
                :class="count($value['selections']) >= $this->getMinSelections() ? 'badge-success' : 'badge-warning'" />
            @if($this->getMaxSelections() !== null)
                <span class="text-gray-600">/ {{ $this->getMaxSelections() }} حداکثر</span>
            @endif
        </div>
        <x-button size="sm" class="btn-ghost" wire:click="clearSelections">حذف انتخاب‌ها</x-button>
    </div>

    {{-- Selection Requirements --}}
    <div class="space-y-1">
        @if(count($value['selections']) < $this->getMinSelections())
            <x-alert icon="o-information-circle" class="alert-warning alert-soft">
                حداقل {{ $this->getMinSelections() }} انتخاب الزامی است.
            </x-alert>
        @endif
        @if($this->getMaxSelections() !== null && count($value['selections']) >= $this->getMaxSelections())
            <x-alert icon="o-information-circle" class="alert-info alert-soft">
                حداکثر {{ $this->getMaxSelections() }} انتخاب مجاز است.
            </x-alert>
        @endif
    </div>

    {{-- Selected Items List --}}
    @if(!empty($value['selections']))
        <div class="space-y-2">
            <div class="text-sm font-medium text-gray-700">بخش‌های انتخاب شده:</div>
            <div class="space-y-1">
                @foreach($value['selections'] as $index => $selection)
                    @php
                        $selectedText = mb_substr($question->body, $selection['start'], $selection['end'] - $selection['start']);
                    @endphp
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded" wire:key="sel-display-{{ $index }}">
                        <span class="text-sm text-gray-700">{{ $selectedText }}</span>
                        <x-button size="xs" icon="o-x-mark" wire:click="removeSelection({{ $index }})" class="btn-ghost btn-sm" />
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($showCorrect && !empty($question->correct_answer['selections'] ?? []))
        <div class="p-3 bg-green-50 rounded text-sm text-green-800">
            <strong>پاسخ صحیح:</strong> {{ json_encode($question->correct_answer['selections']) }}
        </div>
    @endif
</div>


