<div class="space-y-4"
     x-data="{
        selecting: false,
        start: 0,
        end: 0,
        addSelection() {
            if (this.end > this.start) {
                $wire.addSelection(this.start, this.end)
            }
        },
        clear() { $wire.clearSelections() },
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
        <div id="th-body" class="p-4 max-w-none text-gray-700 bg-gray-50 rounded-lg whitespace-pre-wrap">{!! e($question->body) !!}</div>
    @endif

    <div class="flex items-center gap-2 text-sm">
        <x-badge :value="count($value['selections']) . ' انتخاب'" class="badge-primary badge-soft" />
        <x-button size="sm" class="btn-ghost" wire:click="clearSelections">حذف انتخاب‌ها</x-button>
    </div>

    @if ($showCorrect && !empty($question->correct_answer['selections'] ?? []))
        <div class="p-3 bg-green-50 rounded text-sm text-green-800">
            {{ json_encode($question->correct_answer['selections']) }}
        </div>
    @endif
</div>


