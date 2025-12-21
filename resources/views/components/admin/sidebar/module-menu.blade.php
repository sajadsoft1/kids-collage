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

@if (!Arr::get($module, 'is_direct_link', false))
    <div x-show="$store.sidebar.activeModule === '{{ $module['key'] }}'" class="space-y-1">
        @foreach (Arr::get($module, 'sub_menu', []) as $subMenu)
            @if (Arr::get($subMenu, 'access', true))
                @php
                    $routeName = Arr::get($subMenu, 'route_name');
                    $params = Arr::get($subMenu, 'params', []);
                    $exact = Arr::get($subMenu, 'exact', false);
                    $isActive = $routeName ? isRouteActive($routeName, $params, $exact) : false;
                    $menuKey = $routeName ? str_replace('.', '_', $routeName) : 'menu_' . $loop->index;
                @endphp
                <a href="{{ $routeName ? route($routeName, $params) : '#' }}" wire:navigate
                    @click="$store.sidebar.setActiveMenu('{{ $menuKey }}')"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200"
                    :class="$store.sidebar.activeMenu === '{{ $menuKey }}' || {{ $isActive ? 'true' : 'false' }} ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-700/50 hover:text-white'"
                    :aria-current="{{ $isActive ? 'page' : 'false' }}">
                    <x-icon name="{{ Arr::get($subMenu, 'icon', 'o-cube') }}" class="w-5 h-5" />
                    <span>{{ Arr::get($subMenu, 'title') }}</span>
                    @if (Arr::get($subMenu, 'badge'))
                        <span
                            class="mr-auto px-2 py-0.5 text-xs font-medium rounded-full {{ Arr::get($subMenu, 'badge_classes', 'bg-blue-500/20 text-blue-400') }}">
                            {{ Arr::get($subMenu, 'badge') }}
                        </span>
                    @endif
                </a>
            @endif
        @endforeach
    </div>
@endif
