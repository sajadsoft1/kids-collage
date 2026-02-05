{{-- Global learning modal – rendered in layout; opened via event. No trigger here; use withLearningModalActions() or wire:click="openLearningModal". --}}

<x-modal wire:model="showLearningModal" persistent class="backdrop-blur" max-width="2xl">
    @if (count($sections) === 0)
        <p class="text-sm text-base-content/60">{{ trans('general.help') }}</p>
    @elseif (count($sections) === 1)
        <div class="max-w-none prose prose-sm text-base-content">
            {!! array_values($sections)[0]['content'] ?? '' !!}
        </div>
    @else
        <x-tabs wire:model="learningModalTab">
            @foreach ($sections as $key => $section)
                <x-tab :name="(string) $key" :label="$section['title'] ?? (string) $key" :icon="$section['icon'] ?? null">
                    <div class="py-2 max-w-none prose prose-sm text-base-content">
                        {!! $section['content'] ?? '' !!}
                    </div>
                </x-tab>
            @endforeach
        </x-tabs>
    @endif

    <x-slot:actions>
        <x-button :label="trans('general.close')" wire:click="closeLearningModal" class="btn-ghost" />
    </x-slot:actions>
</x-modal>
