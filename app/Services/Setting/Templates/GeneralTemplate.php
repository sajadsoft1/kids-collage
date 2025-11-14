<?php

declare(strict_types=1);

namespace App\Services\Setting\Templates;

use App\Enums\BooleanEnum;
use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Helpers\Constants;
use App\Models\Setting;

class GeneralTemplate extends BaseTemplate
{
    public function __construct()
    {
        $this->settingEnum = SettingEnum::GENERAL;
    }

    public function template(Setting $setting): array
    {
        $this->setting = $setting;
        $options = BooleanEnum::options();
        $media = $this->setting->getFirstMedia('logo');

        return [
            $this->recordComplex('website', [
                $this->record('logo', self::FILE, default_value: $media?->id, value: $media?->getFullUrl(Constants::RESOLUTION_100_SQUARE), rules: [
                    'image/png',
                    'image/jpeg',
                    'image/jpg',
                    'image/gif',
                ]),
            ]),

            $this->recordComplex('company', [
                $this->record('address', self::TEXT, default_value: '', value: $setting->extra_attributes->get('company.address')),
                $this->record('name', self::TEXT, default_value: '', value: $setting->extra_attributes->get('company.name')),
                $this->record('tell', self::TEXT, default_value: '', value: $setting->extra_attributes->get('company.tell')),
                $this->record('phone', self::TEXT, default_value: '', value: $setting->extra_attributes->get('company.phone')),
                $this->record('email', self::TEXT, default_value: '', value: $setting->extra_attributes->get('company.email')),
                $this->record('postal_code', self::TEXT, default_value: '', value: $setting->extra_attributes->get('company.postal_code')),
                $this->record('latitude', self::TEXT, default_value: '', value: $setting->extra_attributes->get('company.latitude')),
                $this->record('longitude', self::TEXT, default_value: '', value: $setting->extra_attributes->get('company.longitude')),
            ]),
        ];
    }

    /** @throws ValidationException */
    public function validate(Setting $setting, array $payload = []): array
    {
        return $this->makeValidator($payload, [
            'website' => ['required', 'array'],
            'website.logo' => ['nullable', 'integer', 'exists:media,id'],

            'company' => ['required', 'array'],
            'company.address' => ['nullable', 'string', 'max:255'],
            'company.name' => ['nullable', 'string', 'max:255'],
            'company.tell' => ['nullable', 'string', 'min:8', 'max:15'],
            'company.phone' => [
                'nullable',
                'regex:/^((0?9)|(\+?989))((14)|(13)|(12)|(19)|(18)|(17)|(15)|(16)|(11)|(10)|(90)|(91)|(92)|(93)|(94)|(95)|(96)|(32)|(30)|(33)|(35)|(36)|(37)|(38)|(39)|(00)|(01)|(02)|(03)|(04)|(05)|(41)|(20)|(21)|(22)|(23)|(31)|(34)|(9910)|(9911)|(9913)|(9914)|(9999)|(999)|(990)|(9810)|(9811)|(9812)|(9813)|(9814)|(9815)|(9816)|(9817)|(998))\d{7}$/',
            ],
            'company.email' => ['nullable', 'string', 'email'],
            'company.postal_code' => ['nullable', 'numeric', 'digits:10'],
            'company.latitude' => ['nullable', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'], // -90 to 90 and float
            'company.longitude' => ['nullable', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'], // -180 to 180 and float
        ], customAttributes: [
            'calendar_value' => trans('setting.configs.general.items.calendar_value.label'),

            'logo' => trans('setting.configs.general.items.value.label'),

            'company.address' => trans('setting.configs.general.items.address.label'),
            'company.name' => trans('setting.configs.general.items.name.label'),
            'company.tell' => trans('setting.configs.general.items.tell.label'),
            'company.phone' => trans('setting.configs.general.items.phone.label'),
            'company.email' => trans('setting.configs.general.items.email.label'),
            'company.postal_code' => trans('setting.configs.general.items.postal_code.label'),
            'company.latitude' => trans('setting.configs.general.items.latitude.label'),
            'company.longitude' => trans('setting.configs.general.items.longitude.label'),
        ]);
    }
}
