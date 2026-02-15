<?php

declare(strict_types=1);

namespace Karnoweb\LaravelNotification\Resolvers;

use Karnoweb\LaravelNotification\Contracts\UserChannelOverridesResolver;
use Karnoweb\LaravelNotification\Models\NotificationPreference;

class DatabaseUserChannelOverridesResolver implements UserChannelOverridesResolver
{
    /** @return array<string, bool> */
    public function get(?object $profile, string $event): array
    {
        if ($profile === null) {
            return [];
        }

        if ( ! method_exists($profile, 'getMorphClass') || ! method_exists($profile, 'getKey')) {
            return [];
        }

        return NotificationPreference::getChannels($profile, $event);
    }
}
