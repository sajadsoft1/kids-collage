<?php

declare(strict_types=1);

namespace App\Services\Setting;

use App\Enums\SettingEnum;
use App\Helpers\StringHelper;
use App\Models\Setting;
use Exception;
use JsonException;
use RuntimeException;

readonly class SettingService
{
    public static function set(SettingEnum $enum, string $key, mixed $value): Setting
    {
        $class   = self::getSettingTemplateClass($enum->value);
        $setting = Setting::createOrFirst([
            'key' => $enum->value,
        ], [
            'permissions' => ['Shared.Admin'],
        ]);
        
        $value = [
            'key'   => $enum->value,
            'value' => [
                [
                    'key'   => $key,
                    'value' => $value,
                ],
            ],
        ];

        return $class->update($setting, $value);
    }
    
    public static function get(string|SettingEnum $enum, ?string $selector = null, mixed $default = null): mixed
    {
        try {
            $setting = Setting::createOrFirst([
                'key' => $enum->value,
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
    
    public static function getSettingTemplateClass($key)
    {
        return app('App\\Services\\Setting\\Templates\\' . StringHelper::convertToClassName($key . '_template'));
    }
    
    public function show(Setting $setting): array
    {
        $filterKey = request()->input('filter_key');
        
        // Ensure filterKey is always an array, even if a single key is provided
        if ($filterKey !== null) {
            $filterKeys = is_array($filterKey) ? $filterKey : ([$filterKey]);
        } else {
            $filterKeys = [];
        }
        
        $template    = self::getSettingTemplateClass($setting->key)->template($setting);
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
        
        $template = array_map(static function ($item) use ($filterKeys) {
            if (isset($item['items']) && ! empty($filterKeys)) {
                foreach ($filterKeys as $filterKey) {
                    if (str_contains($filterKey, '.')) {
                        [$parentKey, $childKey] = explode('.', $filterKey, 2);
                        
                        // If the parent key matches, filter the items based on the child key
                        if ($item['key'] === $parentKey) {
                            $item['items'] = array_filter($item['items'], static function ($subItem) use ($childKey) {
                                return $subItem['key'] === $childKey;
                            });
                            $item['items'] = array_values($item['items']);
                        }
                    }
                }
            }
            
            return $item;
        }, $template);

        return [
            'id'    => $setting->id,
            'uuid'  => $setting->uuid,
            'key'   => $setting->key,
            'label' => $settingEnum?->title(),
            'help'  => $settingEnum?->help(),
            'rows'  => $template,
        ];
    }
    
    public function update(SettingEnum $settingEnum, array $payload = [])
    {
        $setting = Setting::createOrFirst([
            'key' => $settingEnum->value,
        ], [
            'permissions' => ['Shared.Admin'],
        ]);
        $class   = self::getSettingTemplateClass($setting->key);
        $updated = $class->update($setting, $payload);

        return $class->template($updated);
    }
    
    /** @throws JsonException */
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
