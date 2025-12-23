<?php

declare(strict_types=1);

namespace App\Services\Menu\MenuItems;

use App\Models\User;

abstract class MenuItem
{
    /**
     * Convert menu item to array format
     *
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;

    /** Check if user has access to this menu item */
    abstract public function hasAccess(User $user): bool;

    /** Get menu item icon */
    abstract protected function getIcon(): string;

    /** Get menu item title */
    abstract protected function getTitle(): string;

    /** Get route name */
    abstract protected function getRouteName(): ?string;

    /**
     * Get route parameters
     *
     * @return array<string, mixed>
     */
    protected function getParams(User $user): array
    {
        return [];
    }

    /** Check if route match should be exact */
    protected function isExact(): bool
    {
        return false;
    }

    /**
     * Get sub menu items
     *
     * @return array<int, MenuItem>
     */
    protected function getSubMenuItems(User $user): array
    {
        return [];
    }

    /** Get badge text (optional) */
    protected function getBadge(): ?string
    {
        return null;
    }

    /** Get badge classes (optional) */
    protected function getBadgeClasses(): ?string
    {
        return null;
    }

    /**
     * Build final array structure
     *
     * @return array<string, mixed>
     */
    public function buildArray(User $user): array
    {
        $subMenuItems = $this->getSubMenuItems($user);
        $subMenu = [];

        foreach ($subMenuItems as $subMenuItem) {
            if ($subMenuItem->hasAccess($user)) {
                $subMenu[] = $subMenuItem->buildArray($user);
            }
        }

        $array = [
            'icon' => $this->getIcon(),
            'title' => $this->getTitle(),
            'params' => $this->getParams($user),
            'exact' => $this->isExact(),
        ];

        if ($this->getRouteName()) {
            $array['route_name'] = $this->getRouteName();
        }

        if ( ! empty($subMenu)) {
            $array['sub_menu'] = $subMenu;
        }

        if ($this->getBadge()) {
            $array['badge'] = $this->getBadge();
            if ($this->getBadgeClasses()) {
                $array['badge_classes'] = $this->getBadgeClasses();
            }
        }

        return $array;
    }
}
