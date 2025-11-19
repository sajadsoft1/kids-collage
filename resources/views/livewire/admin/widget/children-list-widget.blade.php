<x-card title="{{ __('widgets.children_list.title') }}"
    subtitle="{{ __('widgets.children_list.subtitle') }}" shadow separator progress-indicator="update">
    <x-slot:menu>
        <x-button icon="lucide.external-link" class="btn-circle btn-sm" link="{{ $this->getMoreItemsUrl() }}" />
    </x-slot:menu>

    @if ($this->childrenList->count() > 0)
        <div class="space-y-3">
            @foreach ($this->childrenList as $index => $child)
                <x-list-item :item="$child" no-separator wire:key="children-list-widget-{{ $child->id }}-{{ $index }}">
                    <x-slot:avatar>
                        <img src="{{ $child->getFirstMediaUrl('avatar', '50_square') }}" alt="{{ $child->full_name }}"
                            class="w-10 h-10 rounded-full object-cover" />
                    </x-slot:avatar>
                    <x-slot:value>
                        <div class="font-medium">{{ $child->full_name }}</div>
                        <div class="text-sm text-base-content/60">
                            {{ $child->email ?? $child->mobile }}
                        </div>
                    </x-slot:value>
                    <x-slot:sub-value>
                        <div class="flex items-center gap-2">
                            @php
                                $activeEnrollments = $child->enrollments()->where('status', 'active')->count();
                            @endphp
                            <x-badge :value="$activeEnrollments . ' ' . __('widgets.children_list.active_courses')"
                                class="badge-sm badge-info" />
                        </div>
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <x-button icon="o-eye" class="btn-sm" link="{{ route('admin.user.edit', $child->id) }}" />
                    </x-slot:actions>
                </x-list-item>
            @endforeach
        </div>
    @else
        <x-admin.shared.empty-view title="{{ __('widgets.children_list.empty_title') }}"
            description="{{ __('widgets.children_list.empty_description') }}" icon="o-user-group"
            btn_label="{{ __('widgets.children_list.view_all') }}" btn_link="{{ $this->getMoreItemsUrl() }}" />
    @endif
</x-card>

