@php
    use App\Helpers\Constants;

    // Get container size from session, default to 'container'
    $containerSize = session('container_size', 'container');
    // Determine fullWidth: prioritize input parameter if set, otherwise use session container size
    // Check if $fullWidth variable exists in the view scope
    $isFullWidth = isset($fullWidth) ? (bool) $fullWidth : $containerSize === 'fluid';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">

@include('components.layouts.shared.head')

<body class="flex flex-col min-h-screen bg-base-200" x-data></body>

<x-main :full-width="1">

    {{-- Modern Sidebar --}}
    {{-- Force all children to always use dark mode by wrapping in a "dark" div --}}
    <x-slot:sidebar drawer="main-drawer" collapsible
        class="border-none backdrop-blur-md dark no-scrollbar bg-base-100" data-theme="dark" collapse-icon="o-bars-3"
        collapse-text="بستن">
        {{-- sidebar content starts here --}}

        {{-- Brand Header with Gradient --}}
        <div class="sticky top-0 z-10 h-[64px] bg-base-100">
            <div class="p-2 hidden-when-collapsed">
                <x-button class="text-start btn-ghost btn-block" :link="route('admin.dashboard')" icon="o-home" label="Karnoweb" />
            </div>
            <div class="hidden w-full h-full display-when-collapsed">
                <div class="flex items-center w-full h-full">
                    <x-button class="flex justify-center items-center mx-auto w-10 h-10 rounded-xl btn-ghost" :link="route('admin.dashboard')">
                        <x-icon name="o-cube" class="w-6 h-6 text-primary" />
                    </x-button>
                </div>
            </div>
        </div>

        {{-- Navigation Menu with Modern Styling --}}
        <x-menu activate-by-route class="flex flex-col">
            <div class="overflow-y-auto flex-1 space-y-2">
                @foreach ($navbarMenu ?? [] as $menu)
                    @if (Arr::has($menu, 'sub_menu'))
                        @if (Arr::get($menu, 'access', true))
                            <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')">
                                @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                    @if (Arr::get($subMenu, 'access', true))
                                        <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')"
                                                     :no-wire-navigate="Arr::get($subMenu, 'disable_navigate', false)"
                                            :badge="Arr::get($subMenu, 'badge')" :badge-classes="Arr::get(
                                                $subMenu,
                                                'badge_classes',
                                                'float-left badge-info badge-dash',
                                            )" :link="route(
                                                Arr::get($subMenu, 'route_name'),
                                                Arr::get($subMenu, 'params', []),
                                            )" />
                                    @endif
                                @endforeach
                            </x-menu-sub>
                        @endif
                    @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                        <x-menu-separator :title="Arr::get($menu, 'title')" class="my-4" />
                    @else
                        @if (Arr::get($menu, 'access', true))
                            <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :badge="Arr::get($menu, 'badge')"
                                         :no-wire-navigate="Arr::get($menu, 'disable_navigate', false)"
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
        <livewire:admin.shared.header nav_class="bg-base-100 shadow" />
        {{-- Page Content with Glass Effect --}}
        <div @class([
            'flex flex-col flex-1',
            'mb-4 px-4 sm:px-6 lg:px-8' => !$isFullWidth,
            'px-0' => $isFullWidth,
        ])>
            <div @class([
                'flex flex-col flex-1',
                'container mx-auto' => !$isFullWidth,
                'container-fluid' => $isFullWidth,
                'px-4' => $containerSize === 'fluid' && !(isset($fullWidth) && $fullWidth),
            ])>
                {{ $slot }}
            </div>
        </div>
    </x-slot:content>
</x-main>

@include('components.layouts.shared.shared')
</body>

</html>
