<?php

declare(strict_types=1);

namespace App\Services\Setting\Templates;

use App\Enums\BooleanEnum;
use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Models\Setting;

class NotificationTemplate extends BaseTemplate
{
    public function __construct()
    {
        $this->settingEnum = SettingEnum::NOTIFICATION;
    }
    
    public function template(Setting $setting): array
    {
        $this->setting = $setting;
        $options       = BooleanEnum::options();
        
        $orderCreateSmsValue          = $this->selectOption($options, $setting->extra_attributes->get('order_create.sms', true));
        $orderCreateEmailValue        = $this->selectOption($options, $setting->extra_attributes->get('order_create.email', true));
        $orderCreateNotificationValue = $this->selectOption($options, $setting->extra_attributes->get('order_create.notification', true));
        
        $chatCreateSmsValue          = $this->selectOption($options, $setting->extra_attributes->get('chat_create.sms', true));
        $chatCreateEmailValue        = $this->selectOption($options, $setting->extra_attributes->get('chat_create.email', true));
        $chatCreateNotificationValue = $this->selectOption($options, $setting->extra_attributes->get('chat_create.notification', true));
        
        $userCreateSmsValue          = $this->selectOption($options, $setting->extra_attributes->get('user_create.sms', true));
        $userCreateEmailValue        = $this->selectOption($options, $setting->extra_attributes->get('user_create.email', true));
        $userCreateNotificationValue = $this->selectOption($options, $setting->extra_attributes->get('user_create.notification', true));
        
        return [
            $this->recordComplex('order_create', [
                $this->record('sms', self::SELECT, default_value: true, options: $options, value: $orderCreateSmsValue['value']),
                $this->record('email', self::SELECT, default_value: true, options: $options, value: $orderCreateEmailValue['value']),
                $this->record('notification', self::SELECT, default_value: true, options: $options, value: $orderCreateNotificationValue['value']),
            ]),
            $this->recordComplex('chat_create', [
                $this->record('sms', self::SELECT, default_value: true, options: $options, value: $chatCreateSmsValue['value']),
                $this->record('email', self::SELECT, default_value: true, options: $options, value: $chatCreateEmailValue['value']),
                $this->record('notification', self::SELECT, default_value: true, options: $options, value: $chatCreateNotificationValue['value']),
            ]),
            $this->recordComplex('user_create', [
                $this->record('sms', self::SELECT, default_value: true, options: $options, value: $userCreateSmsValue['value']),
                $this->record('email', self::SELECT, default_value: true, options: $options, value: $userCreateEmailValue['value']),
                $this->record('notification', self::SELECT, default_value: true, options: $options, value: $userCreateNotificationValue['value']),
            ]),
        ];
    }
    
    /** @throws ValidationException */
    public function validate(Setting $setting, array $payload = []): array
    {
        return $this->makeValidator($payload, [
            'order_create'              => ['required', 'array'],
            'order_create.sms'          => ['required', 'boolean'],
            'order_create.email'        => ['required', 'boolean'],
            'order_create.notification' => ['required', 'boolean'],
            
            'chat_create'               => ['required', 'array'],
            'chat_create.sms'           => ['required', 'boolean'],
            'chat_create.email'         => ['required', 'boolean'],
            'chat_create.notification'  => ['required', 'boolean'],
            
            'user_create'               => ['required', 'array'],
            'user_create.sms'           => ['required', 'boolean'],
            'user_create.email'         => ['required', 'boolean'],
            'user_create.notification'  => ['required', 'boolean'],
        ], customAttributes: [
            'order_create.sms'          => trans('setting.configs.notification.items.sms.label'),
            'order_create.email'        => trans('setting.configs.notification.items.email.label'),
            'order_create.notification' => trans('setting.configs.notification.items.notification.label'),
            
            'chat_create.sms'           => trans('setting.configs.notification.items.sms.label'),
            'chat_create.email'         => trans('setting.configs.notification.items.email.label'),
            'chat_create.notification'  => trans('setting.configs.notification.items.notification.label'),
            
            'user_create.sms'           => trans('setting.configs.notification.items.sms.label'),
            'user_create.email'         => trans('setting.configs.notification.items.email.label'),
            'user_create.notification'  => trans('setting.configs.notification.items.notification.label'),
        ]);
    }
}
