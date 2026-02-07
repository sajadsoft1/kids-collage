<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <x-card class="mb-6" shadow>
        <x-input wire:model.live.debounce.300ms="search" icon="o-magnifying-glass"
            placeholder="{{ __('ticket_chat.tag') }}..." class="max-w-xs" />
    </x-card>

    <x-card shadow>
        @if($tags->count() > 0)
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('ticket_chat.tag') }}</th>
                            <th>{{ __('validation.attributes.color') }}</th>
                            <th>{{ __('validation.attributes.description') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tags as $tag)
                            <tr wire:key="tag-{{ $tag->id }}">
                                <td>
                                    <span class="font-medium">{{ $tag->name }}</span>
                                    @if($tag->slug)
                                        <p class="mt-0.5 text-xs text-base-content/60">{{ $tag->slug }}</p>
                                    @endif
                                </td>
                                <td>
                                    @if($tag->color)
                                        <span class="badge badge-sm" style="background-color: {{ $tag->color }}; color: #fff;">{{ $tag->color }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($tag->description)
                                        {{ Str::limit($tag->description, 60) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.ticket-chat.tags.edit', ['ticket_tag' => $tag->id]) }}" wire:navigate
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
                {{ $tags->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <x-icon name="o-tag" class="size-16 text-base-content/30" />
                <h3 class="mt-4 text-lg font-medium">{{ __('ticket_chat.tags_empty') }}</h3>
                <p class="mt-2 max-w-sm text-sm text-base-content/60">{{ __('ticket_chat.no_tags_message') }}</p>
                <x-button icon="o-plus" class="btn-primary mt-6" link="{{ route('admin.ticket-chat.tags.create') }}" wire:navigate>
                    {{ __('general.page.create.title', ['model' => __('ticket_chat.tag')]) }}
                </x-button>
            </div>
        @endif
    </x-card>
</div>
