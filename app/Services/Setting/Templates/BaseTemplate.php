<?php

declare(strict_types=1);

namespace App\Services\Setting\Templates;

use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * Base template for all settings groups.
 *
 * Provides a common DSL for defining setting records (simple and complex),
 * shared validation utilities, seeding defaults into the `extra_attributes`
 * JSON column, and permission-based visibility for each record.
 */
abstract class BaseTemplate
{
    protected const TEXT = 'text';
    protected const TEXTAREA = 'textarea';
    protected const NUMBER = 'number';
    protected const SELECT = 'select';
    protected const FILE = 'file';
    protected ?Setting $setting;
    protected ?SettingEnum $settingEnum;
    protected string $_prefix = 'extra_attributes.';
    private bool $checkCondition = true;

    abstract public function template(Setting $setting): array;

    abstract public function validate(Setting $setting, array $payload = []): array;

    /**
     * Persist validated payload into the setting's `extra_attributes` store.
     *
     * The concrete template `validate()` method is responsible for normalizing
     * and validating the payload. Its return value is then flattened and
     * written into `extra_attributes` using dot notation.
     */
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

    /**
     * Seed default values for the given setting enum.
     *
     * For each record defined in the template, if there is no value present
     * in `extra_attributes`, the `default` value from the record definition
     * will be written. Existing values are never overwritten.
     */
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

    /**
     * Build a single setting record definition.
     *
     * This describes a leaf field (non-complex) including its type, default
     * value, current value, translations, layout ratio, and optional select
     * options, validation rules, permissions, and service-based conditions.
     */
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
        $result = [
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

        return $this->applyVisibility($result, $permissions, $services);
    }

    /**
     * Build a complex/group setting record.
     *
     * A complex record groups multiple leaf records under a single key
     * (e.g. `company` with `name`, `address`, etc.), and can also have
     * permission and service-based visibility rules.
     */
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

        return $this->applyVisibility($result, $permissions, $services);
    }

    /**
     * Apply permission/service visibility rules to a record definition.
     *
     * Returns the original record if visible, or an empty array when it
     * should be hidden from the UI.
     */
    protected function applyVisibility(array $record, array $permissions = [], array $services = []): array
    {
        if (count($services) === 0) {
            if (count($permissions) === 0 || auth()->user()?->hasAnyPermission($permissions)) {
                return $record;
            }
        } else {
            foreach ($services as $service) {
                if ($service && (count($permissions) === 0 || auth()->user()?->hasAnyPermission($permissions))) {
                    return $record;
                }
            }
        }

        return [];
    }

    /** Create a label/value pair option for select fields. */
    protected function option($label, $value): array
    {
        return compact('label', 'value');
    }

    /**
     * Find the option entry that matches the given value.
     *
     * Used to map the stored scalar value back to its option definition
     * (label + value) for select-based settings.
     */
    protected function selectOption($options, $value): ?array
    {
        return collect($options)->where('value', $value)->first();
    }

    /**
     * Build and run a validator for the given data and rules.
     *
     * On failure, a domain-specific `ValidationException` is thrown with the
     * underlying error bag. On success, the validated data array is returned.
     *
     * @throws ValidationException
     */
    protected function makeValidator($data, $rules, $messages = [], $customAttributes = []): array
    {
        $validator = Validator::make($data, $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            // Handle validation failure (e.g., return a custom response)
            throw new ValidationException($validator->errors());
        }

        return $validator->validated();
    }

    /** Helper for building responsive layout ratios for each field. */
    protected function ratio(int $sm = 12, int $md = 8, int $lg = 6): array
    {
        return compact('sm', 'md', 'lg');
    }
}
