<?php

declare(strict_types=1);

namespace App\Services\Menu;

use App\View\Composers\NavbarComposer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    public function __construct(
        private readonly NavbarComposer $navbarComposer
    ) {}

    /** Check if route is active */
    public function isRouteActive(string $routeName, array $params = [], bool $exact = false): bool
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

    /** Get processed modules from menu */
    public function getModules(): array
    {
        $user = Auth::user();

        if ( ! $user) {
            return [];
        }

        $cacheKey = $this->getCacheKey($user);

        return Cache::remember($cacheKey, 3600, function () {
            $navbarMenu = $this->navbarComposer->getMenu();

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
        $activeModuleKey = null;
        $isDirectLinkActive = false;

        // First, check if current route is a direct link
        foreach ($modules as $module) {
            if (Arr::get($module, 'is_direct_link', false)) {
                $routeName = Arr::get($module, 'route_name');
                $params = Arr::get($module, 'params', []);
                $exact = Arr::get($module, 'exact', false);
                if ($routeName && $this->isRouteActive($routeName, $params, $exact)) {
                    $isDirectLinkActive = true;

                    break;
                }
            }
        }

        // If not a direct link, find active module from sub_menus
        if ( ! $isDirectLinkActive) {
            foreach ($modules as $module) {
                // Skip direct link modules - they don't open level 2 menu
                if (Arr::get($module, 'is_direct_link', false)) {
                    continue;
                }

                // Check sub_menu items
                foreach (Arr::get($module, 'sub_menu', []) as $subMenu) {
                    if (Arr::get($subMenu, 'access', true)) {
                        $routeName = Arr::get($subMenu, 'route_name');
                        $params = Arr::get($subMenu, 'params', []);
                        $exact = Arr::get($subMenu, 'exact', false);
                        if ($routeName && $this->isRouteActive($routeName, $params, $exact)) {
                            $activeModuleKey = $module['key'];

                            break 2;
                        }
                    }
                }
            }
        }

        // Set default active module
        $defaultModule = $activeModuleKey;
        if ($defaultModule === null && ! $isDirectLinkActive) {
            // Only set default to first non-direct link module if no direct link is active
            foreach ($modules as $module) {
                if ( ! Arr::get($module, 'is_direct_link', false)) {
                    $defaultModule = $module['key'];

                    break;
                }
            }
        }
        $defaultModule ??= '';

        return [
            'modules' => $modules,
            'activeModuleKey' => $activeModuleKey,
            'defaultModule' => $defaultModule,
            'isDirectLinkActive' => $isDirectLinkActive,
        ];
    }

    /** Get cache key for user menu */
    private function getCacheKey($user): string
    {
        return "menu_{$user->id}_{$user->type->value}";
    }

    /** Clear menu cache for user */
    public function clearCache($user = null): void
    {
        $user ??= Auth::user();

        if ( ! $user) {
            return;
        }

        Cache::forget($this->getCacheKey($user));
    }
}
