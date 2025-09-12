<?php

declare(strict_types=1);

namespace App\Enums;


enum NoificationTypeEnum: string
{
    use EnumToArray;

    case AUTH_REGISTER        = 'auth_register';
    case AUTH_CONFIRM         = 'auth_confirm';
    case AUTH_FORGOT_PASSWORD = 'auth_forgot_password';
    case AUTH_WELCOME         = 'auth_welcome';

    public static function options(): array
    {
        return [
            [
                'label' => __('notification.enum.type.auth_register'),
                'value' => self::AUTH_REGISTER->value,
            ],
            [
                'label' => __('notification.enum.type.auth_confirm'),
                'value' => self::AUTH_CONFIRM->value,
            ],
            [
                'label' => __('notification.enum.type.auth_forgot_password'),
                'value' => self::AUTH_FORGOT_PASSWORD->value,
            ],
            [
                'label' => __('notification.enum.type.auth_welcome'),
                'value' => self::AUTH_WELCOME->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::AUTH_REGISTER        => __('notification.enum.type.auth_register'),
            self::AUTH_CONFIRM         => __('notification.enum.type.auth_confirm'),
            self::AUTH_FORGOT_PASSWORD => __('notification.enum.type.auth_forgot_password'),
            self::AUTH_WELCOME         => __('notification.enum.type.auth_welcome'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
        ];
    }
}
