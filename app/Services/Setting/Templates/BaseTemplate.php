<?php

declare(strict_types=1);

namespace App\Services\Setting\Templates;

use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

abstract class BaseTemplate
{
    protected const TEXT   = 'text';
    protected const NUMBER = 'number';
    protected const SELECT = 'select';
    protected const FILE   = 'file';
    protected ?Setting $setting;
    protected ?SettingEnum $settingEnum;
    protected string $_prefix    = 'extra_attributes.';
    private bool $checkCondition = true;
    
    abstract public function template(Setting $setting): array;
    
    abstract public function validate(Setting $setting, array $payload = []): array;
    
    public function update(Setting $setting, array $payload = []): Setting
    {
        foreach ($this->validate($setting, $payload) as $key => $item) {
            if (is_array($item)) {
                foreach ($item as $subKey => $subItem) {
                    $setting->extra_attributes->set($key . '.' . $subKey, $subItem);
                }
            } else {
                $setting->extra_attributes->set($key, $item);
            }
        }
        
        $setting->save();
        
        $this->afterUpdate($setting);
        
        return $setting->fresh();
    }
    
    public function seed(SettingEnum $enum): void
    {
        $setting = Setting::firstOrCreate([
            'key' => $enum->value,
        ], [
            'permissions' => ['admin'],
        ]);
        
        $this->checkCondition = false;
        
        collect($this->template($setting))->each(function ($item) use ($setting) {
            $key = $item['key'];
            if (Arr::get($item, 'complex', false)) {
                collect($item['items'])->each(function ($row) use ($item, $setting) {
                    $key = $item['key'] . '.' . $row['key'];
                    if (is_null($setting->extra_attributes->get($key, null))) {
                        $setting->extra_attributes->set($key, Arr::get($row, 'default'));
                    }
                });
            } elseif (is_null($setting->extra_attributes->get($key, null))) {
                $setting->extra_attributes->set($key, Arr::get($item, 'default'));
            }
        });
        $setting->save();
    }
    
    protected function afterUpdate(Setting $setting) {}
    
    protected function record(
        $key,
        $type = self::TEXT,
        $default_value = null,
        ?array $options = null,
        mixed $value = null,
        ?array $rules = null,
        array $ratio = ['sm' => 12, 'md' => 6, 'lg' => 6],
        array $permissions = [],
        array $services = []
    ): array {
        $translationKey = $this->settingEnum->value;
        $result         = [
            'key' => $key,
            'default' => $default_value,
            'label' => transOrNull('setting.configs.' . $translationKey . '.items.' . $key . '.label'),
            'value' => [
                'type' => $type,
                'value' => $value ?: ($this->setting->extra_attributes->get($key, $default_value)),
            ],
            'hint' => transOrNull('setting.configs.' . $translationKey . '.items.' . $key . '.hint'),
            'help' => transOrNull('setting.configs.' . $translationKey . '.items.' . $key . '.help'),
            'ratio' => $ratio,
        ];
        
        if ($options) {
            $result['value']['options'] = $options;
        }
        
        if ($rules) {
            $result['value']['rules'] = $rules;
        }
        
        if ( ! $this->checkCondition) {
            return $result;
        }
        
        if (count($services) === 0) {
            if (count($permissions) === 0 || auth()->user()?->hasAnyPermission($permissions)) {
                return $result;
            }
        } else {
            foreach ($services as $service) {
                if ($service) {
                    if (count($permissions) === 0 || auth()->user()?->hasAnyPermission($permissions)) {
                        return $result;
                    }
                }
            }
        }
        
        return [];
    }
    
    protected function recordComplex($key, array $items = [], array $permissions = [], array $services = []): array
    {
        $translationKey = $this->settingEnum->value;
        $translationKey .= '.';
        $result = [
            'complex' => true,
            'key' => $key,
            'label' => transOrNull('setting.configs.' . $translationKey . 'groups.' . $key . '.label'),
            'items' => $items,
            'help' => transOrNull('setting.configs.' . $translationKey . 'groups.' . $key . '.help'),
        ];
        
        if ( ! $this->checkCondition) {
            return $result;
        }
        
        if (count($services) === 0) {
            if (count($permissions) === 0 || auth()->user()?->hasAnyPermission($permissions)) {
                return $result;
            }
        } else {
            foreach ($services as $service) {
                if ($service) {
                    if (count($permissions) === 0 || auth()->user()?->hasAnyPermission($permissions)) {
                        return $result;
                    }
                }
            }
        }
        
        return [];
    }
    
    protected function option($label, $value): array
    {
        return compact('label', 'value');
    }
    
    protected function selectOption($options, $value): ?array
    {
        return collect($options)->where('value', $value)->first();
    }
    
    /** @throws ValidationException */
    protected function makeValidator($data, $rules, $messages = [], $customAttributes = []): array
    {
        $validator = Validator::make($data, $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            // Handle validation failure (e.g., return a custom response)
            throw new ValidationException($validator->errors());
        }

        return $validator->validated();
    }
    
    protected function ratio(int $sm = 12, int $md = 8, int $lg = 6): array
    {
        return compact('sm', 'md', 'lg');
    }
}
