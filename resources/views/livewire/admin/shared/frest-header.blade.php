@php use App\Helpers\Constants; @endphp

<div>
    {{-- Fixed Header --}}
    <header
        class="sticky top-0 z-30 w-full h-16 border-b shadow-sm bg-base-100 dark:bg-base-200 border-base-300 dark:border-base-content/10">
        <div class="flex justify-between items-center px-6 h-full">
            {{-- Left Side: Logo, Search --}}
            <div class="flex flex-1 gap-6 items-center">
                {{-- Mobile Sidebar Toggle --}}
                @if (!$showMenu)
                    <button @click="$dispatch('toggle-sidebar')"
                        class="p-2 rounded-lg lg:hidden text-base-content/70 hover:text-base-content hover:bg-base-200">
                        <x-icon name="o-bars-3" class="w-5 h-5" />
                    </button>
                @endif

                {{-- Brand Logo --}}
                <a href="{{ route('admin.dashboard') }}" class="flex gap-2 items-center shrink-0"
                    aria-label="بازگشت به داشبورد">
                    <div class="flex justify-center items-center w-8 h-8 rounded-lg bg-primary/10">
                        <x-icon name="o-cube" class="w-5 h-5 text-primary" />
                    </div>
                    <span class="hidden text-lg font-bold text-base-content sm:inline">فرست</span>
                </a>

                {{-- Search Input --}}
                <div class="hidden flex-1 items-center max-w-md md:flex">
                    <div class="relative w-full">
                        <x-icon name="o-magnifying-glass"
                            class="absolute right-3 top-1/2 w-5 h-5 -translate-y-1/2 text-base-content/40" />
                        <input type="text" placeholder="جستجو (Ctrl+/)"
                            class="py-2 pr-10 pl-4 w-full text-sm rounded-lg border bg-base-200 border-base-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                            wire:model.live.debounce.300ms="search" />
                    </div>
                </div>
            </div>

            {{-- Right Side: Icons, Notifications, User Menu --}}
            <div class="flex gap-2 items-center">
                {{-- Quick Actions Grid --}}
                <x-button class="btn-ghost btn-sm btn-square" icon="o-rectangle-stack" title="عملیات سریع" />

                {{-- Credit Card Icon --}}
                <x-button class="btn-ghost btn-sm btn-square" icon="o-credit-card" title="پرداخت‌ها" />

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

                <div class="mx-2 w-px h-6 bg-base-content/10" aria-hidden="true"></div>

                {{-- Notifications --}}
                <x-button class="relative btn-ghost btn-sm btn-square" icon="o-bell-alert"
                    wire:click="$toggle('notifications_drawer')">
                    @if ($this->notifications->count() > 0)
                        <span
                            class="flex absolute -top-1 -right-1 justify-center items-center w-5 h-5 text-xs font-bold text-white rounded-full bg-error">
                            {{ $this->notifications->count() > 9 ? '9+' : $this->notifications->count() }}
                        </span>
                    @endif
                </x-button>

                {{-- Messages --}}
                <x-button class="relative btn-ghost btn-sm btn-square" icon="o-envelope"
                    wire:click="$toggle('messages_drawer')">
                    <span
                        class="flex absolute -top-1 -right-1 justify-center items-center w-5 h-5 text-xs font-bold text-white rounded-full bg-error">
                        4
                    </span>
                </x-button>

                <div class="mx-2 w-px h-6 bg-base-content/10" aria-hidden="true"></div>

                {{-- User Profile Dropdown --}}
                <x-dropdown>
                    <x-slot:trigger>
                        <button
                            class="flex gap-2 items-center px-2 py-1.5 rounded-lg transition-colors hover:bg-base-200">
                            <div class="avatar">
                                <div class="w-8 rounded-full ring-2 ring-primary/20">
                                    <img src="{{ auth()->user()->getFirstMediaUrl('avatar', Constants::RESOLUTION_100_SQUARE) ?: asset('assets/images/default-avatar.png') }}"
                                        alt="{{ auth()->user()->full_name }}" />
                                </div>
                            </div>
                            <div class="hidden text-right lg:block">
                                <div class="text-sm font-medium text-base-content">{{ auth()->user()->full_name }}</div>
                                <div class="text-xs text-base-content/60">مدیر</div>
                            </div>
                            <x-icon name="o-chevron-down" class="hidden w-4 h-4 text-base-content/60 lg:block" />
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
        @forelse($this->notifications as $notification)
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
            <div class="flex flex-col justify-center items-center py-12 text-center">
                <x-icon name="o-bell-slash" class="mb-4 w-16 h-16 text-base-content/20" />
                <p class="text-base-content/60">هیچ اعلانی وجود ندارد</p>
            </div>
        @endforelse
    </x-drawer>

    {{-- Messages Drawer --}}
    <x-drawer wire:model="messages_drawer" title="پیام‌ها" separator with-close-button close-on-escape
        class="w-11/12 lg:w-1/3" right>
        <div class="flex flex-col justify-center items-center py-12 text-center">
            <x-icon name="o-envelope" class="mb-4 w-16 h-16 text-base-content/20" />
            <p class="text-base-content/60">هیچ پیامی وجود ندارد</p>
        </div>
    </x-drawer>
</div>
