<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}"
    data-theme="light">
@include('components.layouts.shared.head')

<body class="min-h-screen bg-base-200" x-data>

    {{-- Mobile Navbar --}}
    <div class="border-b navbar bg-base-100 border-base-300 lg:hidden">
        <div class="navbar-start">
            <a href="{{ route('admin.dashboard') }}" class="flex gap-2 items-center">
                <x-icon name="o-cube" class="size-6 text-primary" />
                <span class="text-lg font-bold">Karnoweb</span>
            </a>
        </div>
        <div class="navbar-end">
            <label for="main-drawer" class="btn btn-ghost btn-square">
                <x-icon name="o-bars-3" class="size-5" />
            </label>
        </div>
    </div>

    {{-- Main Layout --}}
    <x-main full-width class="min-h-screen" active-bg-color="">

        {{-- Sidebar --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="w-64 bg-base-100 border-base-300 no-scrollbar">

            {{-- Brand --}}
            <div class="sticky top-0 z-10 border-b bg-base-100 border-base-300">
                <div class="p-4 hidden-when-collapsed">
                    <a href="{{ route('admin.dashboard') }}" class="flex gap-3 items-center">
                        <div
                            class="flex justify-center items-center rounded-lg size-10 bg-primary text-primary-content">
                            <x-icon name="o-cube" class="size-5" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-base font-bold text-base-content">Karnoweb</span>
                            <span class="text-xs text-base-content/60">Admin Dashboard</span>
                        </div>
                    </a>
                </div>
                <div class="hidden p-4 display-when-collapsed">
                    <div
                        class="flex justify-center items-center mx-auto rounded-lg size-10 bg-primary text-primary-content">
                        <x-icon name="o-cube" class="size-5" />
                    </div>
                </div>
            </div>

            {{-- Navigation Menu --}}
            <x-menu activate-by-route class="flex flex-col p-0">
                <nav class="overflow-y-auto flex-1 p-3">
                    @foreach ($navbarMenu ?? [] as $menu)
                        @if (Arr::has($menu, 'sub_menu'))
                            @if (Arr::get($menu, 'access', true))
                                <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')">
                                    @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                        <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')"
                                            :badge="Arr::get($subMenu, 'badge')" :badge-classes="Arr::get($subMenu, 'badge_classes', 'badge-sm')" :link="route(
                                                Arr::get($subMenu, 'route_name'),
                                                Arr::get($subMenu, 'params', []),
                                            )" />
                                    @endforeach
                                </x-menu-sub>
                            @endif
                        @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                            <div class="my-2 text-xs divider text-base-content/40">{{ Arr::get($menu, 'title') }}</div>
                        @else
                            @if (Arr::get($menu, 'access', true))
                                <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :badge="Arr::get($menu, 'badge')"
                                    :badge-classes="Arr::get($menu, 'badge_classes', 'badge-sm')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))" />
                            @endif
                        @endif
                    @endforeach
                </nav>
            </x-menu>

            {{-- User Profile --}}
            <div class="p-3 border-t border-base-300">
                <x-dropdown>
                    <x-slot:trigger>
                        <button type="button" class="gap-3 justify-start px-2 w-full btn btn-ghost">
                            <div class="avatar placeholder">
                                <div class="w-8 rounded-full bg-neutral text-neutral-content">
                                    @if (auth()->user()->avatar)
                                        <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" />
                                    @else
                                        <span class="text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="flex-1 text-sm font-medium truncate hidden-when-collapsed text-start">
                                {{ auth()->user()->name }}
                            </span>
                            <x-icon name="o-chevron-up" class="opacity-50 hidden-when-collapsed size-4" />
                        </button>
                    </x-slot:trigger>
                    <x-menu class="w-56">
                        <li class="text-xs menu-title">{{ auth()->user()->email }}</li>
                        <x-menu-item title="Profile" icon="o-user"
                            link="{{ route('admin.app.profile', ['user' => auth()->id()]) }}" />
                        <x-menu-item title="Settings" icon="o-cog-6-tooth" link="{{ route('admin.setting') }}" />
                        <div class="my-1 divider"></div>
                        <x-menu-item title="Logout" icon="o-arrow-right-start-on-rectangle"
                            link="{{ route('admin.auth.logout') }}" class="text-error" />
                    </x-menu>
                </x-dropdown>
            </div>
        </x-slot:sidebar>

        {{-- Content Area --}}
        <x-slot:content class="flex flex-col p-0 min-h-screen">

            {{-- Header --}}
            <header class="sticky top-0 z-40 gap-4 px-4 border-b navbar border-base-300 bg-base-100 lg:px-6">
                {{-- Title --}}
                <div class="flex-1">
                    <h1 class="text-lg font-semibold text-base-content">{{ $title ?? 'Dashboard' }}</h1>
                </div>

                {{-- Actions --}}
                <div class="flex-none">
                    <div class="flex gap-1 items-center">
                        {{-- Search --}}
                        <div class="hidden form-control lg:block">
                            <label
                                class="flex gap-2 items-center w-52 transition-all input input-bordered input-sm bg-base-200/50 focus-within:w-72">
                                <x-icon name="o-magnifying-glass" class="opacity-50 size-4" />
                                <input type="text" placeholder="Search..." class="bg-transparent grow"
                                    @click="$dispatch('mary-search-open')" readonly />
                                <kbd class="kbd kbd-sm">⌘K</kbd>
                            </label>
                        </div>

                        {{-- Mobile Search --}}
                        <button type="button" class="btn btn-ghost btn-square btn-sm lg:hidden"
                            @click="$dispatch('mary-search-open')">
                            <x-icon name="o-magnifying-glass" class="size-5" />
                        </button>

                        {{-- Divider --}}
                        <div class="hidden mx-1 divider divider-horizontal lg:flex"></div>

                        {{-- Notifications --}}
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn btn-ghost btn-square btn-sm">
                                <div class="indicator">
                                    <x-icon name="o-bell" class="size-5" />
                                    <span class="badge badge-error badge-xs indicator-item"></span>
                                </div>
                            </div>
                            <div tabindex="0"
                                class="z-50 mt-3 w-72 shadow-xl dropdown-content card card-compact bg-base-100">
                                <div class="card-body">
                                    <h3 class="text-sm card-title">Notifications</h3>
                                    <ul class="p-0 menu menu-sm">
                                        <li>
                                            <a class="gap-3">
                                                <x-icon name="o-envelope" class="size-4 text-info" />
                                                <span>New message received</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="gap-3">
                                                <x-icon name="o-cog" class="size-4 text-warning" />
                                                <span>System update available</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="justify-end card-actions">
                                        <a href="#" class="text-xs link link-primary">View all</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Theme Toggle --}}
                        <x-theme-toggle class="btn-ghost btn-square btn-sm" />

                        {{-- Page Actions Slot --}}
                        @if (isset($actions))
                            <div class="mx-1 divider divider-horizontal"></div>
                            {{ $actions }}
                        @endif
                    </div>
                </div>
            </header>

            {{-- Sub-header (optional) --}}
            @if (isset($subheader))
                <div
                    class="flex justify-between items-center px-4 py-2 text-sm border-b border-base-300 bg-base-200/50 lg:px-6">
                    {{ $subheader }}
                </div>
            @endif

            {{-- Main Content --}}
            <main class="flex-1 p-4 lg:p-6">
                {{-- Stats Cards --}}
                @if (isset($stats))
                    <div class="grid grid-cols-2 gap-4 mb-6 lg:grid-cols-4">
                        @foreach ($stats as $stat)
                            <div class="shadow-sm card bg-base-100">
                                <div class="p-4 card-body">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-2xl font-bold">{{ $stat['value'] }}</p>
                                            <p class="text-sm text-base-content/60">{{ $stat['label'] }}</p>
                                        </div>
                                        <div class="rounded-lg bg-{{ $stat['color'] ?? 'base-300' }}/10 p-2">
                                            <x-icon :name="$stat['icon']"
                                                class="size-5 text-{{ $stat['color'] ?? 'base-content' }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Content Card --}}
                <div class="shadow-sm card bg-base-100">
                    {{ $slot }}
                </div>
            </main>

            {{-- Footer --}}
            <footer class="p-4 border-t footer footer-center border-base-300 bg-base-100 text-base-content/60">
                <aside class="flex flex-col gap-2 justify-between items-center w-full text-xs sm:flex-row">
                    <p>&copy; {{ date('Y') }} Karnoweb v{{ config('app.version', '1.0.0') }}</p>
                    <nav class="flex gap-4">
                        <a href="#" class="link link-hover">Support</a>
                    </nav>
                </aside>
            </footer>
        </x-slot:content>
    </x-main>

    @include('components.layouts.shared.shared')
</body>

</html>
