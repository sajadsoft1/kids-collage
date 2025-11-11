<?php

declare(strict_types=1);

return [
    'model' => 'Discount',
    'permissions' => [
        'Index' => 'View Discounts',
        'Show' => 'Show Discount',
        'Store' => 'Create Discount',
        'Update' => 'Update Discount',
        'Delete' => 'Delete Discount',
    ],
    'exceptions' => [
        'discount_not_found' => 'Discount code not found',
        'discount_expired' => 'This discount code has expired',
        'discount_not_active' => 'This discount code is not active',
        'discount_limit_reached' => 'This discount code has reached its usage limit',
        'discount_user_limit' => 'You have reached the usage limit for this discount code',
        'discount_min_order' => 'Order amount does not meet the minimum requirement',
        'discount_user_specific' => 'This discount code is not available for your account',
        'discount_not_started' => 'This discount code is not yet active',
    ],
    'validations' => [
        'code_required' => 'Discount code is required',
        'code_unique' => 'This discount code already exists',
        'type_required' => 'Discount type is required',
        'value_required' => 'Discount value is required',
        'value_percentage' => 'Percentage value must be between 0 and 100',
        'expires_after_start' => 'Expiration date must be after start date',
    ],
    'enum' => [
        'type' => [
            'percentage' => 'Percentage',
            'amount' => 'Fixed Amount',
        ],
    ],
    'notifications' => [
        'discount_applied' => 'Discount code applied successfully',
        'discount_removed' => 'Discount code removed',
        'discount_created' => 'Discount code created successfully',
        'discount_updated' => 'Discount code updated successfully',
        'discount_deleted' => 'Discount code deleted successfully',
    ],
    'page' => [
        'title' => 'Discount Management',
        'description' => 'Create and manage discount codes',
        'fields' => [
            'code' => 'Code',
            'type' => 'Type',
            'value' => 'Value',
            'user' => 'Specific User',
            'min_order_amount' => 'Minimum Order Amount',
            'max_discount_amount' => 'Maximum Discount Amount',
            'usage_limit' => 'Total Usage Limit',
            'usage_per_user' => 'Usage Per User',
            'used_count' => 'Times Used',
            'starts_at' => 'Start Date',
            'expires_at' => 'Expiration Date',
            'is_active' => 'Active',
            'description' => 'Description',
            'status' => 'Status',
        ],
        'placeholders' => [
            'code' => 'e.g., SUMMER2025',
            'value' => 'Enter discount value',
            'min_order_amount' => 'Minimum order amount',
            'max_discount_amount' => 'Maximum discount cap',
            'usage_limit' => 'Leave empty for unlimited',
            'description' => 'Optional description',
        ],
        'help' => [
            'code' => 'Unique code customers will use to apply this discount',
            'type' => 'Choose between percentage or fixed amount discount',
            'value' => 'For percentage: 0-100, For amount: fixed dollar value',
            'user' => 'Leave empty for all users, or select specific user',
            'min_order_amount' => 'Minimum order amount required to use this discount',
            'max_discount_amount' => 'Maximum discount amount (useful for percentage discounts)',
            'usage_limit' => 'Total number of times this discount can be used',
            'usage_per_user' => 'Number of times each user can use this discount',
        ],
    ],
];
