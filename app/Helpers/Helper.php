<?php

declare(strict_types=1);

use Illuminate\Http\Resources\Json\JsonResource;

if ( ! function_exists('generateCacheKey')) {
    function generateCacheKey(string $type, int $id, string $key, string $local): string
    {
        return 'translated:' . class_basename($type) . ':' . $id . ':' . $key . ':' . $local;
    }
}

if ( ! function_exists('attributeCacheKey')) {
    function attributeCacheKey($model, string $key, ?string $local = null): string
    {
        $local ??= app()->getLocale();
        $updatedDate = $model->updated_at?->timestamp ?? now()->getTimestamp();

        return 'attributes:' . class_basename($model) . ':' . $model->id . ':' . $key . ':' . $updatedDate . ':' . $local;
    }
}

if ( ! function_exists('deleteModelCache')) {
    function deleteModelCache($model): void
    {
        // Ensure the prefix is correctly concatenated
        $pattern = Cache::getPrefix() . 'attributes:' . class_basename($model) . ':' . $model->id . ':*';

        foreach (Cache::connection('cache')->keys($pattern) as $key) { // @phpstan-ignore-line
            Cache::forget(str_replace('laravel_database_', '', $key));
        }
    }
}

if ( ! function_exists('transOrNull')) {
    function transOrNull(string $key): ?string
    {
        // Get the translation using the original `trans` function
        $translation = trans($key);

        // Check if the translation is equal to the original key
        if ($translation === $key) {
            // Translation not found, return null
            return null;
        }

        // Translation found, return the string
        return $translation;
    }
}

if ( ! function_exists('removeTrailingSlash')) {
    function removeTrailingSlash(string $url): string
    {
        // Check if the URL ends with a slash and remove it
        if (str_ends_with($url, '/')) {
            $url = rtrim($url, '/');
        }

        return $url;
    }
}

if ( ! function_exists('getUrl')) {
    function getUrl(): string
    {
        if ($url = config('app.url')) {
            return removeTrailingSlash($url);
        }

        throw new RuntimeException(__('exception.app_url_not_set'));
    }
}

if ( ! function_exists('getResourceOrNull')) {
    function getResourceOrNull($resource): ?JsonResource
    {
        $object = (array) $resource;

        return is_null($object['resource']) ? null : $resource;
    }
}

if ( ! function_exists('isRtl')) {
    function isRtl(...$args): bool
    {
        return in_array(app()->getLocale(), ['fa', 'ar'], true);
    }
}

if ( ! function_exists('croperHint')) {
    /**
     * @throws JsonException
     */
    function croperHint(string $maxResolotion): string
    {
        return trans('general.croper_image_hint', ['resolution' => $maxResolotion]);
    }
}

if ( ! function_exists('_dd')) {
    function _dd(...$args)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        call_user_func_array('dd', $args);
    }
}

if ( ! function_exists('_dds')) {
    /**
     * @throws JsonException
     */
    function _dds(...$args): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Allow-Methods: *');
        header('Content-Type: application/json');
        exit(json_encode($args, JSON_THROW_ON_ERROR));
    }
}

if ( ! function_exists('hex2rgba')) {
    function hex2rgba($color, $alpha = 1): string
    {
        $color = str_replace('#', '', $color);
        if (strlen($color) === 6) {
            [$r, $g, $b] = [
                hexdec(substr($color, 0, 2)),
                hexdec(substr($color, 2, 2)),
                hexdec(substr($color, 4, 2)),
            ];
        } elseif (strlen($color) === 3) {
            [$r, $g, $b] = [
                hexdec(str_repeat($color[0], 2)),
                hexdec(str_repeat($color[1], 2)),
                hexdec(str_repeat($color[2], 2)),
            ];
        } else {
            return 'rgba(0,0,0,' . $alpha . ')';
        }

        return "rgba({$r}, {$g}, {$b}, {$alpha})";
    }
}

if ( ! function_exists('systemCurrency')) {
    function systemCurrency()
    {
        return match (config('app.currency')) {
            'IRR' => 'ریال',
            'IRT' => 'تومان',
            'USD' => 'دلار',
            'EUR' => 'یورو',
            'GBP' => 'پوند',
            'JPY' => 'ین',
            'CNY' => 'یوان',
            default => config('app.currency', 'ریال'),
        };
    }
}

if ( ! function_exists('is_route_active')) {
    /**
     * Check if route is active
     * Uses MenuService for consistency across the application
     */
    function is_route_active(string $routeName, array $params = [], bool $exact = false): bool
    {
        $menuService = app(App\Services\Menu\MenuService::class);

        return $menuService->isRouteActive($routeName, $params, $exact);
    }
}

if ( ! function_exists('setting')) {
    /**
     * Get a setting value from the system.
     *
     * Supports both SettingEnum and string keys with optional dot notation for nested values.
     *
     * Examples:
     *   setting('general')                    // Get all general settings
     *   setting('general', 'company.name')    // Get specific nested value
     *   setting('security', 'captcha_handling', false) // With default
     *   setting(SettingEnum::GENERAL, 'company.name')
     */
    function setting(string|App\Enums\SettingEnum $enum, ?string $selector = null, mixed $default = null): mixed
    {
        return App\Services\Setting\SettingService::get($enum, $selector, $default);
    }
}

if ( ! function_exists('settingIs')) {
    /**
     * Check if a setting value equals the expected value.
     *
     * Examples:
     *   settingIs('security', 'captcha_handling', true)
     *   settingIs(SettingEnum::SECURITY, 'captcha_handling', true)
     */
    function settingIs(string|App\Enums\SettingEnum $enum, string $selector, mixed $expected): bool
    {
        return setting($enum, $selector) === $expected;
    }
}

if ( ! function_exists('settingEnabled')) {
    /**
     * Check if a boolean setting is enabled (true).
     *
     * Examples:
     *   settingEnabled('security', 'captcha_handling')
     *   settingEnabled(SettingEnum::NOTIFICATION, 'order_create.email')
     */
    function settingEnabled(string|App\Enums\SettingEnum $enum, string $selector): bool
    {
        return (bool) setting($enum, $selector, false);
    }
}

if ( ! function_exists('settingDisabled')) {
    /**
     * Check if a boolean setting is disabled (false).
     *
     * Examples:
     *   settingDisabled('security', 'captcha_handling')
     */
    function settingDisabled(string|App\Enums\SettingEnum $enum, string $selector): bool
    {
        return ! settingEnabled($enum, $selector);
    }
}
