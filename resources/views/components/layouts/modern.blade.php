@php use App\Helpers\Constants; @endphp
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

<body class="flex flex-col min-h-screen bg-base-300" x-data>

    <x-main full-width>

        {{-- Modern Sidebar --}}
        <x-slot:sidebar drawer="main-drawer" collapsible
            class="shadow-xl backdrop-blur-md no-scrollbar bg-base-100/80 border-e border-base-content/10">

            {{-- Brand Header with Gradient --}}
            <div
                class="sticky top-0 z-10 bg-gradient-to-r border-b from-primary/10 to-secondary/10 border-base-content/10 h-[64px]">
                <div class="p-2 hidden-when-collapsed">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-10 h-10 bg-gradient-to-r rounded-xl from-primary to-secondary">
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
                            class="flex items-center justify-center w-10 h-10 mx-auto bg-gradient-to-r rounded-xl from-primary to-secondary">
                            <x-icon name="o-cube" class="w-6 h-6 text-white" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Menu with Modern Styling --}}
            <x-menu activate-by-route class="!p-0 flex flex-col">
                <div class="flex-1 space-y-2 overflow-y-auto">
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
        <x-slot:content class="!p-0 flex flex-col flex-1 min-h-0">
            {{-- Modern Header Bar --}}
            <livewire:admin.shared.header />
            {{-- Page Content with Glass Effect --}}
            <div @class([
                'flex flex-col flex-1',
                'mb-4 px-4 sm:px-6 lg:px-8' => !isset($fullWidth) || !$fullWidth,
                'px-0' => isset($fullWidth) && $fullWidth,
            ])>
                <div @class([
                    'flex flex-col flex-1 mx-auto',
                    'container' => !isset($fullWidth) || !$fullWidth,
                    'container-fluid' => isset($fullWidth) && $fullWidth,
                ])>
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
