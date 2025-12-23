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

            if ($exact && !empty($params)) {
                $currentParams = request()->route()->parameters();
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
    <div class="relative group">
        <a href="{{ Arr::get($module, 'route_name') ? route(Arr::get($module, 'route_name'), Arr::get($module, 'params', [])) : '#' }}"
            wire:navigate @click="$store.sidebar.resetActiveModule()"
            class="flex justify-center items-center w-11 h-11 rounded-xl transition-all duration-200 {{ isRouteActive(Arr::get($module, 'route_name'), Arr::get($module, 'params', []), Arr::get($module, 'exact', false)) ? 'bg-primary text-primary-content shadow-lg shadow-primary/30' : 'text-base-content/60 hover:bg-base-200 dark:hover:bg-base-300 hover:text-base-content' }}"
            :aria-label="$module['title']"
            aria-current="{{ isRouteActive(Arr::get($module, 'route_name'), Arr::get($module, 'params', []), Arr::get($module, 'exact', false)) ? 'page' : 'false' }}">
            <x-icon name="{{ $module['icon'] }}" class="w-5 h-5" />
        </a>
        <div
            class="absolute top-1/2 -translate-y-1/2 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity bg-base-200 dark:bg-base-300 text-base-content text-xs py-1.5 px-3 rounded-lg whitespace-nowrap z-[70] shadow-lg right-full mr-2">
            {{ $module['title'] }}
        </div>
    </div>
@else
    <div class="relative group">
        <button @click="$store.sidebar.openMenu('{{ $module['key'] }}')"
            class="flex justify-center items-center w-11 h-11 rounded-xl transition-all duration-200"
            :class="$store.sidebar.activeModule === '{{ $module['key'] }}' ?
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
