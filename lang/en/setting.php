<?php

declare(strict_types=1);

return [
    'model' => 'Settings',
    'setting_store_succesfully' => 'Settings saved successfully',
    'configs' => [
        'product' => [
            'label' => 'Product',
            'hint' => 'Product',
            'help' => 'Update product settings in this section.',
            'groups' => [
                'product_title' => [
                    'label' => 'Product Name',
                    'help' => 'What should the product name include',
                ],
                'product_price' => [
                    'label' => 'Product Price',
                    'help' => 'Product price related settings',
                ],
                'product_detail' => [
                    'label' => 'Product Details',
                    'help' => 'Product details related settings',
                ],
            ],
            'items' => [
                'show_sku' => [
                    'label' => 'Show SKU',
                    'hint' => 'hint',
                    'help' => 'help',
                ],
                'show_attribute' => [
                    'label' => 'Show Attribute',
                    'hint' => '',
                    'help' => '',
                ],
                'show_brand' => [
                    'label' => 'Show Brand',
                    'hint' => '',
                    'help' => '',
                ],
                'show_category' => [
                    'label' => 'Show Category',
                    'hint' => '',
                    'help' => '',
                ],
                'show_out_of_stock_products_price' => [
                    'label' => 'Show Price for Out of Stock Products',
                    'hint' => '',
                    'help' => 'Show product price instead of out of stock text',
                ],
                'show_number_of_added_to_carts_in_product_detail' => [
                    'label' => 'Show Number Added to Other Shopping Carts in Product Details',
                    'hint' => '',
                    'help' => '',
                ],
                'show_number_of_sold_in_product_detail' => [
                    'label' => 'Show Sales Count in Product Details',
                    'hint' => '',
                    'help' => '',
                ],
            ],
        ],
        'general' => [
            'label' => 'Basic Settings',
            'hint' => 'Basic Settings',
            'help' => 'Update basic settings in this section.',
            'groups' => [
                'company' => [
                    'label' => 'Company Information',
                    'help' => 'Update company information such as address, contact number, etc. in this section.',
                ],
                'website' => [
                    'label' => 'Website Information',
                    'help' => 'Update website information such as logo, calendar, etc. in this section.',
                ],
            ],
            'items' => [
                'calendar_value' => [
                    'label' => 'System Calendar',
                    'hint' => 'Persian | Lunar | Gregorian',
                    'help' => 'Select system calendar',
                ],
                'logo' => [
                    'label' => 'Site Logo',
                    'hint' => 'Site Logo',
                    'help' => 'Upload your desired site logo from this section',
                ],
                'address' => [
                    'label' => 'Address',
                    'hint' => 'Mashhad...',
                    'help' => 'Enter company address',
                ],
                'tell' => [
                    'label' => 'Contact Number',
                    'hint' => '0531-1234567',
                    'help' => 'Enter company contact number',
                ],
                'name' => [
                    'label' => 'Company Name',
                    'hint' => 'Company Name',
                    'help' => 'Enter company name.',
                ],
                'postal_code' => [
                    'label' => 'Postal Code',
                    'hint' => '1234567899',
                    'help' => 'Enter company postal code',
                ],
                'phone' => [
                    'label' => 'Mobile Number',
                    'hint' => '09100000000',
                    'help' => 'Enter company mobile number',
                ],
                'email' => [
                    'label' => 'Email',
                    'hint' => 'example@gmail.com',
                    'help' => 'Enter company email',
                ],
                'latitude' => [
                    'label' => 'Latitude',
                    'hint' => '59.61602747',
                    'help' => 'Enter company latitude',
                ],
                'longitude' => [
                    'label' => 'Longitude',
                    'hint' => '36.28832538',
                    'help' => 'Enter company longitude',
                ],
                'bank_uuid' => [
                    'label' => 'Default Bank',
                    'hint' => '',
                    'help' => 'This bank will be displayed as the reference bank in your invoice',
                ],
                'media_id' => [
                    'label' => 'Default Image',
                    'hint' => 'Default Image',
                    'help' => 'Upload your default image here',
                ],
            ],
        ],
        'integration_sync' => [
            'label' => 'Software Integration',
            'hint' => 'Software Integration',
            'help' => 'Update integration settings in this section.',
            'groups' => [
                'mahak' => [
                    'label' => 'Mahak',
                    'help' => 'Mahak',
                ],
                'orash' => [
                    'label' => 'Orash',
                    'help' => 'Orash',
                ],
            ],
            'items' => [
                'status' => [
                    'label' => 'Status',
                    'hint' => '',
                    'help' => '',
                ],
                'url' => [
                    'label' => 'Server Address',
                    'hint' => '',
                    'help' => '',
                ],
                'user_name' => [
                    'label' => 'Username',
                    'hint' => '',
                    'help' => '',
                ],
                'password' => [
                    'label' => 'Password',
                    'hint' => '',
                    'help' => '',
                ],
                'code' => [
                    'label' => 'Code',
                    'hint' => '',
                    'help' => '',
                ],
            ],
        ],
        'notification' => [
            'label' => 'Notifications',
            'hint' => 'Notifications',
            'help' => 'Update notification settings in this section.',
            'groups' => [
                'order_create' => [
                    'label' => 'New Order Registration',
                    'help' => 'New Order Registration',
                ],
                'chat_create' => [
                    'label' => 'New Chat Creation',
                    'help' => 'New Chat Creation',
                ],
                'user_create' => [
                    'label' => 'New User Registration',
                    'help' => 'New User Registration',
                ],
            ],
            'items' => [
                'sms' => [
                    'label' => 'SMS',
                    'hint' => '',
                    'help' => '',
                ],
                'email' => [
                    'label' => 'Email',
                    'hint' => '',
                    'help' => '',
                ],
                'notification' => [
                    'label' => 'Notification',
                    'hint' => '',
                    'help' => '',
                ],
            ],
        ],
        'sale' => [
            'label' => 'Sales',
            'hint' => 'Sales',
            'help' => 'Update sales settings in this section.',
            'groups' => [
                'order' => [
                    'label' => 'Orders',
                    'help' => 'Update order settings in this section.',
                ],
                'cart' => [
                    'label' => 'Shopping Cart',
                    'help' => 'Update shopping cart settings in this section.',
                ],
                'discount' => [
                    'label' => 'Discount Code',
                    'help' => 'Update discount code settings in this section.',
                ],
                'payment' => [
                    'label' => 'Payment',
                    'help' => 'Update payment settings in this section.',
                ],
                'shipping' => [
                    'label' => 'Shipping',
                    'help' => 'Update shipping settings in this section.',
                ],
                'target' => [
                    'label' => 'Sales Target',
                    'help' => 'Update sales target in this section.',
                ],
            ],
            'items' => [
                'get_quantity_from_accounting' => [
                    'label' => 'Get Product Quantities from Accounting',
                    'hint' => 'Get Product Quantities from Accounting',
                    'help' => 'If you activate this section, product quantities will be retrieved from accounting',
                ],
                'order_return_days_limit' => [
                    'label' => 'Maximum Days Allowed for Return Order Registration',
                    'hint' => 'Maximum Days Allowed for Return Order Registration',
                    'help' => 'From the time of order payment, maximum how many days is it possible to return the order',
                ],
                'expiration_days_limit' => [
                    'label' => 'Maximum Days Allowed Until Order Expiration',
                    'hint' => 'Maximum Days Allowed Until Order Expiration',
                    'help' => 'From the time of order registration, maximum how many more days until the order expires',
                ],
                'need_to_approve_by_storekeeper' => [
                    'label' => 'Need Storekeeper Approval',
                    'hint' => 'Need Storekeeper Approval',
                    'help' => 'By activating this item when registering an order, the order status changes to need storekeeper approval, and if inactive, the order automatically goes to pending payment status',
                ],
                'cart_capacity' => [
                    'label' => 'Shopping Cart Completion Capacity',
                    'hint' => 'Shopping Cart Completion Capacity',
                    'help' => 'Cannot add more than the specified amount to the shopping cart',
                ],
                'apply_discount_code_only_for_cash_payment' => [
                    'label' => 'Apply Discount Code Only for Cash Payment',
                    'hint' => 'Apply Discount Code Only for Cash Payment',
                    'help' => '',
                ],
                'credit_card_deposit' => [
                    'label' => 'Card to Card Transfer',
                    'hint' => 'Card to Card Transfer',
                    'help' => 'Whether card to card transfer should be active or not',
                ],
                'free_shipping' => [
                    'label' => 'Free Shipping',
                    'hint' => 'Activate free shipping based on order amount',
                    'help' => 'Minimum order amount for free shipping',
                ],
                'monthly' => [
                    'label' => 'Monthly',
                    'hint' => '1',
                    'help' => 'Enter monthly sales target',
                ],
                'semi_annual' => [
                    'label' => 'Semi-Annual',
                    'hint' => '50000',
                    'help' => 'Enter semi-annual sales target',
                ],
                'yearly' => [
                    'label' => 'Yearly',
                    'hint' => '51245612',
                    'help' => 'Enter yearly sales target',
                ],
            ],
        ],
        'security' => [
            'label' => 'Security',
            'hint' => 'Security',
            'help' => 'Update security settings in this section.',
            'items' => [
                'captcha_handling' => [
                    'label' => 'Captcha Management',
                    'hint' => 'Activate and configure captcha',
                    'help' => 'For increased security, activate captcha and manage its settings here.',
                ],
            ],
        ],
        'seo_pages' => [
            'label' => 'SEO Pages',
            'help' => 'SEO settings for static pages such as blog and news lists.',
            'groups' => [
                'blog' => [
                    'label' => 'Blog List',
                    'help' => 'Settings for the blog list page',
                ],
                'news' => [
                    'label' => 'News List',
                    'help' => 'Settings for the news list page',
                ],
            ],
            'items' => [
                'title' => [
                    'label' => 'Title',
                    'help' => 'Page title used in the <title> tag.',
                ],
                'meta_description' => [
                    'label' => 'Meta Description',
                    'help' => 'Meta description for search engines.',
                ],
                'meta_keywords' => [
                    'label' => 'Meta Keywords',
                ],
                'robots' => [
                    'label' => 'Robots',
                ],
                'canonical' => [
                    'label' => 'Canonical URL',
                ],
                // compatibility keys referenced in validation messages
                'blog_title' => ['label' => 'Blog Title'],
                'news_title' => ['label' => 'News Title'],
            ],
        ],

        'site_data' => [
            'label' => 'Site Data',
            'help' => 'Settings related to content and lists across the site.',
            'groups' => [
                'homepage' => [
                    'label' => 'Homepage',
                    'help' => 'Settings for homepage sections',
                ],
                'lists' => [
                    'label' => 'Lists',
                    'help' => 'Pagination and list sizes',
                ],
                'sections' => [
                    'label' => 'Sections',
                    'help' => 'Toggle visibility of site sections',
                ],
            ],
            'items' => [
                'featured_count' => ['label' => 'Featured Items Count'],
                'show_featured' => ['label' => 'Show Featured Section'],
                'products_per_page' => ['label' => 'Products Per Page'],
                'news_per_page' => ['label' => 'News Per Page'],
                'blog_per_page' => ['label' => 'Blog Per Page'],
                'show_news_section' => ['label' => 'Show News Section'],
                'show_blog_section' => ['label' => 'Show Blog Section'],
            ],
        ],
    ],
];
