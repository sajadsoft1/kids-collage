<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Contracts;

/**
 * Resolves user/profile channel overrides per event.
 * Return array of channel => enabled (bool).
 *
 * @return array<string, bool>
 */
interface UserChannelOverridesResolver
{
    /** @return array<string, bool> */
    public function get(?object $profile, string $event): array;
}
