@php
    use Illuminate\Support\Arr;
    $navbarMenu = $navbarMenu ?? [];
@endphp
<aside class="hidden lg:block lg:fixed lg:top-16 lg:bottom-0 lg:z-20 lg:w-72 bg-base-300 dark:bg-base-100 border-r border-base-content/10 transition-transform duration-300 ease-in-out"
    x-show="isDesktop && open" 
    x-cloak
    :class="document.documentElement.dir === 'rtl' 
        ? 'right-0 border-l' 
        : 'left-0 border-r'"
    x-bind:class="open && isDesktop 
        ? 'translate-x-0' 
        : (document.documentElement.dir === 'rtl' ? 'translate-x-full' : '-translate-x-full')"
    aria-label="Sidebar navigation"
    style="will-change: transform;">
    <!-- Sidebar component, swap this element with another sidebar if you like -->
    <div class="flex grow flex-col gap-y-5 overflow-y-auto px-6 pb-4">
        <div class="flex h-16 shrink-0 items-center">
            <img class="h-8 w-auto" src="{{ asset('logo_with_text_light.png') }}" alt="Your Company">
        </div>
        <x-menu activate-by-route active-bg-color="bg-primary text-white hover:text-white" class="!p-0 text-base-content"
            x-data="{
                init() {
                    // Re-evaluate active states on route change
                    const updateActive = () => {
                        this.$nextTick(() => {
                            // Force Alpine to re-evaluate active classes
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
                    window.addEventListener('route-changed', updateActive);
                    if (typeof Livewire !== 'undefined') {
                        Livewire.hook('morph.updated', () => setTimeout(updateActive, 100));
                    }
                    document.addEventListener('alpine:navigated', () => setTimeout(updateActive, 100));
                }
            }">

            @foreach ($navbarMenu as $menu)
                @if (Arr::has($menu, 'sub_menu'))
                    @if (Arr::get($menu, 'access', true))
                        @php
                            $menuKey = 'submenu_' . md5(Arr::get($menu, 'title', '') . Arr::get($menu, 'icon', ''));
                            $hasActiveSubmenu = false;
                            foreach (Arr::get($menu, 'sub_menu', []) as $subMenu) {
                                $routeName = Arr::get($subMenu, 'route_name');
                                if ($routeName && request()->routeIs($routeName)) {
                                    $hasActiveSubmenu = true;
                                    break;
                                }
                            }
                            $initialOpen = $hasActiveSubmenu || (session()->has('sidebar_submenu_' . $menuKey) && session('sidebar_submenu_' . $menuKey));
                        @endphp
                        <div x-data="{
                            menuKey: '{{ $menuKey }}',
                            init() {
                                // Watch for route changes to auto-open if active
                                const checkActive = () => {
                                    this.$nextTick(() => {
                                        const hasActive = Array.from(this.$el.querySelectorAll('a')).some(link => {
                                            const href = link.getAttribute('href');
                                            return href && window.isRouteActive && window.isRouteActive(href, link.hasAttribute('data-exact'));
                                        });
                                        
                                        if (hasActive) {
                                            // Find the details element and open it
                                            const details = this.$el.querySelector('details');
                                            if (details && !details.hasAttribute('open')) {
                                                details.setAttribute('open', '');
                                                localStorage.setItem('sidebar_' + this.menuKey, 'true');
                                            }
                                        }
                                    });
                                };
                                
                                window.addEventListener('route-changed', checkActive);
                                if (typeof Livewire !== 'undefined') {
                                    Livewire.hook('morph.updated', () => setTimeout(checkActive, 100));
                                }
                                document.addEventListener('alpine:navigated', () => setTimeout(checkActive, 100));
                                
                                // Initial check
                                checkActive();
                                
                                // Save state when submenu is toggled
                                const details = this.$el.querySelector('details');
                                if (details) {
                                    details.addEventListener('toggle', () => {
                                        localStorage.setItem('sidebar_' + this.menuKey, details.open.toString());
                                    });
                                    
                                    // Restore state from localStorage
                                    const savedState = localStorage.getItem('sidebar_' + this.menuKey);
                                    if (savedState === 'true' || {{ $initialOpen ? 'true' : 'false' }}) {
                                        details.setAttribute('open', '');
                                    }
                                }
                            }
                        }">
                            <x-menu-sub :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :open="$initialOpen">
                                @foreach (Arr::get($menu, 'sub_menu', []) as $subMenu)
                                    <x-menu-item :exact="Arr::get($subMenu, 'exact', false)" :title="Arr::get($subMenu, 'title')" :icon="Arr::get($subMenu, 'icon')" :badge="Arr::get($subMenu, 'badge')"
                                        :badge-classes="Arr::get($subMenu, 'badge_classes', 'float-left')" :link="route(
                                            Arr::get($subMenu, 'route_name'),
                                            Arr::get($subMenu, 'params', []),
                                        )"
                                        :data-exact="Arr::get($subMenu, 'exact', false) ? 'true' : null" />
                                @endforeach
                            </x-menu-sub>
                        </div>
                    @endif
                @elseif(Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator')
                    <x-menu-separator :title="Arr::get($menu, 'title')" class="text-base-content/50" />
                @else
                    @if (Arr::get($menu, 'access', true))
                        <x-menu-item :exact="Arr::get($menu, 'exact', false)" :title="Arr::get($menu, 'title')" :icon="Arr::get($menu, 'icon')" :badge="Arr::get($menu, 'badge')"
                            :badge-classes="Arr::get($menu, 'badge_classes', 'float-left')" :link="route(Arr::get($menu, 'route_name'), Arr::get($menu, 'params', []))"
                            :data-exact="Arr::get($menu, 'exact', false) ? 'true' : null" />
                    @endif
                @endif
            @endforeach

        </x-menu>
    </div>
</aside>
