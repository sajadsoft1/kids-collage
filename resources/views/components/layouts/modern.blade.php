<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script src="/assets/js/tinymce/tinymce.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gradient-to-br from-base-200 via-base-100 to-base-300 min-h-screen" x-data>
    {{-- Background Decorations --}}
    <div class="fixed left-0 right-0 top-0 bottom-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-secondary/10 rounded-full blur-3xl"></div>
    </div>

    {{-- Mobile Navigation --}}
    <x-nav sticky class="lg:hidden h-16 bg-base-100/80 backdrop-blur-md border-b border-base-content/10 shadow-lg">
        <x-slot:brand>
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 bg-gradient-to-r from-primary to-secondary rounded-lg flex items-center justify-center">
                    <x-icon name="o-cube" class="w-5 h-5 text-white" />
                </div>
                <span class="text-lg font-bold">Karnoweb</span>
            </div>
        </x-slot:brand>
        <x-slot:actions>
            <div class="flex items-center gap-2">
                <x-theme-toggle />
                <label for="main-drawer" class="lg:hidden">
                    <x-icon name="o-bars-3" class="w-6 h-6 cursor-pointer" />
                </label>
            </div>
        </x-slot:actions>
    </x-nav>

    <x-main full-width class="min-h-screen" active-bg-color="">

        {{-- Modern Sidebar --}}
        <x-slot:sidebar drawer="main-drawer" collapsible
            class="no-scrollbar bg-base-100/80 backdrop-blur-md border-e border-base-content/10 shadow-xl">

            {{-- Brand Header with Gradient --}}
            <div
                class="sticky top-0 bg-gradient-to-r from-primary/10 to-secondary/10 p-6 z-10 border-b border-base-content/10">
                <div class="hidden-when-collapsed">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-primary to-secondary rounded-xl flex items-center justify-center">
                            <x-icon name="o-cube" class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <h1
                                class="text-xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                                Karnoweb</h1>
                            <p class="text-sm text-base-content/70">Admin Dashboard</p>
                        </div>
                    </div>
                </div>
                <div class="display-when-collapsed hidden text-center">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-primary to-secondary rounded-xl flex items-center justify-center mx-auto">
                        <x-icon name="o-cube" class="w-6 h-6 text-white" />
                    </div>
                </div>
            </div>

            {{-- Navigation Menu with Modern Styling --}}
            <x-menu activate-by-route class="!p-0 flex flex-col">
                <div class="flex-1 overflow-y-auto p-4 space-y-2">
                    @foreach ($navbarMenu ?? [] as $menu)
                        @if (Arr::has($menu, 'sub_menu'))
                            @if (Arr::get($menu, 'access', true))
                                <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" class="mb-2">
                                    @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                        <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')"
                                            :badge="Arr::get($subMenu, 'badge')" :badge-classes="Arr::get($subMenu, 'badge_classes', 'float-left')" :link="route(
                                                Arr::get($subMenu, 'route_name'),
                                                Arr::get($subMenu, 'params', []),
                                            )"
                                            class="rounded-xl hover:bg-gradient-to-r hover:from-primary/10 hover:to-secondary/10" />
                                    @endforeach
                                </x-menu-sub>
                            @endif
                        @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                            <x-menu-separator :title="Arr::get($menu, 'title')" class="my-4" />
                        @else
                            @if (Arr::get($menu, 'access', true))
                                <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :badge="Arr::get($menu, 'badge')"
                                    :badge-classes="Arr::get($menu, 'badge_classes', 'float-left')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))"
                                    class="rounded-xl hover:bg-gradient-to-r hover:from-primary/10 hover:to-secondary/10 mb-1" />
                            @endif
                        @endif
                    @endforeach
                </div>
            </x-menu>

            {{-- User Profile Section with Modern Design --}}
            <div class="p-4 border-t border-base-content/10">
                <x-dropdown>
                    <x-slot:trigger>
                        <div
                            class="flex items-center gap-3 p-3 rounded-xl hover:bg-gradient-to-r hover:from-primary/10 hover:to-secondary/10 cursor-pointer transition-all duration-200">
                            <x-avatar src="{{ auth()->user()->avatar ?? '' }}" class="w-10 h-10 ring-2 ring-primary/20">
                                <x-icon name="o-user" class="w-6 h-6" />
                            </x-avatar>
                            <div class="hidden-when-collapsed">
                                <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-base-content/70">{{ auth()->user()->email }}</div>
                            </div>
                            <x-icon name="o-chevron-down" class="w-4 h-4 hidden-when-collapsed" />
                        </div>
                    </x-slot:trigger>
                    <x-menu>
                        <x-menu-item title="Profile" icon="o-user" link="{{ route('admin.app.profile') }}" />
                        <x-menu-item title="Settings" icon="o-cog-6-tooth" link="{{ route('admin.setting') }}" />
                        <x-menu-separator />
                        <x-menu-item title="Logout" icon="o-arrow-right-on-rectangle"
                            link="{{ route('admin.auth.logout') }}" />
                    </x-menu>
                </x-dropdown>
            </div>
        </x-slot:sidebar>

        {{-- Main Content Area --}}
        <x-slot:content class="!pt-0">
            {{-- Modern Header Bar --}}
            <div
                class="sticky top-0 z-40 bg-base-100/80 backdrop-blur-md border-b border-base-content/10 px-6 py-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <h2
                            class="text-2xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                            {{ $title ?? 'Dashboard' }}</h2>
                        @if (isset($breadcrumbs))
                            <div class="flex items-center gap-2 text-sm text-base-content/70">
                                @foreach ($breadcrumbs as $breadcrumb)
                                    <span>{{ $breadcrumb }}</span>
                                    @if (!$loop->last)
                                        <x-icon name="o-chevron-right" class="w-4 h-4" />
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Theme Switcher for Desktop --}}
                        <div class="hidden lg:block">
                            <x-theme-toggle />
                        </div>
                        {{-- Notifications with Badge --}}
                        <x-dropdown>
                            <x-slot:trigger>
                                <x-button icon="o-bell" class="btn-circle btn-ghost relative">
                                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-error rounded-full animate-pulse">
                                    </div>
                                </x-button>
                            </x-slot:trigger>
                            <x-menu>
                                <x-menu-item title="New message received" icon="o-envelope" />
                                <x-menu-item title="System update available" icon="o-cog" />
                            </x-menu>
                        </x-dropdown>
                        {{-- Search with Modern Input --}}
                        <x-input icon="o-magnifying-glass" placeholder="Search..." class="w-64 hidden lg:block" />
                    </div>
                </div>
            </div>

            {{-- Page Content with Glass Effect --}}
            <div class="p-6">
                <div class="bg-base-100/50 backdrop-blur-sm rounded-2xl p-6 shadow-xl border border-base-content/10">
                    {{ $slot }}
                </div>
            </div>
        </x-slot:content>
    </x-main>

    {{-- Toast Notifications --}}
    <x-toast position="toast-button toast-end" />

    {{-- Spotlight Search --}}
    <x-spotlight shortcut="meta.k" search-text="Search for users, blogs, categories, or actions..."
        no-results-text="No results found." />

    @livewireScripts
    @livewireCalendarScripts
</body>

</html>
