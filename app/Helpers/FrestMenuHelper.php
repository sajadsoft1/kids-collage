<?php

declare(strict_types=1);

namespace App\Helpers;

use Exception;
use Illuminate\Support\Arr;

/**
 * Helper class for Frest sidebar menu processing
 * Pre-computes menu data to avoid repeated calculations in Blade templates
 */
class FrestMenuHelper
{
    /** Process menu items and pre-compute URLs and active states */
    public static function processMenu(array $navbarMenu): array
    {
        $currentPath = request()->path();

        return collect($navbarMenu)->map(function ($menu) use ($currentPath) {
            $processed = [
                'type' => Arr::get($menu, 'type'),
                'title' => Arr::get($menu, 'title'),
                'icon' => Arr::get($menu, 'icon'),
                'access' => Arr::get($menu, 'access', true),
                'badge' => Arr::get($menu, 'badge'),
                'badge_classes' => Arr::get($menu, 'badge_classes', 'bg-primary/20 text-primary'),
            ];

            // Handle separator
            if (Arr::has($menu, 'type') && Arr::get($menu, 'type') === 'seperator') {
                return $processed;
            }

            // Handle menu with submenu
            if (Arr::has($menu, 'sub_menu')) {
                $menuUrl = self::generateMenuUrl($menu);
                $subMenus = collect(Arr::get($menu, 'sub_menu', []))->map(function ($subMenu) use ($currentPath) {
                    return self::processSubMenu($subMenu, $currentPath);
                })->filter(fn ($subMenu) => Arr::get($subMenu, 'access', true))->values()->toArray();

                $hasActiveSubmenu = collect($subMenus)->contains(fn ($subMenu) => Arr::get($subMenu, 'isActive', false));

                return array_merge($processed, [
                    'url' => $menuUrl,
                    'sub_menu' => $subMenus,
                    'hasActiveSubmenu' => $hasActiveSubmenu,
                ]);
            }

            // Handle single menu item
            $menuUrl = self::generateMenuUrl($menu);
            $isActive = self::checkRouteActive($menuUrl, Arr::get($menu, 'exact', false), $currentPath);

            return array_merge($processed, [
                'url' => $menuUrl,
                'isActive' => $isActive,
            ]);
        })->filter(fn ($menu) => Arr::get($menu, 'access', true))->values()->toArray();
    }

    /** Generate URL for a menu item */
    private static function generateMenuUrl(array $menu): string
    {
        $routeName = Arr::get($menu, 'route_name');

        if (empty($routeName)) {
            return '#';
        }

        try {
            return route($routeName, Arr::get($menu, 'params', []));
        } catch (Exception $e) {
            return '#';
        }
    }

    /** Process submenu item */
    private static function processSubMenu(array $subMenu, string $currentPath): array
    {
        $subMenuUrl = self::generateMenuUrl($subMenu);
        $isActive = self::checkRouteActive($subMenuUrl, Arr::get($subMenu, 'exact', false), $currentPath);

        return [
            'title' => Arr::get($subMenu, 'title'),
            'icon' => Arr::get($subMenu, 'icon'),
            'url' => $subMenuUrl,
            'isActive' => $isActive,
            'access' => Arr::get($subMenu, 'access', true),
            'badge' => Arr::get($subMenu, 'badge'),
            'badge_classes' => Arr::get($subMenu, 'badge_classes', 'bg-primary/20 text-primary'),
            'exact' => Arr::get($subMenu, 'exact', false),
        ];
    }

    /** Check if route is active */
    private static function checkRouteActive(string $url, bool $exact, string $currentPath): bool
    {
        if ($url === '#') {
            return false;
        }

        try {
            $urlPath = parse_url($url, PHP_URL_PATH);
            $targetPath = ltrim($urlPath, '/');

            if ($exact) {
                return $currentPath === $targetPath || $currentPath === $targetPath . '/';
            }

            return str_starts_with($currentPath, $targetPath);
        } catch (Exception $e) {
            return false;
        }
    }
}
