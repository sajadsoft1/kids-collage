{{-- Global learning drawer (Complex: header, separator, footer). Opened via event; use withLearningModalActions() or wire:click="openLearningModal". --}}

<x-drawer
    wire:model="showLearningModal"
    right
    :title="trans('general.learning')"
    separator
    with-close-button
    close-on-escape
    class="w-11/12 sm:max-w-xl md:max-w-2xl lg:max-w-4xl"
>
    @if (count($sections) === 0)
        <p class="text-sm text-base-content/60">{{ trans('general.help') }}</p>
    @elseif (count($sections) === 1)
        <div class="max-w-none prose prose-sm text-base-content min-h-[50vh] max-h-[65vh] overflow-y-auto">
            {!! array_values($sections)[0]['content'] ?? '' !!}
        </div>
    @else
        <x-tabs wire:model="learningModalTab">
            @foreach ($sections as $key => $section)
                <x-tab :name="(string) $key" :label="$section['title'] ?? (string) $key" :icon="$section['icon'] ?? null">
                    <div class="py-2 max-w-none prose prose-sm text-base-content min-h-[50vh] max-h-[65vh] overflow-y-auto">
                        {!! $section['content'] ?? '' !!}
                    </div>
                </x-tab>
            @endforeach
        </x-tabs>
    @endif

    <x-slot:actions>
        <x-button :label="trans('general.close')" wire:click="closeLearningModal" class="btn-ghost" />
    </x-slot:actions>
</x-drawer>
