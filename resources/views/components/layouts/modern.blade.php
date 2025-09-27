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

<body class="min-h-screen bg-base-300" x-data>

    {{-- Mobile Navigation --}}
    <x-nav sticky class="h-16 border-b shadow-lg backdrop-blur-md lg:hidden bg-base-100/80 border-base-content/10">
        <x-slot:brand>
            <div class="flex gap-3 items-center">
                <div
                    class="flex justify-center items-center w-8 h-8 bg-gradient-to-r rounded-lg from-primary to-secondary">
                    <x-icon name="o-cube" class="w-5 h-5 text-white" />
                </div>
                <span class="text-lg font-bold">Karnoweb</span>
            </div>
        </x-slot:brand>
        <x-slot:actions>
            <div class="flex gap-2 items-center">
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
            class="shadow-xl backdrop-blur-md no-scrollbar bg-base-100/80 border-e border-base-content/10">

            {{-- Brand Header with Gradient --}}
            <div
                class="sticky top-0 z-10 bg-gradient-to-r border-b from-primary/10 to-secondary/10 border-base-content/10 h-[64px]">
                <div class="p-2 hidden-when-collapsed">
                    <div class="flex gap-3 items-center">
                        <div
                            class="flex justify-center items-center w-10 h-10 bg-gradient-to-r rounded-xl from-primary to-secondary">
                            <x-icon name="o-cube" class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <h1
                                class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">
                                Karnoweb</h1>
                            <p class="text-sm text-base-content/70">Admin Dashboard</p>
                        </div>
                    </div>
                </div>
                <div class="hidden w-full h-full display-when-collapsed">
                    <div class="flex items-center w-full h-full">
                        <div
                            class="flex justify-center items-center mx-auto w-10 h-10 bg-gradient-to-r rounded-xl from-primary to-secondary">
                            <x-icon name="o-cube" class="w-6 h-6 text-white" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Menu with Modern Styling --}}
            <x-menu activate-by-route class="!p-0 flex flex-col">
                <div class="overflow-y-auto flex-1 space-y-2">
                    @foreach ($navbarMenu ?? [] as $menu)
                        @if (Arr::has($menu, 'sub_menu'))
                            @if (Arr::get($menu, 'access', true))
                                <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')">
                                    @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                        <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')"
                                            :badge="Arr::get($subMenu, 'badge')" :badge-classes="Arr::get($subMenu, 'badge_classes', 'float-left')" :link="route(
                                                Arr::get($subMenu, 'route_name'),
                                                Arr::get($subMenu, 'params', []),
                                            )" />
                                    @endforeach
                                </x-menu-sub>
                            @endif
                        @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                            <x-menu-separator :title="Arr::get($menu, 'title')" class="my-4" />
                        @else
                            @if (Arr::get($menu, 'access', true))
                                <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :badge="Arr::get($menu, 'badge')"
                                    :badge-classes="Arr::get($menu, 'badge_classes', 'float-left')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))" />
                            @endif
                        @endif
                    @endforeach
                </div>
            </x-menu>
        </x-slot:sidebar>

        {{-- Main Content Area --}}
        <x-slot:content class="!p-0">
            {{-- Modern Header Bar --}}
            <div
                class="sticky top-0 z-40 px-6 py-4 border-b backdrop-blur-md bg-base-100/80 border-base-content/10 h-[64px]">
                <div class="flex justify-between items-center">
                    <div class="flex gap-4 items-center">
                        <h2
                            class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">
                            {{ $title ?? 'Dashboard' }}</h2>
                        @if (isset($breadcrumbs))
                            <div class="flex gap-2 items-center text-sm text-base-content/70">
                                @foreach ($breadcrumbs as $breadcrumb)
                                    <span>{{ $breadcrumb }}</span>
                                    @if (!$loop->last)
                                        <x-icon name="o-chevron-right" class="w-4 h-4" />
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-3 items-center">
                        {{-- Theme Switcher for Desktop --}}
                        <div class="hidden lg:block">
                            <x-theme-toggle />
                        </div>
                        {{-- Notifications with Badge --}}
                        <x-dropdown>
                            <x-slot:trigger>
                                <x-button icon="o-bell" class="relative btn-circle btn-ghost">
                                    <div class="absolute -top-1 -right-1 w-3 h-3 rounded-full animate-pulse bg-error">
                                    </div>
                                </x-button>
                            </x-slot:trigger>
                            <x-menu>
                                <x-menu-item title="New message received" icon="o-envelope" />
                                <x-menu-item title="System update available" icon="o-cog" />
                            </x-menu>
                        </x-dropdown>

                    </div>
                </div>
            </div>

            {{-- Page Content with Glass Effect --}}
            <div class="p-6">
                <div class="container">
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
