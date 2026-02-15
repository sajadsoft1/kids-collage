<?php

declare(strict_types=1);

namespace App\Support\Notifications\Resolvers;

use App\Enums\SettingEnum;
use App\Services\Setting\SettingService;
use Karnoweb\LaravelNotification\Contracts\GlobalChannelOverridesResolver;

class GlobalChannelOverridesResolver implements GlobalChannelOverridesResolver
{
    /** @return array<string, bool> */
    public function get(string $event): array
    {
        $settings = SettingService::get(SettingEnum::NOTIFICATION, $event, []);

        return is_array($settings) ? $settings : [];
    }
}
