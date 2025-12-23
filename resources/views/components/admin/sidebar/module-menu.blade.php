@props(['module'])

@php
    use Illuminate\Support\Arr;

    /**
     * Check if route is active
     */
    if (!function_exists('isRouteActive')) {
        function isRouteActive(string $routeName, array $params = [], bool $exact = false): bool
        {
            if (!$routeName || !request()->routeIs($routeName)) {
                return false;
            }

            if ($exact) {
                $currentParams = request()->route()->parameters();

                // If exact is true and params is empty, route should have no parameters
                if (empty($params)) {
                    return empty($currentParams);
                }

                // If exact is true and params is not empty, check exact match
                if (count($params) !== count($currentParams)) {
                    return false;
                }

                foreach ($params as $key => $value) {
                    if (!isset($currentParams[$key]) || $currentParams[$key] != $value) {
                        return false;
                    }
                }
            }

            return true;
        }
    }
@endphp

@if (!Arr::get($module, 'is_direct_link', false))
    <div x-show="$store.sidebar.activeModule === '{{ $module['key'] }}'" class="space-y-1" x-data="{
        menuStates: {},
        checkMenuActive(menuKey, url, exact) {
            const currentPath = window.location.pathname;
            const menuPath = new URL(url, window.location.origin).pathname;
            let isActive = false;
            if (exact) {
                isActive = currentPath === menuPath || currentPath === menuPath + '/';
            } else {
                isActive = currentPath.startsWith(menuPath);
            }
            this.menuStates[menuKey] = isActive;
            return isActive;
        },
        initMenuStates() {
            @foreach (Arr::get($module, 'sub_menu', []) as $subMenu)
                    @if (Arr::get($subMenu, 'access', true))
                        @php
                            $routeName = Arr::get($subMenu, 'route_name');
                            $params = Arr::get($subMenu, 'params', []);
                            $exact = Arr::get($subMenu, 'exact', false);
                            $menuKey = $routeName ? str_replace('.', '_', $routeName) : 'menu_' . $loop->index;
                            $menuUrl = $routeName ? route($routeName, $params) : '#';
                        @endphp
                        this.checkMenuActive('{{ $menuKey }}', '{{ $menuUrl }}', {{ $exact ? 'true' : 'false' }});
                    @endif @endforeach
        }
    }"
        x-init="initMenuStates();
        document.addEventListener('alpine:navigated', () => {
            setTimeout(() => initMenuStates(), 100);
        });
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', () => {
                setTimeout(() => initMenuStates(), 100);
            });
        }">
        @foreach (Arr::get($module, 'sub_menu', []) as $subMenu)
            @if (Arr::get($subMenu, 'access', true))
                @php
                    $routeName = Arr::get($subMenu, 'route_name');
                    $params = Arr::get($subMenu, 'params', []);
                    $exact = Arr::get($subMenu, 'exact', false);
                    $menuKey = $routeName ? str_replace('.', '_', $routeName) : 'menu_' . $loop->index;
                    $menuUrl = $routeName ? route($routeName, $params) : '#';
                    $initialActive = $routeName ? isRouteActive($routeName, $params, $exact) : false;
                @endphp
                @if ($exact)
                    <a href="{{ $menuUrl }}" wire:navigate
                        @click="$store.sidebar.setActiveMenu('{{ $menuKey }}')"
                        class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200"
                        :class="menuStates['{{ $menuKey }}'] ?
                            'bg-primary text-primary-content shadow-lg shadow-primary/20' :
                            'text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content'"
                        :aria-current="menuStates['{{ $menuKey }}'] ? 'page' : 'false'">
                    @else
                        <a href="{{ $menuUrl }}" wire:navigate
                            @click="$store.sidebar.setActiveMenu('{{ $menuKey }}')"
                            wire:current="bg-primary text-primary-content shadow-lg shadow-primary/20"
                            class="flex gap-3 items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200"
                            :class="$store.sidebar.activeMenu === '{{ $menuKey }}' ?
                                'bg-primary text-primary-content shadow-lg shadow-primary/20' :
                                'text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content'"
                            aria-current="page">
                @endif
                <x-icon name="{{ Arr::get($subMenu, 'icon', 'o-cube') }}" class="w-5 h-5" />
                <span>{{ Arr::get($subMenu, 'title') }}</span>
                @if (Arr::get($subMenu, 'badge'))
                    <span
                        class="mr-auto px-2 py-0.5 text-xs font-medium rounded-full {{ Arr::get($subMenu, 'badge_classes', 'bg-primary/20 text-primary') }}">
                        {{ Arr::get($subMenu, 'badge') }}
                    </span>
                @endif
                </a>
            @endif
        @endforeach
    </div>
@endif
