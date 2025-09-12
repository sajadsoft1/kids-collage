<?php

declare(strict_types=1);

namespace App\Enums;

enum SettingEnum: string
{
    use EnumToArray;

    case PRODUCT          = 'product';
    case GENERAL          = 'general';
    case INTEGRATION_SYNC = 'integration_sync';
    case NOTIFICATION     = 'notification';
    case SALE             = 'sale';
    case SECURITY         = 'security';

    public static function options(): array
    {
        return [
            [
                'label' => trans('setting.configs.product.label'),
                'value' => self::PRODUCT->value,
            ],
            [
                'label' => trans('setting.configs.general.label'),
                'value' => self::GENERAL->value,
            ],
            [
                'label' => trans('setting.configs.integration_sync.label'),
                'value' => self::INTEGRATION_SYNC->value,
            ],
            [
                'label' => trans('setting.configs.notification.label'),
                'value' => self::NOTIFICATION->value,
            ],
            [
                'label' => trans('setting.configs.sale.label'),
                'value' => self::SALE->value,
            ],
            [
                'label' => trans('setting.configs.security.label'),
                'value' => self::SECURITY->value,
            ],
        ];
    }

    public static function websiteSettings(): array
    {
        return [
            self::GENERAL->value,
            self::SECURITY->value,
            self::PRODUCT->value,
            self::SALE->value,
        ];
    }

    public static function activeSettings(): array
    {
        $data = collect();
        $data->push(self::websiteSettings());

        return $data->flatten()->unique()->toArray();
    }

    public function title(): string
    {
        return trans('setting.configs.' . $this->value . '.label');
    }

    public function help(): string
    {
        return trans('setting.configs.' . $this->value . '.help');
    }

    public function hint(): string
    {
        return trans('setting.configs.' . $this->value . '.hint');
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
            'help'  => $this->help(),
            'hint'  => $this->hint(),
        ];
    }
}
