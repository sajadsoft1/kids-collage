<x-card title="{{ __('widgets.top_blog_writers.title') }}"
    subtitle="{{ __('widgets.top_blog_writers.subtitle', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
    shadow separator>
    <x-slot:menu>
        <x-button icon="lucide.external-link" class="btn-circle btn-sm" link="{{ $this->getMoreItemsUrl() }}" />
    </x-slot:menu>

    @if ($this->topWriters->count() > 0)
        <div class="space-y-3">
            @foreach ($this->topWriters as $index => $writer)
                <x-list-item :item="$writer" no-separator
                    wire:key="top-writers-widget-{{ $writer->id }}-{{ $index }}">
                    <x-slot:avatar>
                        <img src="{{ $writer->getFirstMediaUrl('avatar', '50_square') }}" alt="{{ $writer->full_name }}"
                            class="w-10 h-10 rounded-full object-cover" />
                    </x-slot:avatar>
                    <x-slot:value>
                        {{ $writer->full_name }}
                    </x-slot:value>
                    <x-slot:sub-value>
                        {{ $writer->email ?? $writer->mobile }}
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <div class="text-right">
                            <div class="flex items-center gap-2">
                                <div class="text-sm font-medium text-success">
                                    {{ $writer->total_blogs }} blogs
                                </div>
                            </div>
                            <div class="text-xs text-base-content/60">
                                {{ number_format($writer->total_views_sum ?? 0) }} views
                            </div>
                        </div>
                    </x-slot:actions>
                </x-list-item>
            @endforeach
        </div>
    @else
        <x-admin.shared.empty-view title="{{ __('widgets.top_blog_writers.empty_title') }}"
            description="{{ __('widgets.top_blog_writers.empty_description', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
            icon="o-document-text" btn_label="{{ __('widgets.top_blog_writers.view_all') }}"
            btn_link="{{ $this->getMoreItemsUrl() }}" />
    @endif
</x-card>
