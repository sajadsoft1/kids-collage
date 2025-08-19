<div x-show="open" class="relative z-50 lg:hidden"
    x-description="Off-canvas menu for mobile, show/hide based on off-canvas menu state." x-ref="dialog" aria-modal="true">

    <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80"
        x-description="Off-canvas menu backdrop, show/hide based on off-canvas menu state." aria-hidden="true">

    </div>


    <div class="fixed inset-0 flex">

        <div x-show="open" x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="ltr:-translate-x-full rtl:translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="ltr:-translate-x-full rtl:translate-x-full"
            x-description="Off-canvas menu, show/hide based on off-canvas menu state."
            class="relative me-16 flex w-full max-w-xs flex-1" @click.away="open = false">

            <div x-show="open" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                x-description="Close button, show/hide based on off-canvas menu state."
                class="ltr:absolute ltr:left-full rtl:absolute rtl:right-full flex w-16 justify-center pt-5">
                <button type="button" class="-m-2.5 p-2.5" @click="open = false">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" aria-hidden="true" data-slot="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div
                class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 dark:bg-base-100 px-6 pb-4 ring-1 ring-white/10">
                <div class="flex h-16 shrink-0 items-center">
                    <img class="h-8 w-auto"
                        src="https://tailwindui.com/plus-assets/img/logos/mark.svg?color=indigo&amp;shade=500"
                        alt="Your Company">
                </div>
                <x-menu activate-by-route active-bg-color="bg-gray-800 text-white" class="!p-0 !text-white">

                    @foreach ($navbarMenu as $menu)
                        @if (Arr::has($menu, 'sub_menu'))
                            <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')">
                                @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                    <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')" :badge="Arr::get($subMenu, 'badge')"
                                        :badge-classes="Arr::get($subMenu, 'badge_classes', 'float-left')" :link="route(
                                            Arr::get($subMenu, 'route_name'),
                                            Arr::get($subMenu, 'params', []),
                                        )" />
                                @endforeach
                            </x-menu-sub>
                        @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                            <x-menu-separator :title="Arr::get($menu, 'title')" class="!text-gray-400" />
                        @else
                            <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :badge="Arr::get($menu, 'badge')"
                                :badge-classes="Arr::get($menu, 'badge_classes', 'float-left')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))" />
                        @endif
                    @endforeach
                </x-menu>
            </div>
        </div>

    </div>
</div>
