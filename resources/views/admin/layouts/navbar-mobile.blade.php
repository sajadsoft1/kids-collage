<div x-show="!isDesktop && open" class="relative z-[60] lg:hidden"
    x-description="Off-canvas menu for mobile, show/hide based on off-canvas menu state." x-ref="dialog" aria-modal="true">

    <div x-show="!isDesktop && open" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 z-40"
        x-description="Off-canvas menu backdrop, show/hide based on off-canvas menu state." @click="closeSidebar()"
        aria-hidden="true">
    </div>


    <div class="fixed left-0 right-0 flex z-[60]">

        <div x-show="!isDesktop && open" x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="ltr:-translate-x-full rtl:translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="ltr:-translate-x-full rtl:translate-x-full"
            x-description="Off-canvas menu, show/hide based on off-canvas menu state."
            class="relative me-16 flex w-full max-w-xs flex-1 z-[60]" @click.away="open = false">

            <div x-show="!isDesktop && open" x-transition:enter="ease-in-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-description="Close button, show/hide based on off-canvas menu state."
                class="ltr:absolute ltr:left-full rtl:absolute rtl:right-full flex w-16 justify-center pt-5">
                <button type="button"
                    class="-m-2.5 p-2.5 rounded-lg text-base-content/70 hover:text-base-content hover:bg-base-200 dark:hover:bg-base-300 transition-colors"
                    @click="closeSidebar()" aria-label="Close sidebar">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true" data-slot="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div
                class="flex grow flex-col gap-y-5 overflow-y-auto bg-base-300 dark:bg-base-100 px-6 pb-4 border-r border-base-content/10 relative z-[60]">
                <div class="flex h-16 shrink-0 items-center">
                    <img class="h-8 w-auto" src="{{ asset('logo_with_text_light.png') }}" alt="Your Company">
                </div>
                <x-menu activate-by-route active-bg-color="bg-primary text-white hover:text-white"
                    class="!p-0 text-base-content"
                    x-data="{
                        init() {
                            // Close mobile sidebar after navigation
                            const closeOnNavigate = () => {
                                const isMobile = window.matchMedia('(max-width: 1023px)').matches;
                                if (isMobile) {
                                    const bodyData = Alpine.$data(document.body);
                                    if (bodyData && bodyData.closeSidebar) {
                                        bodyData.closeSidebar();
                                    }
                                }
                            };

                            // Re-evaluate active states on route change
                            const updateActive = () => {
                                this.$nextTick(() => {
                                    this.$el.querySelectorAll('a').forEach(link => {
                                        const href = link.getAttribute('href');
                                        if (href && window.isRouteActive) {
                                            const isActive = window.isRouteActive(href, link.hasAttribute('data-exact'));
                                            link.classList.toggle('mary-active-menu', isActive);
                                        }
                                    });
                                });
                            };

                            // Listen for route changes
                            window.addEventListener('route-changed', () => {
                                updateActive();
                                closeOnNavigate();
                            });
                            if (typeof Livewire !== 'undefined') {
                                Livewire.hook('morph.updated', () => {
                                    setTimeout(() => {
                                        updateActive();
                                        closeOnNavigate();
                                    }, 100);
                                });
                            }
                            document.addEventListener('alpine:navigated', () => {
                                setTimeout(() => {
                                    updateActive();
                                    closeOnNavigate();
                                }, 100);
                            });
                        }
                    }">

                    @foreach ($navbarMenu as $menu)
                        @if (Arr::has($menu, 'sub_menu'))
                            <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')">
                                @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                    <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')" :badge="Arr::get($subMenu, 'badge')"
                                        :badge-classes="Arr::get($subMenu, 'badge_classes', 'float-left')" :link="route(
                                            Arr::get($subMenu, 'route_name'),
                                            Arr::get($subMenu, 'params', []),
                                        )"
                                        :data-exact="Arr::get($subMenu, 'exact', false) ? 'true' : null" />
                                @endforeach
                            </x-menu-sub>
                        @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                            <x-menu-separator :title="Arr::get($menu, 'title')" class="text-base-content/50" />
                        @else
                            <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :badge="Arr::get($menu, 'badge')"
                                :badge-classes="Arr::get($menu, 'badge_classes', 'float-left')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))"
                                :data-exact="Arr::get($menu, 'exact', false) ? 'true' : null" />
                        @endif
                    @endforeach
                </x-menu>
            </div>
        </div>

    </div>
</div>
