@props(['module'])

@php
    use Illuminate\Support\Arr;

    /**
     * Check if route is active
     */
    if ( ! function_exists('isRouteActive')) {
        function isRouteActive(string $routeName, array $params = [], bool $exact = false): bool
        {
            if ( ! $routeName || ! request()->routeIs($routeName)) {
                return false;
            }

            if ($exact && ! empty($params)) {
                $currentParams = request()->route()->parameters();
                foreach ($params as $key => $value) {
                    if ( ! isset($currentParams[$key]) || $currentParams[$key] != $value) {
                        return false;
                    }
                }
            }

            return true;
        }
    }
@endphp

@if (Arr::get($module, 'is_direct_link', false))
    <a href="{{ Arr::get($module, 'route_name') ? route(Arr::get($module, 'route_name'), Arr::get($module, 'params', [])) : '#' }}"
        wire:navigate @click="$store.sidebar.resetActiveModule()"
        class="flex justify-center items-center w-11 h-11 rounded-xl transition-all {{ isRouteActive(Arr::get($module, 'route_name'), Arr::get($module, 'params', []), Arr::get($module, 'exact', false)) ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
        title="{{ $module['title'] }}">
        <x-icon name="{{ $module['icon'] }}" class="w-5 h-5" />
    </a>
@else
    <button @click="$store.sidebar.openMenu('{{ $module['key'] }}')"
        class="flex justify-center items-center w-11 h-11 rounded-xl transition-all"
        :class="$store.sidebar.activeModule === '{{ $module['key'] }}' ? 'bg-blue-600 text-white' :
            'text-slate-400 hover:bg-slate-800 hover:text-white'"
        title="{{ $module['title'] }}">
        <x-icon name="{{ $module['icon'] }}" class="w-5 h-5" />
    </button>
@endif
