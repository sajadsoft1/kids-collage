<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">

@include('components.layouts.shared.head')

<body class="bg-base-200/50" x-data>
    {{-- Mobile Navigation --}}
    <x-nav sticky class="h-14 border-b lg:hidden bg-base-100 border-base-300">
        <x-slot:brand>
            <div class="flex gap-2 items-center">
                <x-icon name="o-cube" class="w-6 h-6 text-primary" />
                <span class="font-semibold">Karnoweb</span>
            </div>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="btn btn-ghost btn-sm btn-square">
                <x-icon name="o-bars-3" class="w-5 h-5" />
            </label>
        </x-slot:actions>
    </x-nav>

    <x-main full-width class="min-h-screen" active-bg-color="">

        {{-- Sidebar: Clean & Focused --}}
        <x-slot:sidebar drawer="main-drawer" collapsible
            class="w-64 no-scrollbar bg-base-100 border-e border-base-300">

            {{-- Brand: Minimal --}}
            <div class="sticky top-0 z-10 border-b bg-base-100 border-base-300">
                <div class="p-4 hidden-when-collapsed">
                    <div class="flex justify-between items-center">
                        <div class="flex gap-2 items-center">
                            <x-icon name="o-cube" class="w-7 h-7 text-primary" />
                            <div>
                                <h1 class="font-bold text-base-content">Karnoweb</h1>
                                <p class="text-xs text-base-content/60">Enterprise Dashboard</p>
                            </div>
                        </div>
                    </div>
                    {{-- Compact Status Bar --}}
                    <div class="flex gap-3 items-center mt-3 text-xs">
                        <span class="flex gap-1 items-center text-success">
                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                            Online
                        </span>
                        <span class="text-base-content/50">|</span>
                        <span class="text-base-content/60">{{ \App\Models\User::count() }} users</span>
                    </div>
                </div>
                <div class="hidden py-4 display-when-collapsed">
                    <x-icon name="o-cube" class="mx-auto w-7 h-7 text-primary" />
                </div>
            </div>

            {{-- Navigation: Clean hierarchy --}}
            <x-menu activate-by-route class="!p-0 flex flex-col">
                <div class="overflow-y-auto flex-1 px-2 py-2">
                    @foreach ($navbarMenu ?? [] as $menu)
                        @if (Arr::has($menu, 'sub_menu'))
                            @if (Arr::get($menu, 'access', true))
                                <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')">
                                    @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                        <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')"
                                            :badge="Arr::get($subMenu, 'badge')" :badge-classes="Arr::get($subMenu, 'badge_classes', 'float-left badge-sm')" :link="route(
                                                Arr::get($subMenu, 'route_name'),
                                                Arr::get($subMenu, 'params', []),
                                            )" />
                                    @endforeach
                                </x-menu-sub>
                            @endif
                        @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                            <x-menu-separator :title="Arr::get($menu, 'title')" class="my-2 opacity-50" />
                        @else
                            @if (Arr::get($menu, 'access', true))
                                <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :badge="Arr::get($menu, 'badge')"
                                    :badge-classes="Arr::get($menu, 'badge_classes', 'float-left badge-sm')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))" />
                            @endif
                        @endif
                    @endforeach
                </div>
            </x-menu>

            {{-- User: Compact footer --}}
            <div class="p-2 border-t border-base-300">
                <x-dropdown>
                    <x-slot:trigger>
                        <div
                            class="flex gap-2 items-center p-2 rounded-lg transition-colors cursor-pointer hover:bg-base-200">
                            <x-avatar src="{{ auth()->user()->avatar ?? '' }}" class="!w-8 !h-8">
                                <x-icon name="o-user" class="w-4 h-4" />
                            </x-avatar>
                            <div class="flex-1 min-w-0 hidden-when-collapsed">
                                <div class="text-sm font-medium truncate">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-success">● Online</div>
                            </div>
                            <x-icon name="o-chevron-up" class="w-4 h-4 text-base-content/50 hidden-when-collapsed" />
                        </div>
                    </x-slot:trigger>
                    <x-menu class="w-52">
                        <x-menu-item title="{{ auth()->user()->email }}"
                            class="text-xs pointer-events-none text-base-content/60" />
                        <x-menu-separator />
                        <x-menu-item title="Profile" icon="o-user"
                            link="{{ route('admin.app.profile', ['user' => auth()->id()]) }}" />
                        <x-menu-item title="Settings" icon="o-cog-6-tooth" link="{{ route('admin.setting') }}" />
                        <x-menu-separator />
                        <x-menu-item title="Logout" icon="o-arrow-right-start-on-rectangle"
                            link="{{ route('admin.auth.logout') }}" class="text-error" />
                    </x-menu>
                </x-dropdown>
            </div>
        </x-slot:sidebar>

        {{-- Main Content Area --}}
        <x-slot:content class="!p-0 flex flex-col min-h-screen">
            {{-- Header: Focused & Functional --}}
            <header class="sticky top-0 z-40 border-b bg-base-100 border-base-300">
                <div class="flex justify-between items-center px-4 h-14 lg:px-6">
                    {{-- Left: Title only (breadcrumbs via slot if needed) --}}
                    <div class="flex gap-3 items-center min-w-0">
                        <h1 class="text-lg font-semibold truncate text-base-content">{{ $title ?? 'Dashboard' }}</h1>
                    </div>

                    {{-- Right: Grouped actions by priority --}}
                    <div class="flex items-center">
                        {{-- Primary: Search (keyboard shortcut hint) --}}
                        <div class="hidden lg:block">
                            <x-input icon="o-magnifying-glass" placeholder="Search... ⌘K"
                                class="w-48 border-0 transition-all input-sm focus:w-64 bg-base-200/50"
                                @click="$dispatch('mary-search-open')" readonly />
                        </div>

                        {{-- Secondary: Quick actions group --}}
                        <div class="flex items-center border-s border-base-300 ms-3 ps-3">
                            {{-- Notifications --}}
                            <x-dropdown>
                                <x-slot:trigger>
                                    <x-button icon="o-bell" class="relative btn-ghost btn-sm btn-square">
                                        <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-error"></span>
                                    </x-button>
                                </x-slot:trigger>
                                <x-menu class="w-72">
                                    <div class="px-3 py-2 border-b border-base-300">
                                        <span class="text-sm font-medium">Notifications</span>
                                    </div>
                                    <x-menu-item title="System backup completed" icon="o-check-circle"
                                        class="text-success" />
                                    <x-menu-item title="New user registered" icon="o-user-plus" />
                                    <x-menu-item title="Security alert" icon="o-exclamation-triangle"
                                        class="text-warning" />
                                    <div class="px-3 py-2 border-t border-base-300">
                                        <a href="#" class="text-xs text-primary hover:underline">View all
                                            notifications</a>
                                    </div>
                                </x-menu>
                            </x-dropdown>

                            {{-- Phone Status --}}
                            <x-button icon="o-phone" class="hidden btn-ghost btn-sm btn-square lg:flex" />

                            {{-- Theme Toggle --}}
                            <x-theme-toggle class="btn-ghost btn-sm btn-square" />
                        </div>

                        {{-- Tertiary: Quick Actions (most prominent) --}}
                        <div class="border-s border-base-300 ms-3 ps-3">
                            <x-dropdown>
                                <x-slot:trigger>
                                    <x-button icon="o-plus" class="gap-1 btn-primary btn-sm">
                                        <span class="hidden sm:inline">Quick Actions</span>
                                    </x-button>
                                </x-slot:trigger>
                                <x-menu class="w-48">
                                    <x-menu-item title="New User" icon="o-user-plus" />
                                    <x-menu-item title="New Blog" icon="o-document-plus" />
                                    <x-menu-item title="New Category" icon="o-folder-plus" />
                                </x-menu>
                            </x-dropdown>
                        </div>
                    </div>
                </div>

                {{-- Sub-header: Breadcrumbs & page actions (optional slot) --}}
                @if (isset($subheader))
                    <div
                        class="flex justify-between items-center px-4 py-2 text-sm border-t lg:px-6 bg-base-200/30 border-base-300">
                        {{ $subheader }}
                    </div>
                @endif
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-4 lg:p-6">
                {{-- Stats Row (optional, compact) --}}
                @if (isset($stats))
                    <div class="grid grid-cols-2 gap-3 mb-6 lg:grid-cols-4">
                        @foreach ($stats as $stat)
                            <div class="p-4 rounded-lg border bg-base-100 border-base-300">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="text-xl font-bold">{{ $stat['value'] }}</div>
                                        <div class="text-xs text-base-content/60">{{ $stat['label'] }}</div>
                                    </div>
                                    <x-icon :name="$stat['icon']"
                                        class="w-5 h-5 text-{{ $stat['color'] ?? 'base-content/30' }}" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Main Content Card --}}
                <div class="rounded-lg border bg-base-100 border-base-300">
                    {{ $slot }}
                </div>
            </main>

            {{-- Footer: Minimal, on-demand info --}}
            <footer class="px-4 py-3 mt-auto border-t border-base-300 lg:px-6 bg-base-100">
                <div class="flex justify-between items-center text-xs text-base-content/50">
                    <span>&copy; {{ date('Y') }} Karnoweb v{{ config('app.version', '1.0.0') }}</span>
                    <div class="flex gap-4 items-center">
                        <span class="hidden lg:inline">Server: {{ config('app.env') }}</span>
                        <a href="#" class="hover:text-base-content">Support</a>
                    </div>
                </div>
            </footer>
        </x-slot:content>
    </x-main>

    @include('components.layouts.shared.shared')
</body>

</html>
