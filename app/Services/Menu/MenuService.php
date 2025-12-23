<?php

declare(strict_types=1);

namespace App\Services\Menu;

use App\Models\User;
use App\Services\Menu\Contracts\MenuProviderInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    public function __construct(
        private readonly MenuProviderInterface $menuProvider
    ) {}

    /** Check if route is active */
    public function isRouteActive(string $routeName, array $params = [], bool $exact = false): bool
    {
        if ( ! $routeName || ! request()->routeIs($routeName)) {
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
                if ( ! isset($currentParams[$key]) || $currentParams[$key] != $value) {
                    return false;
                }
            }
        }

        return true;
    }

    /** Get processed modules from menu */
    public function getModules(): array
    {
        $user = Auth::user();

        if ( ! $user) {
            return [];
        }

        $cacheKey = $this->getCacheKey($user);

        return Cache::remember($cacheKey, 3600, function () {
            $navbarMenu = $this->menuProvider->getMenu();

            return collect($navbarMenu)
                ->filter(fn ($menu) => Arr::get($menu, 'access', true))
                ->map(function ($menu, $index) {
                    $subMenu = Arr::get($menu, 'sub_menu', []);
                    $isDirectLink = empty($subMenu) && Arr::has($menu, 'route_name');

                    // If menu doesn't have sub_menu, create one with the menu itself for display
                    if ($isDirectLink) {
                        $subMenu = [
                            [
                                'icon' => Arr::get($menu, 'icon', 'o-cube'),
                                'params' => Arr::get($menu, 'params', []),
                                'exact' => Arr::get($menu, 'exact', false),
                                'title' => Arr::get($menu, 'title'),
                                'route_name' => Arr::get($menu, 'route_name'),
                                'access' => Arr::get($menu, 'access', true),
                            ],
                        ];
                    }

                    return [
                        'id' => 'module-' . $index,
                        'key' => 'module-' . $index,
                        'title' => Arr::get($menu, 'title'),
                        'icon' => Arr::get($menu, 'icon', 'o-cube'),
                        'sub_menu' => $subMenu,
                        'is_direct_link' => $isDirectLink,
                        'route_name' => Arr::get($menu, 'route_name'),
                        'params' => Arr::get($menu, 'params', []),
                        'exact' => Arr::get($menu, 'exact', false),
                    ];
                })
                ->values()
                ->toArray();
        });
    }

    /** Find active module based on current route */
    public function getActiveModuleData(): array
    {
        $modules = $this->getModules();
        $currentRoute = request()->route()?->getName();
        $currentParams = request()->route()?->parameters() ?? [];

        if ( ! $currentRoute) {
            return $this->getDefaultModuleData($modules);
        }

        // Build lookup map for O(1) route matching instead of O(n*m)
        $routeToModuleMap = $this->buildRouteToModuleMap($modules);

        // Check if current route matches any direct link
        if (isset($routeToModuleMap[$currentRoute]['type']) && $routeToModuleMap[$currentRoute]['type'] === 'direct') {
            $module = $routeToModuleMap[$currentRoute]['module'];
            $params = Arr::get($module, 'params', []);
            $exact = Arr::get($module, 'exact', false);

            if ($this->isRouteActive($currentRoute, $params, $exact)) {
                return [
                    'modules' => $modules,
                    'activeModuleKey' => null,
                    'defaultModule' => '',
                    'isDirectLinkActive' => true,
                ];
            }
        }

        // Check if current route matches any sub-menu
        if (isset($routeToModuleMap[$currentRoute]['type']) && $routeToModuleMap[$currentRoute]['type'] === 'submenu') {
            $module = $routeToModuleMap[$currentRoute]['module'];
            $subMenu = $routeToModuleMap[$currentRoute]['subMenu'];
            $params = Arr::get($subMenu, 'params', []);
            $exact = Arr::get($subMenu, 'exact', false);

            if ($this->isRouteActive($currentRoute, $params, $exact)) {
                return [
                    'modules' => $modules,
                    'activeModuleKey' => $module['key'],
                    'defaultModule' => $module['key'],
                    'isDirectLinkActive' => false,
                ];
            }
        }

        // No active route found, return default
        return $this->getDefaultModuleData($modules);
    }

    /** Build route to module lookup map for O(1) access */
    private function buildRouteToModuleMap(array $modules): array
    {
        $map = [];

        foreach ($modules as $module) {
            // Direct link modules
            if (Arr::get($module, 'is_direct_link', false)) {
                $routeName = Arr::get($module, 'route_name');
                if ($routeName) {
                    $map[$routeName] = [
                        'type' => 'direct',
                        'module' => $module,
                    ];
                }
            } else {
                // Sub-menu modules
                foreach (Arr::get($module, 'sub_menu', []) as $subMenu) {
                    if (Arr::get($subMenu, 'access', true)) {
                        $routeName = Arr::get($subMenu, 'route_name');
                        if ($routeName) {
                            $map[$routeName] = [
                                'type' => 'submenu',
                                'module' => $module,
                                'subMenu' => $subMenu,
                            ];
                        }
                    }
                }
            }
        }

        return $map;
    }

    /** Get default module data when no route is active */
    private function getDefaultModuleData(array $modules): array
    {
        $defaultModule = '';

        // Find first non-direct link module as default
        foreach ($modules as $module) {
            if ( ! Arr::get($module, 'is_direct_link', false)) {
                $defaultModule = $module['key'];

                break;
            }
        }

        return [
            'modules' => $modules,
            'activeModuleKey' => null,
            'defaultModule' => $defaultModule,
            'isDirectLinkActive' => false,
        ];
    }

    /** Get cache key for user menu */
    private function getCacheKey(User $user): string
    {
        // Include permissions hash to invalidate cache when permissions change
        $permissionHash = $this->getPermissionHash($user);

        return "menu_v2_{$user->id}_{$user->type->value}_{$permissionHash}";
    }

    /** Get permission hash for cache invalidation */
    private function getPermissionHash(User $user): string
    {
        // Get all permission IDs and sort them for consistent hash
        $permissionIds = $user->getAllPermissions()
            ->pluck('id')
            ->sort()
            ->values()
            ->toArray();

        // Create hash from permission IDs
        return md5(serialize($permissionIds));
    }

    /** Clear menu cache for user */
    public function clearCache(?User $user = null): void
    {
        $user ??= Auth::user();

        if ( ! $user instanceof User) {
            return;
        }

        Cache::forget($this->getCacheKey($user));
    }
}
