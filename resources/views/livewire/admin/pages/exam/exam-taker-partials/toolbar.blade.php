{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
{{-- SECONDARY TOOLBAR --}}
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}
<div class="bg-primary/80 text-primary-content px-4 py-2 flex items-center justify-between">
    {{-- Tool Buttons --}}
    <div class="flex items-center gap-1">
        <x-button icon="o-pencil-square" class="btn-ghost btn-sm text-primary-content hover:bg-white/20" responsive>
            {{ __('exam.notes') }}
        </x-button>
        <x-button icon="o-calculator" class="btn-ghost btn-sm text-primary-content hover:bg-white/20" responsive>
            {{ __('exam.calculator') }}
        </x-button>
        <x-button icon="o-chat-bubble-left" class="btn-ghost btn-sm text-primary-content hover:bg-white/20" responsive>
            {{ __('exam.feedback') }}
        </x-button>
    </div>

    {{-- Right: Icons & Mark for Review --}}
    <div class="flex items-center gap-2">
        <x-button icon="o-arrows-pointing-out"
            class="btn-ghost btn-sm btn-circle text-primary-content hover:bg-white/20" />
        <x-button icon="o-question-mark-circle"
            class="btn-ghost btn-sm btn-circle text-primary-content hover:bg-white/20" />
        <x-button icon="o-cog-6-tooth" class="btn-ghost btn-sm btn-circle text-primary-content hover:bg-white/20" />
        <x-button icon="o-flag" class="btn-ghost btn-sm text-primary-content hover:bg-white/20">
            {{ __('exam.mark_for_review') }}
        </x-button>
    </div>
</div>

