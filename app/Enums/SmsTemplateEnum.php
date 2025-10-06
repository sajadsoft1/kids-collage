<?php

declare(strict_types=1);

namespace App\Enums;

enum SmsTemplateEnum: string
{
    use EnumToArray;

    case LOGIN_OTP           = 'login_otp';
    case REGISTER_OTP        = 'register_otp';
    case FORGOT_PASSWORD_OTP = 'forgot_password_otp';
    case VERIFY_PHONE_OTP    = 'verify_phone_otp';

    public function title(): string
    {
        return match ($this) {
            self::LOGIN_OTP           => 'login_otp',
            self::REGISTER_OTP        => 'register_otp',
            self::FORGOT_PASSWORD_OTP => 'forgot_password_otp',
            self::VERIFY_PHONE_OTP    => 'verify_phone_otp',
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
