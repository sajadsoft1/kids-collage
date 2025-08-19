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

    public function ti() {}
}
