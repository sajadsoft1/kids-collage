@php
    use App\Helpers\Constants;
    use App\Enums\UserTypeEnum;

    $isEmployee = auth()->user()?->type === UserTypeEnum::EMPLOYEE;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'fa' ? 'rtl' : 'ltr' }}">

@include('components.layouts.shared.head')

<body class="flex flex-col min-h-screen bg-base-200" x-data>

    <x-main full-width>

        @if ($isEmployee)
            {{-- Sidebar: Only for Employees --}}
            <x-slot:sidebar drawer="main-drawer" collapsible
                class="border-none backdrop-blur-md no-scrollbar bg-base-200">

                {{-- Brand Header --}}
                <div class="sticky top-0 z-10 border-none h-[64px]">
                    <div class="p-2 hidden-when-collapsed">
                        <div class="flex gap-3 items-center">
                            <div class="flex justify-center items-center w-10 h-10 rounded-xl">
                                <x-icon name="o-cube" class="w-6 h-6 text-primary" />
                            </div>
                            <div>
                                <h1 class="text-xl font-bold">Karnoweb</h1>
                                <p class="text-sm text-base-content/70">Admin Dashboard</p>
                            </div>
                        </div>
                    </div>
                    <div class="hidden w-full h-full display-when-collapsed">
                        <div class="flex items-center w-full h-full">
                            <div class="flex justify-center items-center mx-auto w-10 h-10 rounded-xl">
                                <x-icon name="o-cube" class="w-6 h-6 text-primary" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navigation Menu --}}
                <x-menu activate-by-route class="flex flex-col">
                    <div class="overflow-y-auto flex-1 space-y-2">
                        @foreach ($navbarMenu ?? [] as $menu)
                            @if (Arr::has($menu, 'sub_menu'))
                                @if (Arr::get($menu, 'access', true))
                                    <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')">
                                        @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                            @if (Arr::get($subMenu, 'access', true))
                                                <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')"
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
                                    <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')"
                                        :badge="Arr::get($menu, 'badge')" :badge-classes="Arr::get($menu, 'badge_classes', 'float-left')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))" />
                                @endif
                            @endif
                        @endforeach
                    </div>
                </x-menu>
            </x-slot:sidebar>
        @endif

        {{-- Main Content Area --}}
        <x-slot:content class="!p-0 flex flex-col flex-1 min-h-0">
            {{-- Header with horizontal menu for non-employees --}}
            <livewire:admin.shared.header nav_class="bg-base-200 border-none" :show-menu="!$isEmployee" />

            {{-- Page Content --}}
            <div @class([
                'flex flex-col flex-1',
                'mb-4 px-4 sm:px-6 lg:px-8' => !isset($fullWidth) || !$fullWidth,
                'px-0' => isset($fullWidth) && $fullWidth,
            ])>
                <div @class([
                    'flex flex-col flex-1',
                    'container mx-auto' => !isset($fullWidth) || !$fullWidth,
                    'container-fluid' => isset($fullWidth) && $fullWidth,
                ])>
                    {{ $slot }}
                </div>
            </div>
        </x-slot:content>
    </x-main>

    @include('components.layouts.shared.shared')
</body>

</html>
