<x-card title="{{ __('widgets.popular_blogs.title') }}"
    subtitle="{{ __('widgets.popular_blogs.subtitle', ['start_date' => $start_date, 'end_date' => $end_date]) }}" shadow
    separator>
    <x-slot:menu>
        <x-button icon="lucide.external-link" class="btn-circle btn-sm" link="{{ $this->getMoreItemsUrl() }}" />
    </x-slot:menu>

    @if ($this->popularBlogs->count() > 0)
        <div class="space-y-3">
            @foreach ($this->popularBlogs as $index => $blog)
                <x-list-item :item="$blog" no-separator
                    wire:key="popular-blogs-widget-{{ $blog->id }}-{{ $index }}">
                    <x-slot:avatar>
                        <img src="{{ $blog->getFirstMediaUrl('image', '50_square') }}" alt="{{ $blog->title }}"
                            class="w-10 h-10 rounded-lg object-cover" />
                    </x-slot:avatar>
                    <x-slot:value>
                        <div class="truncate max-w-32">
                            {{ $blog->title }}
                        </div>
                    </x-slot:value>
                    <x-slot:sub-value>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-base-content/60">{{ $blog->user?->full_name }}</span>
                            @if ($blog->category)
                                <span class="text-base-content/40">•</span>
                                <span class="text-base-content/60">{{ $blog->category->title }}</span>
                            @endif
                        </div>
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <div class="text-right">
                            <div class="flex items-center gap-2">
                                <div class="text-sm font-medium text-success">
                                    {{ number_format($blog->view_count) }} views
                                </div>
                            </div>
                            <div class="text-xs text-base-content/60">
                                {{ $blog->comment_count }} comments • {{ $blog->wish_count }} wishes
                            </div>
                        </div>
                    </x-slot:actions>
                </x-list-item>
            @endforeach
        </div>

        <x-slot:footer>
            <div class="flex items-center justify-between text-sm text-base-content/60">
                <span>Showing top {{ $this->popularBlogs->count() }} blogs</span>
                <span>{{ $start_date }} to {{ $end_date }}</span>
            </div>
        </x-slot:footer>
    @else
        <x-admin.shared.empty-view title="{{ __('widgets.popular_blogs.empty_title') }}"
            description="{{ __('widgets.popular_blogs.empty_description', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
            icon="o-document-text" btn_label="{{ __('widgets.popular_blogs.view_all') }}"
            btn_link="{{ $this->getMoreItemsUrl() }}" />
    @endif
</x-card>
