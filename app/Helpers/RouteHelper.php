<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Services\Menu\MenuService;

/**
 * Helper class for route-related operations
 * This provides a unified interface for route checking across the application
 */
class RouteHelper
{
    /**
     * Check if route is active
     * Uses MenuService for consistency across the application
     */
    public static function isRouteActive(string $routeName, array $params = [], bool $exact = false): bool
    {
        $menuService = app(MenuService::class);

        return $menuService->isRouteActive($routeName, $params, $exact);
    }
}
