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

<body class="bg-base-100" x-data>
    {{-- Top Navigation Bar --}}
    <x-nav sticky class="h-16 bg-base-100 border-b border-base-content/10 shadow-sm">
        <x-slot:brand>
            <div class="flex items-center gap-3">
                <x-icon name="o-cube" class="w-8 h-8 text-primary" />
                <span class="text-xl font-bold hidden sm:block">Karnoweb</span>
            </div>
        </x-slot:brand>
        <x-slot:actions>
            <div class="flex items-center gap-2">
                {{-- Search --}}
                <div class="hidden md:block">
                    <x-input icon="o-magnifying-glass" placeholder="Search..." class="w-64" />
                </div>
                {{-- Notifications --}}
                <x-dropdown>
                    <x-slot:trigger>
                        <x-button icon="o-bell" class="btn-circle btn-ghost relative">
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-error rounded-full"></div>
                        </x-button>
                    </x-slot:trigger>
                    <x-menu>
                        <x-menu-item title="New message received" icon="o-envelope" />
                        <x-menu-item title="System update available" icon="o-cog" />
                        <x-menu-separator />
                        <x-menu-item title="View all notifications" icon="o-eye" />
                    </x-menu>
                </x-dropdown>
                {{-- Theme Switcher --}}
                <x-theme-toggle />
                {{-- User Menu --}}
                <x-dropdown>
                    <x-slot:trigger>
                        <div class="flex items-center gap-2 p-2 rounded-lg hover:bg-base-200 cursor-pointer">
                            <x-avatar src="{{ auth()->user()->avatar ?? '' }}" class="w-8 h-8">
                                <x-icon name="o-user" class="w-4 h-4" />
                            </x-avatar>
                            <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name }}</span>
                            <x-icon name="o-chevron-down" class="w-4 h-4" />
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
        </x-slot:actions>
    </x-nav>

    <div class="flex min-h-screen">
        {{-- Compact Sidebar --}}
        <div class="w-16 bg-base-200 border-r border-base-content/10 flex flex-col items-center py-4 space-y-4">
            {{-- Brand Icon --}}
            <div class="p-2">
                <x-icon name="o-cube" class="w-8 h-8 text-primary" />
            </div>

            {{-- Navigation Icons --}}
            <div class="flex-1 flex flex-col items-center space-y-2">
                @foreach ($navbarMenu ?? [] as $menu)
                    @if (Arr::get($menu, 'access', true))
                        @if (Arr::has($menu, 'sub_menu'))
                            <x-dropdown>
                                <x-slot:trigger>
                                    <x-button :icon="Arr::get($menu, 'icon')" class="btn-circle btn-ghost w-10 h-10" />
                                </x-slot:trigger>
                                <x-menu>
                                    @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                        <x-menu-item :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')" :link="route(
                                            Arr::get($subMenu, 'route_name'),
                                            Arr::get($subMenu, 'params', []),
                                        )" />
                                    @endforeach
                                </x-menu>
                            </x-dropdown>
                        @else
                            <x-button :icon="Arr::get($menu, 'icon')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))" class="btn-circle btn-ghost w-10 h-10" />
                        @endif
                    @endif
                @endforeach
            </div>

            {{-- Bottom Actions --}}
            <div class="flex flex-col items-center space-y-2">
                <x-button icon="o-cog-6-tooth" class="btn-circle btn-ghost w-10 h-10" />
                <x-button icon="o-question-mark-circle" class="btn-circle btn-ghost w-10 h-10" />
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">
            {{-- Page Header --}}
            <div class="bg-base-100 px-6 py-4 border-b border-base-content/10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-base-content">{{ $title ?? 'Dashboard' }}</h1>
                        @if (isset($subtitle))
                            <p class="text-base-content/70 mt-1">{{ $subtitle }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        @if (isset($actions))
                            {{ $actions }}
                        @endif
                        <x-button icon="o-plus" label="New" class="btn-primary" />
                    </div>
                </div>
            </div>

            {{-- Page Content --}}
            <div class="flex-1 p-6">
                {{ $slot }}
            </div>
        </div>
    </div>

    {{-- Toast Notifications --}}
    <x-toast position="toast-button toast-end" />

    {{-- Spotlight Search --}}
    <x-spotlight shortcut="meta.k" search-text="Search for users, blogs, categories, or actions..."
        no-results-text="No results found." />

    @livewireScripts
    @livewireCalendarScripts
</body>

</html>
