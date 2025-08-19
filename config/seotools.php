<?php

declare(strict_types=1);
/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'inertia'   => false,
    'meta'      => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'              => [
            'title'       => 'آکادمی طراحی سایت کارنو | آموزش، مشاوره و خدمات طراحی سایت',
            'titleBefore' => true,
            'description' => 'آکادمی کارنو ارائه‌دهنده آموزش‌های تخصصی طراحی سایت، فروش دوره‌های آموزشی و خدمات مشاوره و پیاده‌سازی وبسایت برای کسب‌وکارها و افراد.',
            'separator'   => ' | ',
            'keywords'    => ['آموزش طراحی سایت', 'دوره طراحی سایت', 'خدمات طراحی سایت', 'آموزش وردپرس', 'آموزش برنامه نویسی', 'فروش دوره آنلاین', 'مشاوره کسب و کار اینترنتی'],
            'canonical'   => 'full',
            'robots'      => 'index,follow',
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags'        => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => 'آکادمی طراحی سایت کارنو',
            'description' => 'آموزش تخصصی طراحی سایت، فروش دوره‌های آموزشی و ارائه خدمات مشاوره و پیاده‌سازی وبسایت.',
            'url'         => null,
            'type'        => 'website',
            'site_name'   => 'آکادمی کارنو',
            'images'      => ['assets/images/default/og-image.jpg'],
        ],
    ],
    'twitter'   => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            // 'card'        => 'summary',
            // 'site'        => '@LuizVinicius73',
        ],
    ],
    'json-ld'   => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title'       => 'آکادمی طراحی سایت کارنو',
            'description' => 'آموزش تخصصی طراحی سایت، فروش دوره‌های آموزشی و ارائه خدمات مشاوره و پیاده‌سازی وبسایت.',
            'url'         => null,
            'type'        => 'WebPage',
            'images'      => ['assets/images/default/og-image.jpg'],
        ],
    ],
];
