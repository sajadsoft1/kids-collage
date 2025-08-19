<x-card title="{{ __('widgets.new_users.title') }}"
    subtitle="{{ __('widgets.new_users.subtitle', ['start_date' => $start_date, 'end_date' => $end_date]) }}" shadow
    separator progress-indicator="update">
    <x-slot:menu>
        <x-button icon="lucide.external-link" class="btn-circle btn-sm" link="{{ $this->getMoreItemsUrl() }}" />
    </x-slot:menu>

    @if ($this->users->count() > 0)
        <div class="space-y-3">
            @foreach ($this->users as $index => $user)
                <x-list-item :item="$user" no-separator
                    wire:key="new-users-widget-{{ $user->id }}-{{ $index }}">
                    <x-slot:avatar>
                        <img src="{{ $user->getFirstMediaUrl('avatar', '50_square') }}" alt="{{ $user->full_name }}"
                            class="w-10 h-10 rounded-full object-cover" />
                    </x-slot:avatar>
                    <x-slot:value>
                        {{ $user->full_name }}
                    </x-slot:value>
                    <x-slot:sub-value>
                        {{ $user->email ?? $user->mobile }}
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <x-button icon="o-eye" class="btn-sm" link="{{ route('admin.user.edit', $user->id) }}" />
                    </x-slot:actions>
                </x-list-item>
            @endforeach

        </div>
    @else
        <x-admin.shared.empty-view title="{{ __('widgets.new_users.empty_title') }}"
            description="{{ __('widgets.new_users.empty_description', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
            icon="o-user" btn_label="{{ __('widgets.new_users.view_all') }}"
            btn_link="{{ $this->getMoreItemsUrl() }}" />
    @endif
</x-card>
