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

            {{-- Horizontal Menu for Teacher/Parent/User --}}
            @if ($showMenu)
                {{-- Desktop Horizontal Menu --}}
                <div class="hidden gap-1 items-center lg:flex">
                    @foreach ($navbarMenu ?? [] as $menu)
                        @if (Arr::has($menu, 'sub_menu'))
                            @if (Arr::get($menu, 'access', true))
                                <x-dropdown>
                                    <x-slot:trigger>
                                        <x-button class="gap-2 btn-ghost btn-sm" icon="{{ Arr::get($menu, 'icon') }}">
                                            {{ Arr::get($menu, 'title') }}
                                            <x-icon name="o-chevron-down" class="w-3 h-3" />
                                        </x-button>
                                    </x-slot:trigger>
                                    <x-menu class="w-52">
                                        @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                            @if (Arr::get($subMenu, 'access', true))
                                                <x-menu-item :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')" :link="route(
                                                    Arr::get($subMenu, 'route_name'),
                                                    Arr::get($subMenu, 'params', []),
                                                )" />
                                            @endif
                                        @endforeach
                                    </x-menu>
                                </x-dropdown>
                            @endif
                        @elseif(!Arr::has($menu, 'type'))
                            @if (Arr::get($menu, 'access', true))
                                <x-button class="btn-ghost btn-sm" icon="{{ Arr::get($menu, 'icon') }}"
                                    link="{{ route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', [])) }}">
                                    {{ Arr::get($menu, 'title') }}
                                </x-button>
                            @endif
                        @endif
                    @endforeach
                </div>

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
                    <x-slot:trigger class="btn-ghost btn-sm btn-square">
                        <x-icon name="o-rectangle-stack" class="w-5 h-5" />
                    </x-slot:trigger>
                    <x-slot:content class="!w-70 grid grid-cols-4 gap-4">
                        <x-button class="bg-primary text-white w-[50px] h-[50px]">
                            <x-icon name="lucide.activity" />
                        </x-button>
                        <x-button class="bg-primary text-white w-[50px] h-[50px]">
                            <x-icon name="lucide.activity" />
                        </x-button>
                        <x-button class="bg-primary text-white w-[50px] h-[50px]">
                            <x-icon name="lucide.activity" />
                        </x-button>
                        <x-button class="bg-primary text-white w-[50px] h-[50px]">
                            <x-icon name="lucide.activity" />
                        </x-button>
                        <x-button class="bg-primary text-white w-[50px] h-[50px]">
                            <x-icon name="lucide.activity" />
                        </x-button>
                        <x-button class="bg-primary text-white w-[50px] h-[50px]">
                            <x-icon name="lucide.activity" />
                        </x-button>
                    </x-slot:content>
                </x-popover>
            @endif

            {{-- Notifications --}}
            <x-button class="btn-ghost btn-sm btn-square" icon="o-bell-alert"
                wire:click="$toggle('notifications_drawer')" />

            {{-- Theme Toggle --}}
            <x-theme-toggle class="btn-ghost btn-sm btn-square" />

            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-base-content/10" aria-hidden="true"></div>

            {{-- Profile dropdown --}}
            <x-dropdown>
                <x-slot:trigger>
                    <x-button class="btn btn-ghost btn-circle btn-sm">
                        <div class="avatar">
                            <div class="w-8 rounded-full">
                                <img src="{{ auth()->user()->getFirstMediaUrl('avatar', Constants::RESOLUTION_100_SQUARE) }}"
                                    alt="{{ auth()->user()->full_name }}" />
                            </div>
                        </div>
                    </x-button>
                </x-slot:trigger>
                <x-menu class="w-52">
                    <li class="text-xs menu-title">{{ auth()->user()->email }}</li>
                    <x-menu-item :title="trans('_menu.setting')" icon="o-cog-6-tooth" :link="route('admin.setting')" />
                    <div class="my-1 divider"></div>
                    <x-menu-item title="Logout" icon="o-arrow-right-start-on-rectangle" class="text-error"
                        :link="route('admin.auth.logout')" no-wire-navigate="true" />
                </x-menu>
            </x-dropdown>
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
</div>
