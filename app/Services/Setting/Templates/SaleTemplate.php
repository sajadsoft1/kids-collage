<?php

declare(strict_types=1);

namespace App\Services\Setting\Templates;

use App\Enums\BooleanEnum;
use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Models\Setting;

class SaleTemplate extends BaseTemplate
{
    public function __construct()
    {
        $this->settingEnum = SettingEnum::SALE;
    }
    
    public function template(Setting $setting): array
    {
        $this->setting = $setting;
        $options       = BooleanEnum::options();
        
        // values
        $value1 = $this->selectOption($options, $this->setting->extra_attributes->get('payment.credit_card_deposit', true));
        $value2 = $this->selectOption($options, $this->setting->extra_attributes->get('order.get_quantity_from_accounting', true));
        $value3 = $this->selectOption($options, $this->setting->extra_attributes->get('order.need_to_approve_by_storekeeper', true));
        $value4 = $this->selectOption($options, $this->setting->extra_attributes->get('discount.apply_discount_code_only_for_cash_payment', false));
        
        return [
            $this->recordComplex('order', [
                $this->record('get_quantity_from_accounting', self::SELECT, default_value: true, options: $options, value: $value2['value']),
                $this->record('order_return_days_limit', self::NUMBER, default_value: 30, value: $this->setting->extra_attributes->get('order.order_return_days_limit', 30)),
                $this->record('expiration_days_limit', self::NUMBER, default_value: 7, value: $this->setting->extra_attributes->get('order.expiration_days_limit', 7)),
                $this->record('need_to_approve_by_storekeeper', self::SELECT, default_value: true, options: $options, value: $value3['value']),
            ]),
            
            $this->recordComplex('cart', [
                $this->record('cart_capacity', self::NUMBER, default_value: 50, value: $this->setting->extra_attributes->get('cart.cart_capacity', 50)),
            ]),
            
            $this->recordComplex('discount', [
                $this->record('apply_discount_code_only_for_cash_payment', self::SELECT, default_value: false, options: $options, value: $value4['value']),
            ]),
            
            $this->recordComplex('payment', [
                $this->record('credit_card_deposit', self::SELECT, default_value: true, options: $options, value: $value1['value']),
            ]),
            
            $this->recordComplex('shipping', [
                $this->record('free_shipping', self::NUMBER, default_value: 1000000, value: $this->setting->extra_attributes->get('shipping.free_shipping', 1000000)),
            ]),
            
            $this->recordComplex('target', [
                $this->record('monthly', self::NUMBER, value: $setting->extra_attributes->get('target.monthly')),
                $this->record('semi_annual', self::NUMBER, value: $setting->extra_attributes->get('target.semi_annual')),
                $this->record('yearly', self::NUMBER, value: $setting->extra_attributes->get('target.yearly')),
            ]),
        ];
    }
    
    /** @throws ValidationException */
    public function validate(Setting $setting, array $payload = []): array
    {
        return $this->makeValidator($payload, [
            'order'                                              => ['required', 'array'],
            'order.get_quantity_from_accounting'                 => ['required', 'boolean'],
            'order.order_return_days_limit'                      => ['required', 'numeric'],
            'order.expiration_days_limit'                        => ['required', 'numeric'],
            'order.need_to_approve_by_storekeeper'               => ['required', 'boolean'],
            
            'cart'                                               => ['required', 'array'],
            'cart.cart_capacity'                                 => ['required', 'numeric'],
            
            'discount'                                           => ['required', 'array'],
            'discount.apply_discount_code_only_for_cash_payment' => ['required', 'boolean'],
            
            'payment'                                            => ['required', 'array'],
            'payment.credit_card_deposit'                        => ['required', 'boolean'],
            
            'shipping'                                           => ['required', 'array'],
            'shipping.free_shipping'                             => ['required', 'numeric'],
            
            'target'                                             => ['required', 'array'],
            'target.monthly'                                     => ['required', 'numeric', 'min:1'],
            'target.semi_annual'                                 => ['required', 'numeric', 'min:1'],
            'target.yearly'                                      => ['required', 'numeric', 'min:1'],
        ], customAttributes: [
            'order.get_quantity_from_accounting'                 => trans('setting.configs.sale.items.get_quantity_from_accounting.label'),
            'order.order_return_days_limit'                      => trans('setting.configs.sale.items.order_return_days_limit.label'),
            'order.expiration_days_limit'                        => trans('setting.configs.sale.items.expiration_days_limit.label'),
            'order.need_to_approve_by_storekeeper'               => trans('setting.configs.sale.items.need_to_approve_by_storekeeper.label'),
            
            'cart.cart_capacity'                                 => trans('setting.configs.sale.items.cart_capacity.label'),
            
            'discount.apply_discount_code_only_for_cash_payment' => trans('setting.configs.sale.items.apply_discount_code_only_for_cash_payment.label'),
            
            'payment.credit_card_deposit'                        => trans('setting.configs.sale.items.credit_card_deposit.label'),
            
            'shipping.free_shipping'                             => trans('setting.configs.sale.items.free_shipping.label'),
            
            'target.monthly'                                     => trans('setting.configs.product.items.show_sku.label'),
            'target.semi_annual'                                 => trans('setting.configs.product.items.show_sku.label'),
            'target.yearly'                                      => trans('setting.configs.product.items.show_sku.label'),
        ]);
    }
}
