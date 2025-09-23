<div class="hidden lg:fixed lg:top-0 lg:bottom-0 lg:z-50 lg:flex lg:w-72 lg:flex-col bg-gray-900 dark:bg-base-300"
    x-show="isDesktop && open" x-bind:class="isDesktop && open ? '!lg:fixed' : '!lg:hidden'">
    <!-- Sidebar component, swap this element with another sidebar if you like -->
    <div class="flex grow flex-col gap-y-5 overflow-y-auto px-6 pb-4">
        <div class="flex h-16 shrink-0 items-center">
            <img class="h-8 w-auto" src="https://tailwindui.com/plus-assets/img/logos/mark.svg?color=indigo&amp;shade=500"
                alt="Your Company">
        </div>
        <x-menu activate-by-route active-bg-color="bg-gray-800 dark:bg-gray-800 text-white hover:text-white"
            class="!p-0 !text-white">

            @foreach ($navbarMenu as $menu)
                @if (Arr::has($menu, 'sub_menu'))
                    @if (Arr::get($menu, 'access', true))
                        <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')">
                            @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')" :badge="Arr::get($subMenu, 'badge')"
                                    :badge-classes="Arr::get($subMenu, 'badge_classes', 'float-left')" :link="route(
                                        Arr::get($subMenu, 'route_name'),
                                        Arr::get($subMenu, 'params', []),
                                    )" />
                            @endforeach
                        </x-menu-sub>
                    @endif
                @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                    <x-menu-separator :title="Arr::get($menu, 'title')" class="!text-gray-400" />
                @else
                    @if (Arr::get($menu, 'access', true))
                        <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :badge="Arr::get($menu, 'badge')"
                            :badge-classes="Arr::get($menu, 'badge_classes', 'float-left')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))" />
                    @endif
                @endif
            @endforeach

        </x-menu>
    </div>
</div>
