<?php

declare(strict_types=1);

return [
    'default'  => env('SMS_DEFAULT', 'kavenegar'),
    'failover' => array_filter(explode(',', (string) env('SMS_FAILOVER', 'smsir,mellipayamac'))),

    'drivers'  => [
        'kavenegar'    => [
            'class'       => App\Services\Sms\Drivers\KavenegarDriver::class,
            'credentials' => [
                'token'  => env('KAVENEGAR_TOKEN'),
                'sender' => env('KAVENEGAR_SENDER'),
            ],
        ],
        'smsir'        => [
            'class'       => App\Services\Sms\Drivers\SmsIrDriver::class,
            'credentials' => [
                'api_key'    => env('SMSIR_API_KEY'),
                'secret_key' => env('SMSIR_SECRET_KEY'),
                'sender'     => env('SMSIR_SENDER'),
            ],
        ],
        'mellipayamac' => [
            'class'       => App\Services\Sms\Drivers\MelliPayamakDriver::class,
            'credentials' => [
                'username' => env('MELLIPAYAMAK_USERNAME'),
                'password' => env('MELLIPAYAMAK_PASSWORD'),
                'sender'   => env('MELLIPAYAMAK_SENDER'),
            ],
        ],
    ],
];
