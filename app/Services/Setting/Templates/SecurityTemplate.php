<?php

declare(strict_types=1);

namespace App\Services\Setting\Templates;

use App\Enums\BooleanEnum;
use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Models\Setting;

class SecurityTemplate extends BaseTemplate
{
    public function __construct()
    {
        $this->settingEnum = SettingEnum::SECURITY;
    }
    
    public function template(Setting $setting): array
    {
        $this->setting = $setting;
        $options       = BooleanEnum::options();
        
        $value1 = $this->selectOption($options, $this->setting->extra_attributes->get('captcha_handling', false));
        
        return [
            $this->record('captcha_handling', self::SELECT, default_value: false, options: $options, value: $value1['value']),
        ];
    }
    
    /** @throws ValidationException */
    public function validate(Setting $setting, array $payload = []): array
    {
        return $this->makeValidator($payload, [
            'captcha_handling' => ['required', 'boolean'],
        ], customAttributes: [
            'captcha_handling' => trans('setting.configs.security.items.captcha_handling.label'),
        ]);
    }
}
