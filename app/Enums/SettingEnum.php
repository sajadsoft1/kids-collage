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
}
