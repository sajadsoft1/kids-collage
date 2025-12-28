@php use App\Helpers\Constants; @endphp

<div>
    <x-nav sticky full-width class="{{ $nav_class }}">
        <x-slot:brand class="gap-6">
            {{-- Drawer toggle for "main-drawer" (only for employees with sidebar) --}}
            @if (!$showMenu)
                <label for="main-drawer" class="me-3 lg:hidden">
                    <x-icon name="o-bars-3" class="cursor-pointer" />
                </label>
            @endif

            {{-- Brand --}}
            <a href="{{ route('admin.dashboard') }}" class="flex gap-2 items-center shrink-0">
                <x-icon name="o-cube" class="w-6 h-6 text-primary" />
                <span class="text-lg font-semibold">{{ config('app.name') }}</span>
            </a>

            @if ($showMenu)
                {{-- Mobile Menu Toggle --}}
                <div class="lg:hidden" x-data="{ mobileMenuOpen: false }">
                    <x-button class="btn-ghost btn-square btn-sm" @click="mobileMenuOpen = true">
                        <x-icon name="o-bars-3" class="w-5 h-5" />
                    </x-button>

                    {{-- Mobile Menu Drawer --}}
                    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 bg-black/50"
                        @click="mobileMenuOpen = false" x-cloak>
                    </div>

                    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-4"
                        class="fixed top-0 left-0 right-0 z-50 p-4 mx-4 mt-4 rounded-2xl bg-base-100 shadow-xl max-h-[80vh] overflow-y-auto"
                        @click.outside="mobileMenuOpen = false" x-cloak>

                        {{-- Header --}}
                        <div class="flex justify-between items-center pb-4 mb-4 border-b border-base-300">
                            <div class="flex gap-2 items-center">
                                <x-icon name="o-cube" class="w-6 h-6 text-primary" />
                                <span class="font-semibold">{{ config('app.name') }}</span>
                            </div>
                            <x-button class="btn-ghost btn-square btn-sm" @click="mobileMenuOpen = false">
                                <x-icon name="o-x-mark" class="w-5 h-5" />
                            </x-button>
                        </div>

                        {{-- Menu Items --}}
                        <x-menu class="p-0">
                            @foreach ($navbarMenu ?? [] as $menu)
                                @if (Arr::has($menu, 'sub_menu'))
                                    @if (Arr::get($menu, 'access', true))
                                        <li class="mt-4 first:mt-0">
                                            <div
                                                class="flex gap-2 items-center px-2 mb-2 text-xs font-semibold uppercase text-base-content/50">
                                                <x-icon name="{{ Arr::get($menu, 'icon') }}" class="w-4 h-4" />
                                                {{ Arr::get($menu, 'title') }}
                                            </div>
                                        </li>
                                        @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                            @if (Arr::get($subMenu, 'access', true))
                                                <x-menu-item :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')" :link="route(
                                                    Arr::get($subMenu, 'route_name'),
                                                    Arr::get($subMenu, 'params', []),
                                                )"
                                                    @click="mobileMenuOpen = false" />
                                            @endif
                                        @endforeach
                                    @endif
                                @elseif(!Arr::has($menu, 'type'))
                                    @if (Arr::get($menu, 'access', true))
                                        <x-menu-item :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))"
                                            @click="mobileMenuOpen = false" />
                                    @endif
                                @endif
                            @endforeach
                        </x-menu>
                    </div>
                </div>
            @endif
        </x-slot:brand>

        <x-slot:actions>
            {{-- Quick Actions (only for employees) --}}
            @if (!$showMenu)
                <x-popover>
                    <x-slot:trigger>
                        <x-button class="btn-ghost btn-sm btn-square" icon="o-rectangle-stack"/>
                    </x-slot:trigger>
                    <x-slot:content class="!w-70 grid grid-cols-4 gap-4">
                        @foreach($this->quickAccess() as $menu)
                            <x-button class="bg-primary text-white w-[50px] h-[50px]" :tooltip="$menu['label']" :link="$menu['route']">
                                <x-icon :name="$menu['icon']" />
                            </x-button>
                        @endforeach
                    </x-slot:content>
                </x-popover>
            @endif

            {{-- Notifications --}}
            <x-button class="btn-ghost btn-sm btn-square" icon="o-bell-alert"
                wire:click="$toggle('notifications_drawer')" />

            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-base-content/10" aria-hidden="true"></div>

            {{-- Profile drawer trigger --}}
            <x-button class="btn btn-ghost btn-circle btn-sm" wire:click="$toggle('profile_drawer')">
                <div class="avatar">
                    <div class="w-8 rounded-full">
                        <img src="{{ auth()->user()->getFirstMediaUrl('avatar', Constants::RESOLUTION_100_SQUARE) }}"
                            alt="{{ auth()->user()->full_name }}" />
                    </div>
                </div>
            </x-button>
        </x-slot:actions>
    </x-nav>

    {{-- Notifications Drawer --}}
    <x-drawer wire:model="notifications_drawer" :title="trans('notification.models')" separator with-close-button close-on-escape
        class="w-11/12 lg:w-1/3" right>
        @forelse($notificaations as $notif)
            <x-list-item :item="$notif">
                <x-slot:value>
                    {{ App\Helpers\NotifyHelper::title($notif->data) }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ \Illuminate\Support\Str::limit(App\Helpers\NotifyHelper::subTitle($notif->data)) }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-eye" class="btn-sm" wire:click="toastNotification('{{ $notif->id }}')" />
                </x-slot:actions>
            </x-list-item>
            @if ($loop->last)
                <div class="flex gap-4 mt-5">
                    <x-button class="flex-1 btn-primary" spinner :label="trans('notification.read_all')" />
                    <x-button class="flex-1 btn-primary" spinner :label="trans('notification.read_all')" />
                </div>
            @endif
        @empty
            <x-admin.shared.empty-view class:image="size-60" :title="trans('core.notification.empty_title')" :description="trans('core.notification.empty_description')" :image="asset('assets/images/png/no-data.png')"
                :btn_label="trans('core.notification.empty_btn')" :btn_link="route('admin.notification.index')" />
        @endforelse
    </x-drawer>

    {{-- Profile Drawer --}}
    <x-drawer wire:model="profile_drawer" title="پروفایل کاربر" separator with-close-button close-on-escape
        class="w-full max-w-md" right>

        {{-- User Profile Section --}}
        <div class="flex gap-4 items-start pb-6 mb-6 border-b border-base-300">
            <div class="relative avatar">
                <div class="w-16 rounded-full ring ring-offset-2 ring-primary ring-offset-base-100">
                    <img src="{{ auth()->user()->getFirstMediaUrl('avatar', Constants::RESOLUTION_100_SQUARE) }}"
                        alt="{{ auth()->user()->full_name }}" />
                </div>
                <div class="absolute right-0 bottom-0 w-4 h-4 rounded-full border-2 bg-success border-base-100">
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="mb-1 text-lg font-semibold truncate">{{ auth()->user()->full_name }}</h3>
                <p class="mb-2 text-sm text-base-content/70">
                    {{ auth()->user()->profile?->job_title ?? 'کاربر سیستم' }}
                </p>
                <div class="flex gap-2 items-center text-sm text-base-content/60">
                    <x-icon name="o-envelope" class="w-4 h-4 shrink-0" />
                    <span class="truncate">{{ auth()->user()->email }}</span>
                </div>
            </div>
        </div>

        {{-- Settings Section --}}
        <div class="mb-6 space-y-3">
            {{-- Theme Toggle --}}
            <div class="flex gap-3 justify-between items-center p-3 rounded-lg transition-colors hover:bg-base-200">
                <div class="flex gap-3 items-center">
                    <div class="flex flex-shrink-0 justify-center items-center w-10 h-10 rounded-lg bg-primary/10">
                        <x-icon name="o-paint-brush" class="w-5 h-5 text-primary" />
                    </div>
                    <div>
                        <h4 class="font-medium">تغییر تم</h4>
                        <p class="text-xs text-base-content/60">تم روشن / تیره</p>
                    </div>
                </div>
                <x-theme-toggle class="btn-sm btn-ghost" />
            </div>

            {{-- Container Size Toggle --}}
            <div class="flex gap-3 justify-between items-center p-3 rounded-lg transition-colors hover:bg-base-200">
                <div class="flex gap-3 items-center">
                    <div class="flex flex-shrink-0 justify-center items-center w-10 h-10 rounded-lg bg-info/10">
                        <x-icon name="o-arrows-pointing-out" class="w-5 h-5 text-info" />
                    </div>
                    <div>
                        <h4 class="font-medium">سایز محتوا</h4>
                        <p class="text-xs text-base-content/60">
                            {{ $container_size === 'container' ? 'محدود' : 'تمام عرض' }}
                        </p>
                    </div>
                </div>
                <x-button wire:click="toggleContainerSize" class="btn-sm btn-ghost" icon="o-arrows-pointing-out">
                    {{ $container_size === 'container' ? 'تمام عرض' : 'محدود' }}
                </x-button>
            </div>
        </div>

        {{-- Branch Selection (if user has multiple branches) --}}
        @if ($branches->count() > 1)
            <div class="pb-6 mb-6 border-b border-base-300">
                <h4 class="mb-3 text-sm font-semibold text-base-content/70">انتخاب شعبه</h4>
                <div class="space-y-2">
                    @foreach ($branches as $branch)
                        <button wire:click="switchBranch({{ $branch->id }})" wire:loading.attr="disabled"
                            wire:target="switchBranch" wire:loading.class="opacity-50 cursor-not-allowed"
                            class="w-full flex gap-3 items-center p-3 rounded-lg transition-colors text-right cursor-pointer
                            {{ $currentBranchId === $branch->id ? 'bg-primary/10 border border-primary/20' : 'hover:bg-base-200' }}">
                            <div class="flex-1">
                                <div class="font-medium">{{ $branch->name }}</div>
                                @if ($branch->code)
                                    <div class="text-xs text-base-content/60">{{ $branch->code }}</div>
                                @endif
                            </div>
                            @if ($currentBranchId === $branch->id)
                                <x-icon name="o-check-circle" class="w-5 h-5 text-primary shrink-0" />
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Quick Links Section --}}
        <div class="mb-6 space-y-2">
            <h4 class="mb-3 text-sm font-semibold text-base-content/70">لینک‌های سریع</h4>

            {{-- Profile Link --}}
            <a href="{{ route('admin.app.profile', auth()->id()) }}"
                class="flex gap-3 items-center p-3 rounded-lg transition-colors hover:bg-base-200">
                <div class="flex flex-shrink-0 justify-center items-center w-10 h-10 rounded-lg bg-teal-500/10">
                    <x-icon name="o-user-circle" class="w-5 h-5 text-teal-500" />
                </div>
                <div class="flex-1">
                    <h4 class="font-medium">پروفایل من</h4>
                    <p class="text-xs text-base-content/60">مشاهده و ویرایش پروفایل</p>
                </div>
                <x-icon name="o-chevron-left" class="w-4 h-4 text-base-content/40 shrink-0" />
            </a>

            {{-- Notifications Link --}}
            <button wire:click="$toggle('notifications_drawer')"
                class="flex gap-3 items-center p-3 w-full text-right rounded-lg transition-colors hover:bg-base-200">
                <div class="flex flex-shrink-0 justify-center items-center w-10 h-10 rounded-lg bg-yellow-500/10">
                    <x-icon name="o-bell" class="w-5 h-5 text-yellow-500" />
                </div>
                <div class="flex-1">
                    <h4 class="font-medium">اعلانات</h4>
                    <p class="text-xs text-base-content/60">مشاهده اعلانات جدید</p>
                </div>
                @if (count($notificaations) > 0)
                    <span class="badge badge-primary badge-sm">{{ count($notificaations) }}</span>
                @endif
                <x-icon name="o-chevron-left" class="w-4 h-4 text-base-content/40 shrink-0" />
            </button>

            {{-- Calendar Link --}}
            <a href="{{ route('admin.app.calendar') }}"
                class="flex gap-3 items-center p-3 rounded-lg transition-colors hover:bg-base-200">
                <div class="flex flex-shrink-0 justify-center items-center w-10 h-10 rounded-lg bg-blue-500/10">
                    <x-icon name="o-calendar" class="w-5 h-5 text-blue-500" />
                </div>
                <div class="flex-1">
                    <h4 class="font-medium">تقویم</h4>
                    <p class="text-xs text-base-content/60">مشاهده رویدادها و برنامه‌ها</p>
                </div>
                <x-icon name="o-chevron-left" class="w-4 h-4 text-base-content/40 shrink-0" />
            </a>

            {{-- Ticket Link --}}
            <a href="{{ route('admin.ticket.index') }}"
                class="flex gap-3 items-center p-3 rounded-lg transition-colors hover:bg-base-200">
                <div class="flex flex-shrink-0 justify-center items-center w-10 h-10 rounded-lg bg-purple-500/10">
                    <x-icon name="o-ticket" class="w-5 h-5 text-purple-500" />
                </div>
                <div class="flex-1">
                    <h4 class="font-medium">تیکت جدید</h4>
                    <p class="text-xs text-base-content/60">ایجاد تیکت پشتیبانی</p>
                </div>
                <x-icon name="o-chevron-left" class="w-4 h-4 text-base-content/40 shrink-0" />
            </a>
        </div>

        {{-- Logout Button --}}
        <div class="pt-4 border-t border-base-300">
            <x-button :link="route('admin.auth.logout')" no-wire-navigate="true" class="w-full btn-outline btn-error"
                icon="o-arrow-right-start-on-rectangle">
                خروج از حساب کاربری
            </x-button>
        </div>
    </x-drawer>
</div>
