<?php

declare(strict_types=1);

namespace App\Services\Setting\Templates;

use App\Enums\BooleanEnum;
use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Models\Setting;

class IntegrationSyncTemplate extends BaseTemplate
{
    public function __construct()
    {
        $this->settingEnum = SettingEnum::INTEGRATION_SYNC;
    }
    
    public function template(Setting $setting): array
    {
        $this->setting = $setting;
        $options       = BooleanEnum::options();
        
        $mahakStatus = $this->selectOption($options, $setting->extra_attributes->get('mahak.status', true));
        $orashStatus = $this->selectOption($options, $setting->extra_attributes->get('orash.status', true));
        
        return [
            $this->recordComplex('mahak', [
                $this->record('status', self::SELECT, default_value: true, options: $options, value: $mahakStatus['value']),
                $this->record('url', value: $setting->extra_attributes->get('mahak.url')),
                $this->record('user_name', value: $setting->extra_attributes->get('mahak.user_name')),
                $this->record('password', value: $setting->extra_attributes->get('mahak.password')),
            ]),
            $this->recordComplex('orash', [
                $this->record('status', self::SELECT, default_value: true, options: $options, value: $orashStatus['value']),
                $this->record('url', value: $setting->extra_attributes->get('orash.url')),
                $this->record('user_name', value: $setting->extra_attributes->get('orash.user_name')),
                $this->record('password', value: $setting->extra_attributes->get('orash.password')),
                $this->record('code', value: $setting->extra_attributes->get('orash.code')),
            ]),
        ];
    }
    
    /** @throws ValidationException */
    public function validate(Setting $setting, array $payload = []): array
    {
        return $this->makeValidator($payload, [
            'mahak'           => ['required', 'array'],
            'mahak.status'    => ['required', 'boolean'],
            'mahak.url'       => ['required'],
            'mahak.user_name' => ['required'],
            'mahak.password'  => ['required'],
            
            'orash'           => ['required', 'array'],
            'orash.status'    => ['required', 'boolean'],
            'orash.url'       => ['required'],
            'orash.user_name' => ['required'],
            'orash.password'  => ['required'],
            'orash.code'      => ['required'],
        ], customAttributes: [
            'mahak.status'    => trans('setting.configs.integration_sync.items.status.label'),
            'mahak.url'       => trans('setting.configs.integration_sync.items.url.label'),
            'mahak.user_name' => trans('setting.configs.integration_sync.items.user_name.label'),
            'mahak.password'  => trans('setting.configs.integration_sync.items.password.label'),
            
            'orash.status'    => trans('setting.configs.integration_sync.items.status.label'),
            'orash.url'       => trans('setting.configs.integration_sync.items.url.label'),
            'orash.user_name' => trans('setting.configs.integration_sync.items.user_name.label'),
            'orash.password'  => trans('setting.configs.integration_sync.items.password.label'),
            'orash.code'      => trans('setting.configs.integration_sync.items.code.label'),
        ]);
    }
}
