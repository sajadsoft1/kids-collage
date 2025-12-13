<x-card title="اعلانات" shadow separator>
    {{-- Statistics Section --}}
    <div class="flex gap-4 mb-6">
        <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-base-300">
            <x-icon name="o-bell" class="w-5 h-5 text-primary" />
            <div>
                <div class="text-xs text-gray-500">کل اعلانات</div>
                <div class="font-bold">{{ $this->notificationStats['total'] }}</div>
            </div>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-error/10">
            <x-icon name="o-bell-alert" class="w-5 h-5 text-error" />
            <div>
                <div class="text-xs text-gray-500">خوانده نشده</div>
                <div class="font-bold text-error">{{ $this->notificationStats['unread'] }}</div>
            </div>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-success/10">
            <x-icon name="o-check-circle" class="w-5 h-5 text-success" />
            <div>
                <div class="text-xs text-gray-500">خوانده شده</div>
                <div class="font-bold text-success">{{ $this->notificationStats['read'] }}</div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="flex flex-wrap gap-2 mb-4">
        <x-button label="نمایش خوانده شده" icon="o-eye" class="btn-sm btn-outline" wire:click="toggleShowRead"
            :class="$show_read ? 'btn-active' : ''" />

        @if ($this->unreadCount > 0)
            <x-button label="علامت همه به عنوان خوانده شده" icon="o-check" class="btn-sm btn-success"
                wire:click="markAllAsRead" spinner />
        @endif

        @if ($this->notificationStats['read'] > 0)
            <x-button label="حذف خوانده شده‌ها" icon="o-trash" class="btn-sm btn-error" wire:click="deleteAllRead"
                wire:confirm="آیا مطمئن هستید؟" spinner />
        @endif
    </div>

    <div class="divider"></div>

    {{-- Notifications List --}}
    @if ($this->notifications->isEmpty())
        <div class="py-12 text-center">
            <x-icon name="o-bell-slash" class="w-16 h-16 mx-auto text-gray-400" />
            <p class="mt-2 text-gray-500">{{ $show_read ? 'هیچ اعلانی وجود ندارد' : 'اعلان خوانده نشده‌ای وجود ندارد' }}
            </p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($this->notifications as $notification)
                <div wire:key="notification-{{ $notification->id }}"
                    class="flex items-start gap-3 p-3 transition-all border rounded-lg hover:shadow-md {{ $notification->read_at ? 'bg-base-100 border-base-300' : 'bg-primary/5 border-primary/20' }}">
                    {{-- Icon --}}
                    <div class="flex-shrink-0 mt-1">
                        <x-icon :name="$this->getNotificationIcon($notification->type)"
                            class="w-6 h-6 {{ $this->getNotificationColor($notification->type) }}" />
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        {{-- Title --}}
                        <h4 class="font-semibold {{ $notification->read_at ? 'text-gray-700' : 'text-gray-900' }}">
                            {{ $notification->data['title'] ?? 'اعلان' }}
                        </h4>

                        {{-- Message --}}
                        <p class="mt-1 text-sm text-gray-600">
                            {{ $notification->data['message'] ?? ($notification->data['body'] ?? 'پیام اعلان') }}
                        </p>

                        {{-- Metadata --}}
                        <div class="flex flex-wrap items-center gap-2 mt-2 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <x-icon name="o-clock" class="w-3 h-3" />
                                {{ $notification->created_at->diffForHumans() }}
                            </span>

                            @if ($notification->read_at)
                                <span class="flex items-center gap-1 text-success">
                                    <x-icon name="o-check-circle" class="w-3 h-3" />
                                    خوانده شده
                                </span>
                            @endif
                        </div>

                        {{-- Link if available --}}
                        @if (isset($notification->data['link']))
                            <a href="{{ $notification->data['link'] }}"
                                class="inline-flex items-center gap-1 mt-2 text-sm text-primary hover:underline">
                                مشاهده جزئیات
                                <x-icon name="o-arrow-left" class="w-3 h-3" />
                            </a>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-1">
                        @if (!$notification->read_at)
                            <x-button icon="o-check" class="btn-xs btn-circle btn-ghost"
                                tooltip="علامت به عنوان خوانده شده" wire:click="markAsRead('{{ $notification->id }}')"
                                spinner />
                        @endif

                        <x-button icon="o-trash" class="btn-xs btn-circle btn-ghost text-error" tooltip="حذف"
                            wire:click="deleteNotification('{{ $notification->id }}')" wire:confirm="آیا مطمئن هستید؟"
                            spinner />
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Show More Button --}}
        @if ($this->notifications->count() >= $limit)
            <div class="mt-4 text-center">
                <x-button label="مشاهده همه اعلانات" icon-right="o-chevron-left" class="btn-sm btn-outline"
                    link="{{ route('admin.dashboard') }}" />
            </div>
        @endif
    @endif
</x-card>
