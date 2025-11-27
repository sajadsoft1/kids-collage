{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- EXPLANATION PANEL --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<div class="w-[45%] flex flex-col bg-base-100">
    {{-- Tab Header --}}
    <div class="border-b border-base-300">
        <button class="px-6 py-3 text-sm font-medium text-base-content border-b-2 border-primary bg-base-200">
            <x-icon name="o-light-bulb" class="w-4 h-4 inline me-1" />
            {{ __('exam.explanation') }}
        </button>
    </div>

    {{-- Explanation Content --}}
    <div class="flex-1 p-6 overflow-y-auto text-sm leading-relaxed">
        @if ($currentQuestion?->explanation)
            <div class="prose prose-sm max-w-none">
                {!! $currentQuestion->explanation !!}
            </div>
        @else
            <div class="text-base-content/40 italic flex items-center gap-2">
                <x-icon name="o-information-circle" class="w-5 h-5" />
                {{ __('exam.no_explanation') }}
            </div>
        @endif
    </div>
</div>

