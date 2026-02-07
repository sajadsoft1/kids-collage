<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <x-card class="mb-6" shadow>
        <x-input wire:model.live.debounce.300ms="search" icon="o-magnifying-glass"
            placeholder="{{ __('ticket_chat.department') }}..." class="max-w-xs" />
    </x-card>

    <x-card shadow>
        @if($departments->count() > 0)
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('ticket_chat.department') }}</th>
                            <th>{{ __('ticket_chat.status') }}</th>
                            <th>{{ __('general.order') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departments as $department)
                            <tr wire:key="dept-{{ $department->id }}">
                                <td>
                                    <span class="font-medium">{{ $department->name }}</span>
                                    @if($department->description)
                                        <p class="mt-0.5 text-xs text-base-content/60">{{ Str::limit($department->description, 60) }}</p>
                                    @endif
                                </td>
                                <td>
                                    @if($department->is_active)
                                        <span class="badge badge-success badge-sm">{{ __('general.active') }}</span>
                                    @else
                                        <span class="badge badge-ghost badge-sm">{{ __('general.inactive') }}</span>
                                    @endif
                                </td>
                                <td>{{ $department->order }}</td>
                                <td>
                                    <a href="{{ route('admin.ticket-chat.departments.edit', ['ticket_department' => $department->id]) }}" wire:navigate
                                        class="btn btn-ghost btn-sm">
                                        {{ __('general.edit') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $departments->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <x-icon name="o-building-office-2" class="size-16 text-base-content/30" />
                <h3 class="mt-4 text-lg font-medium">{{ __('ticket_chat.no_departments') }}</h3>
                <p class="mt-2 max-w-sm text-sm text-base-content/60">{{ __('ticket_chat.no_departments_message') }}</p>
                <x-button icon="o-plus" class="btn-primary mt-6" link="{{ route('admin.ticket-chat.departments.create') }}" wire:navigate>
                    {{ __('general.page.create.title', ['model' => __('ticket_chat.department')]) }}
                </x-button>
            </div>
        @endif
    </x-card>
</div>
