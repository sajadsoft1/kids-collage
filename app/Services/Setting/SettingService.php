<?php

declare(strict_types=1);

namespace App\Services\Setting;

use App\Enums\SettingEnum;
use App\Helpers\StringHelper;
use App\Models\Setting;
use Exception;
use JsonException;
use RuntimeException;

/**
 * Service facade for working with application settings.
 *
 * Responsibilities:
 * - Resolve the appropriate template class for a given setting key
 * - Provide simple `get` / `set` helpers over the `Setting` model
 * - Orchestrate show/update flows used by the admin UI
 * - Delegate validation, seeding, and field definitions to template classes.
 */
readonly class SettingService
{
    /**
     * Generic helper for updating a setting group using its template.
     *
     * The payload structure must match what the concrete template expects
     * (the same shape you would pass to the instance `update()` method).
     */
    public static function set(string|SettingEnum $enum, array $payload): Setting
    {
        $key = $enum instanceof SettingEnum ? $enum->value : $enum;

        $class = self::getSettingTemplateClass($key);
        $setting = Setting::createOrFirst([
            'key' => $key,
        ], [
            'permissions' => ['Shared.Admin'],
        ]);

        return $class->update($setting, $payload);
    }
    
    /**
     * Read a setting value from the underlying `extra_attributes` store.
     *
     * When no selector is provided, the entire attributes array is returned.
     * When a selector (dot notation) is provided, only that value is returned
     * or the given default if it does not exist or an error occurs.
     */
    public static function get(string|SettingEnum $enum, ?string $selector = null, mixed $default = null): mixed
    {
        $key = $enum instanceof SettingEnum ? $enum->value : $enum;
        
        try {
            $setting = Setting::createOrFirst([
                'key' => $key,
            ], [
                'permissions' => ['Shared.Admin'],
            ]);
        } catch (Exception $exception) {
            return $default;
        }
        if ( ! $selector) {
            return $setting->extra_attributes->all() ?? $default;
        }

        return $setting->extra_attributes->get($selector, $default);
    }
    
    /**
     * Resolve the concrete template class instance for the given setting key.
     *
     * The key is converted to StudlyCase with a `Template` suffix and then
     * resolved from the Laravel service container.
     */
    public static function getSettingTemplateClass($key)
    {
        return app('App\\Services\\Setting\\Templates\\' . StringHelper::convertToClassName($key . '_template'));
    }
    
    /**
     * Build the full template payload for the given `Setting` model.
     *
     * Optionally, a `filter_key` query parameter can be used to limit the
     * returned rows to specific groups or nested keys (e.g. `company.name`).
     * The result is a structure ready to be consumed by the admin settings UI.
     */
    public function show(Setting $setting): array
    {
        $filterKey = request()->input('filter_key');
        
        // Ensure filterKey is always an array, even if a single key is provided
        if ($filterKey !== null) {
            $filterKeys = is_array($filterKey) ? $filterKey : ([$filterKey]);
        } else {
            $filterKeys = [];
        }
        
        $template = self::getSettingTemplateClass($setting->key)->template($setting);
        $settingEnum = SettingEnum::tryFrom($setting->key);
        
        if ( ! empty($filterKeys)) {
            // Filter the template to include only items whose keys are in the filterKeys array
            $template = array_filter($template, static function ($item) use ($filterKeys) {
                foreach ($filterKeys as $filterKey) {
                    if (str_contains($filterKey, '.')) {
                        [$parentKey, $childKey] = explode('.', $filterKey, 2);
                        
                        // Check if the current item matches the parent key
                        if ($item['key'] === $parentKey && isset($item['items'])) {
                            // Filter the nested items based on the child key
                            $item['items'] = array_filter($item['items'], static function ($subItem) use ($childKey) {
                                return $subItem['key'] === $childKey;
                            });
                            
                            // Reindex the item array
                            $item['items'] = array_values($item['items']);
                            
                            // Return true only if there are matching subitems
                            return ! empty($item['items']);
                        }
                    } elseif ($item['key'] === $filterKey) {
                        return true;
                    }
                }
                
                return false;
            });
        } else {
            // If no filter key is provided, filter out null items
            $template = array_filter($template);
        }
        
        // Reindex the array to remove numeric keys
        $template = array_values($template);

        return [
            'id' => $setting->id,
            'uuid' => $setting->uuid,
            'key' => $setting->key,
            'label' => $settingEnum?->title(),
            'help' => $settingEnum?->help(),
            'rows' => $template,
        ];
    }
    
    /**
     * Update a full setting group using its template definition.
     *
     * The payload must match the structure that the underlying template
     * expects (and validates). On success, the updated template structure
     * is returned for convenient use in the UI.
     */
    public function update(SettingEnum $settingEnum, array $payload = [])
    {
        $setting = Setting::createOrFirst([
            'key' => $settingEnum->value,
        ], [
            'permissions' => ['Shared.Admin'],
        ]);
        $class = self::getSettingTemplateClass($setting->key);
        $updated = $class->update($setting, $payload);

        return $class->template($updated);
    }
    
    /**
     * Seed default values for the given setting enum via its template.
     *
     * Any exception thrown by the template is wrapped in a `RuntimeException`
     * with a JSON-encoded payload for easier debugging in logs.
     *
     * @throws JsonException
     */
    public function seed(SettingEnum $enum): void
    {
        try {
            $class = self::getSettingTemplateClass($enum->value);
            $class->seed($enum);
        } catch (Exception $exception) {
            throw new RuntimeException(json_encode([$enum->value, $exception->getMessage()], JSON_THROW_ON_ERROR));
        }
    }
}
