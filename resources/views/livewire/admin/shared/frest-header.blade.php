@php use App\Helpers\Constants; @endphp

<div>
    {{-- Fixed Header --}}
    <header
        class="sticky top-0 z-30 w-full h-16 bg-base-100 dark:bg-base-200 border-b border-base-300 dark:border-base-content/10 shadow-sm">
        <div class="flex items-center justify-between h-full px-6">
            {{-- Left Side: Logo, Search --}}
            <div class="flex items-center gap-6 flex-1">
                {{-- Mobile Sidebar Toggle --}}
                @if (!$showMenu)
                    <button @click="$dispatch('toggle-sidebar')"
                        class="lg:hidden p-2 text-base-content/70 hover:text-base-content hover:bg-base-200 rounded-lg">
                        <x-icon name="o-bars-3" class="w-5 h-5" />
                    </button>
                @endif

                {{-- Brand Logo --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary/10">
                        <x-icon name="o-cube" class="w-5 h-5 text-primary" />
                    </div>
                    <span class="text-lg font-bold text-base-content hidden sm:inline">فرست</span>
                </a>

                {{-- Search Input --}}
                <div class="hidden md:flex items-center flex-1 max-w-md">
                    <div class="relative w-full">
                        <x-icon name="o-magnifying-glass"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-base-content/40" />
                        <input type="text" placeholder="جستجو (Ctrl+/)"
                            class="w-full pr-10 pl-4 py-2 text-sm bg-base-200 border border-base-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                            wire:model.live.debounce.300ms="search" />
                    </div>
                </div>
            </div>

            {{-- Right Side: Icons, Notifications, User Menu --}}
            <div class="flex items-center gap-2">
                {{-- Quick Actions Grid --}}
                <x-button class="btn-ghost btn-sm btn-square" icon="o-rectangle-stack" />

                {{-- Credit Card Icon --}}
                <x-button class="btn-ghost btn-sm btn-square" icon="o-credit-card" />

                {{-- Theme Toggle --}}
                <x-theme-toggle class="btn-ghost btn-sm btn-square" />

                {{-- Language Selector (Flag) --}}
                <x-dropdown>
                    <x-slot:trigger>
                        <x-button class="btn-ghost btn-sm btn-square">
                            <span class="text-xl">🇮🇷</span>
                        </x-button>
                    </x-slot:trigger>
                    <x-menu class="w-48">
                        <x-menu-item title="فارسی" />
                        <x-menu-item title="English" />
                        <x-menu-item title="Français" />
                        <x-menu-item title="Deutsch" />
                        <x-menu-item title="Português" />
                    </x-menu>
                </x-dropdown>

                <div class="h-6 w-px bg-base-content/10 mx-2" aria-hidden="true"></div>

                {{-- Notifications --}}
                <x-button class="btn-ghost btn-sm btn-square relative" icon="o-bell-alert"
                    wire:click="$toggle('notifications_drawer')">
                    @if ($notifications->count() > 0)
                        <span
                            class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-error rounded-full">
                            {{ $notifications->count() > 9 ? '9+' : $notifications->count() }}
                        </span>
                    @endif
                </x-button>

                {{-- Messages --}}
                <x-button class="btn-ghost btn-sm btn-square relative" icon="o-envelope"
                    wire:click="$toggle('messages_drawer')">
                    <span
                        class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-error rounded-full">
                        4
                    </span>
                </x-button>

                <div class="h-6 w-px bg-base-content/10 mx-2" aria-hidden="true"></div>

                {{-- User Profile Dropdown --}}
                <x-dropdown>
                    <x-slot:trigger>
                        <button
                            class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-base-200 transition-colors">
                            <div class="avatar">
                                <div class="w-8 rounded-full ring-2 ring-primary/20">
                                    <img src="{{ auth()->user()->getFirstMediaUrl('avatar', Constants::RESOLUTION_100_SQUARE) ?: asset('assets/images/default-avatar.png') }}"
                                        alt="{{ auth()->user()->full_name }}" />
                                </div>
                            </div>
                            <div class="hidden lg:block text-right">
                                <div class="text-sm font-medium text-base-content">{{ auth()->user()->full_name }}</div>
                                <div class="text-xs text-base-content/60">مدیر</div>
                            </div>
                            <x-icon name="o-chevron-down" class="w-4 h-4 text-base-content/60 hidden lg:block" />
                        </button>
                    </x-slot:trigger>
                    <x-menu class="w-56">
                        <li class="text-xs menu-title text-base-content/60">{{ auth()->user()->email }}</li>
                        <x-menu-item title="پروفایل من" icon="o-user-circle" :link="route('admin.app.profile', ['user' => auth()->id()])" />
                        <x-menu-item title="تنظیمات" icon="o-cog-6-tooth" :link="route('admin.setting')" />
                        <x-menu-item title="صورتحساب 4" icon="o-credit-card" />
                        <div class="my-1 divider"></div>
                        <x-menu-item title="راهنمایی" icon="o-question-mark-circle" />
                        <x-menu-item title="سوالات متداول" icon="o-information-circle" />
                        <x-menu-item title="قیمت گذاری" icon="o-tag" />
                        <div class="my-1 divider"></div>
                        <x-menu-item title="خروج" icon="o-arrow-right-start-on-rectangle" class="text-error"
                            :link="route('admin.auth.logout')" no-wire-navigate="true" />
                    </x-menu>
                </x-dropdown>
            </div>
        </div>
    </header>

    {{-- Notifications Drawer --}}
    <x-drawer wire:model="notifications_drawer" title="اعلان‌ها" separator with-close-button close-on-escape
        class="w-11/12 lg:w-1/3" right>
        @forelse($notifications as $notification)
            <x-list-item>
                <x-slot:value>
                    {{ App\Helpers\NotifyHelper::title($notification->data) }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ \Illuminate\Support\Str::limit(App\Helpers\NotifyHelper::subTitle($notification->data), 80) }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-eye" class="btn-sm" wire:click="toastNotification('{{ $notification->id }}')" />
                </x-slot:actions>
            </x-list-item>
            @if ($loop->last)
                <div class="flex gap-4 mt-5">
                    <x-button class="flex-1 btn-primary" :label="trans('notification.read_all')" />
                </div>
            @endif
        @empty
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <x-icon name="o-bell-slash" class="w-16 h-16 text-base-content/20 mb-4" />
                <p class="text-base-content/60">هیچ اعلانی وجود ندارد</p>
            </div>
        @endforelse
    </x-drawer>

    {{-- Messages Drawer --}}
    <x-drawer wire:model="messages_drawer" title="پیام‌ها" separator with-close-button close-on-escape
        class="w-11/12 lg:w-1/3" right>
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <x-icon name="o-envelope" class="w-16 h-16 text-base-content/20 mb-4" />
            <p class="text-base-content/60">هیچ پیامی وجود ندارد</p>
        </div>
    </x-drawer>
</div>
