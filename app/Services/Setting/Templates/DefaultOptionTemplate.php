<?php

declare(strict_types=1);

namespace App\Services\Setting\Templates;

use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Models\Setting;
use Illuminate\Validation\Rule;

class DefaultOptionTemplate extends BaseTemplate
{
    public function template(Setting $setting): array
    {
        $this->setting     = $setting;
        $this->settingEnum = SettingEnum::from($setting->key);
        
        $options = [
            [
                'label' => trans('general.active'),
                'value' => true,
            ],
            [
                'label' => trans('general.inactive'),
                'value' => false,
            ],
        ];
        
        return [
            $this->record('status', self::SELECT, true, $options, $this->selectOption($options, $setting->extra_attributes->get('status', true))),
        ];
    }
    
    /** @throws ValidationException */
    public function validate(Setting $setting, array $payload = []): array
    {
        $keys = collect($this->template($setting))->pluck('key')->toArray();

        return $this->makeValidator($payload['value'], [
            '*.key'   => ['required', Rule::in($keys)],
            '*.value' => ['required'],
        ]);
    }
}
