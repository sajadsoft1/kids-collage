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

<body x-data>
    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden h-12">
        <x-slot:brand>
            <x-icon name="o-cube" class="w-6 h-6 text-primary" />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>
    {{-- END NAVBAR mobile only --}}


    <x-main full-width class="aaa" active-bg-color="">


        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible
            class="no-scrollbar bg-base-100 border-e border-base-content/10">
            {{-- Using the reusable collapsible header component --}}
            <x-collapsible-header expanded-title="Karnoweb" expanded-subtitle="Admin Dashboard" collapsed-text="Admin"
                collapsed-icon="o-cube" variant="icon" />

            <x-menu activate-by-route class="!p-0 flex flex-col">

                {{-- Menu Items --}}
                <div class="flex-1 overflow-y-auto">
                    @foreach ($navbarMenu as $menu)
                        @if (Arr::has($menu, 'sub_menu'))
                            @if (Arr::get($menu, 'access', true))
                                <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" class="">
                                    @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                        <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')"
                                            :badge="Arr::get($subMenu, 'badge')" :badge-classes="Arr::get($subMenu, 'badge_classes', 'float-left')" :link="route(
                                                Arr::get($subMenu, 'route_name'),
                                                Arr::get($subMenu, 'params', []),
                                            )" class="" />
                                    @endforeach
                                </x-menu-sub>
                            @endif
                        @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                            <x-menu-separator :title="Arr::get($menu, 'title')" class="" />
                        @else
                            @if (Arr::get($menu, 'access', true))
                                <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :badge="Arr::get($menu, 'badge')"
                                    :badge-classes="Arr::get($menu, 'badge_classes', 'float-left')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))" class="" />
                            @endif
                        @endif
                    @endforeach
                </div>

            </x-menu>

        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content class="!pt-0 overflow-y-auto max-h-[calc(100vh)] bg-base-300 rounded-4xl p-10">
            {{ $slot }}
        </x-slot:content>
    </x-main>
    {{-- MAIN --}}


    {{-- Toast --}}
    <x-toast position="toast-button toast-end" />

    {{-- Spotlight Component --}}
    <x-spotlight shortcut="meta.k" search-text="Search for users, blogs, categories, or actions..."
        no-results-text="No results found." />

    @livewireScripts
    @livewireCalendarScripts
</body>

</html>
