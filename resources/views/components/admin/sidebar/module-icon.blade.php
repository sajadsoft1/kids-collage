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

@if (Arr::get($module, 'is_direct_link', false))
    @php
        $routeName = Arr::get($module, 'route_name');
        $params = Arr::get($module, 'params', []);
        $exact = Arr::get($module, 'exact', false);
        $isActive = $routeName ? isRouteActive($routeName, $params, $exact) : false;
    @endphp
    <div class="relative group">
        <a href="{{ $routeName ? route($routeName, $params) : '#' }}" wire:navigate
            @click="$store.sidebar.resetActiveModule()"
            class="flex justify-center items-center w-11 h-11 rounded-xl transition-all duration-200 {{ $isActive ? 'bg-primary text-primary-content shadow-lg shadow-primary/30' : 'text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content' }}"
            :aria-label="$module['title']" aria-current="{{ $isActive ? 'page' : 'false' }}">
            <x-icon name="{{ $module['icon'] }}" class="w-5 h-5" />
        </a>
        <div
            class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-base-200 dark:bg-base-300 text-base-content text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2">
            {{ $module['title'] }}
        </div>
    </div>
@else
    @php
        // Prepare sub_menu data for JavaScript
        $subMenusData = [];
        $subMenus = Arr::get($module, 'sub_menu', []);
        foreach ($subMenus as $subMenu) {
            if (Arr::get($subMenu, 'access', true)) {
                $routeName = Arr::get($subMenu, 'route_name');
                if ($routeName) {
                    try {
                        $subMenusData[] = [
                            'route' => $routeName,
                            'url' => route($routeName, Arr::get($subMenu, 'params', [])),
                            'exact' => Arr::get($subMenu, 'exact', false),
                        ];
                    } catch (\Exception $e) {
                        // Skip invalid routes
                    }
                }
            }
        }
        $subMenusJson = json_encode($subMenusData);

        // Check initial state
        $hasActiveSubMenu = false;
        foreach ($subMenus as $subMenu) {
            if (Arr::get($subMenu, 'access', true)) {
                $routeName = Arr::get($subMenu, 'route_name');
                $params = Arr::get($subMenu, 'params', []);
                $exact = Arr::get($subMenu, 'exact', false);
                if ($routeName && isRouteActive($routeName, $params, $exact)) {
                    $hasActiveSubMenu = true;
                    break;
                }
            }
        }
    @endphp
    <div class="relative group" x-data="{
        hasActiveSubMenu: {{ $hasActiveSubMenu ? 'true' : 'false' }},
        subMenus: {{ $subMenusJson }},
        checkActiveSubMenu() {
            const currentPath = window.location.pathname;
            this.hasActiveSubMenu = this.subMenus.some(subMenu => {
                const subMenuPath = new URL(subMenu.url, window.location.origin).pathname;
                if (subMenu.exact) {
                    return currentPath === subMenuPath || currentPath === subMenuPath + '/';
                } else {
                    return currentPath.startsWith(subMenuPath);
                }
            });
        }
    }" x-init="checkActiveSubMenu();
    document.addEventListener('alpine:navigated', () => {
        setTimeout(() => checkActiveSubMenu(), 100);
    });
    if (typeof Livewire !== 'undefined') {
        Livewire.hook('morph.updated', () => {
            setTimeout(() => checkActiveSubMenu(), 100);
        });
    }">
        <button @click="$store.sidebar.openMenu('{{ $module['key'] }}')"
            class="flex justify-center items-center w-11 h-11 rounded-xl transition-all duration-200"
            :class="hasActiveSubMenu || $store.sidebar.activeModule === '{{ $module['key'] }}' ?
                'bg-primary text-primary-content shadow-lg shadow-primary/30' :
                'text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content'"
            :aria-label="$module['title']" aria-haspopup="true">
            <x-icon name="{{ $module['icon'] }}" class="w-5 h-5" />
        </button>
        <div
            class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-base-200 dark:bg-base-300 text-base-content text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2">
            {{ $module['title'] }}
        </div>
    </div>
@endif
