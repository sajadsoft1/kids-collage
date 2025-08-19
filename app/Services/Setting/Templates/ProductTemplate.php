<?php

declare(strict_types=1);

namespace App\Services\Setting\Templates;

use App\Enums\BooleanEnum;
use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Models\Setting;

class ProductTemplate extends BaseTemplate
{
    public function template(Setting $setting): array
    {
        $this->setting     = $setting;
        $this->settingEnum = SettingEnum::PRODUCT;
        
        $booleanOptions = BooleanEnum::options();
        
        $value1 = $this->selectOption($booleanOptions, $this->setting->extra_attributes->get('product_title.show_sku', false));
        $value2 = $this->selectOption($booleanOptions, $this->setting->extra_attributes->get('product_title.show_attribute', false));
        $value3 = $this->selectOption($booleanOptions, $this->setting->extra_attributes->get('product_title.show_brand', false));
        $value4 = $this->selectOption($booleanOptions, $this->setting->extra_attributes->get('product_title.show_category', false));
        $value5 = $this->selectOption($booleanOptions, $this->setting->extra_attributes->get('product_price.show_out_of_stock_products_price', true));
        $value6 = $this->selectOption($booleanOptions, $this->setting->extra_attributes->get('show_number_of_added_to_carts_in_product_detail', false));
        $value7 = $this->selectOption($booleanOptions, $this->setting->extra_attributes->get('show_number_of_sold_in_product_detail', false));
        
        return [
            $this->recordComplex('product_title', [
                $this->record('show_sku', self::SELECT, default_value: false, options: $booleanOptions, value: $value1['value']),
                $this->record('show_attribute', self::SELECT, default_value: false, options: $booleanOptions, value: $value2['value']),
                $this->record('show_brand', self::SELECT, default_value: false, options: $booleanOptions, value: $value3['value']),
                $this->record('show_category', self::SELECT, default_value: false, options: $booleanOptions, value: $value4['value']),
            ]),
            
            $this->recordComplex('product_price', [
                $this->record('show_out_of_stock_products_price', self::SELECT, default_value: true, options: $booleanOptions, value: $value5['value']),
            ]),
            
            $this->recordComplex('product_detail', [
                $this->record('show_number_of_added_to_carts_in_product_detail', self::SELECT, default_value: false, options: $booleanOptions, value: $value6['value']),
                $this->record('show_number_of_sold_in_product_detail', self::SELECT, default_value: false, options: $booleanOptions, value: $value7['value']),
            ]),
        ];
    }
    
    /** @throws ValidationException */
    public function validate(Setting $setting, array $payload = []): array
    {
        return $this->makeValidator($payload, [
            'product_title'                                                  => ['required', 'array'],
            'product_title.show_sku'                                         => ['required', 'boolean'],
            'product_title.show_attribute'                                   => ['required', 'boolean'],
            'product_title.show_brand'                                       => ['required', 'boolean'],
            'product_title.show_category'                                    => ['required', 'boolean'],
            
            'product_price'                                                  => ['required', 'array'],
            'product_price.show_out_of_stock_products_price'                 => ['required', 'boolean'],
            
            'product_detail'                                                 => ['required', 'array'],
            'product_detail.show_number_of_added_to_carts_in_product_detail' => ['required', 'boolean'],
            'product_detail.show_number_of_sold_in_product_detail'           => ['required', 'boolean'],
        ], customAttributes: [
            'product_title.show_sku'                                         => trans('setting.configs.product.items.show_sku.label'),
            'product_title.show_attribute'                                   => trans('setting.configs.product.items.show_attribute.label'),
            'product_title.show_brand'                                       => trans('setting.configs.product.items.show_brand.label'),
            'product_title.show_category'                                    => trans('setting.configs.product.items.show_category.label'),
            'product_price.show_out_of_stock_products_price'                 => trans('setting.configs.product.items.show_out_of_stock_products_price.label'),
            'product_detail.show_number_of_added_to_carts_in_product_detail' => trans('setting.configs.product.items.show_number_of_added_to_carts_in_product_detail.label'),
            'product_detail.show_number_of_sold_in_product_detail'           => trans('setting.configs.product.items.show_number_of_sold_in_product_detail.label'),
        ]);
    }
    
    protected function afterUpdate(Setting $setting): void
    {
        //        if ($setting->key === 'product') {
        //            Cache::tags('product-titles')->flush();
        //        }
        //
        //        if (ServiceHelper::enableAccounting() && $setting->extra_attributes->get('read_stock_status_from_accounting', true)) {
        //            foreach (Product::all() as $product) {
        //                event(new UpdateProductStockStatusEvent($product));
        //            }
        //        }
    }
}
