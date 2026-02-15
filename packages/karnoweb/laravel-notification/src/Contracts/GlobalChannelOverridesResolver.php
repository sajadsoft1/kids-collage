<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Contracts;

/**
 * Resolves global (admin) channel overrides per event.
 * Return array of channel => enabled (bool).
 *
 * @return array<string, bool>
 */
interface GlobalChannelOverridesResolver
{
    /** @return array<string, bool> */
    public function get(string $event): array;
}
