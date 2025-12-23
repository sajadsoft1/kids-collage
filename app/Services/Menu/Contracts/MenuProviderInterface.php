<?php

declare(strict_types=1);

namespace App\Services\Menu\Contracts;

interface MenuProviderInterface
{
    /**
     * Get menu array for the current user
     *
     * @return array<int, array<string, mixed>>
     */
    public function getMenu(): array;
}
