<?php

declare(strict_types=1);

return [
    'config'            => 'پیکربندی سئو',
    'reports'           => 'گزارشات سئو',
    'comments'          => 'نظرات',
    'wished'            => 'لایک ها',
    'views'             => 'بازدیدها',
    'stats'             => [
        'views'    => 'بازدیدها',
        'likes'    => 'لایک ها',
        'comments' => 'نظرات',
        'wishes'   => 'علاقه مندی ها',
    ],
    'stats_report'      => [
        '1'  => 'ماه گذشته',
        '3'  => '3 ماه گذشته',
        '6'  => '6 ماه گذشته',
        '12' => '12 ماه گذشته',
    ],
    'months'            => [
        'month_1'  => '1 ماه',
        'month_3'  => '3 ماه',
        'month_6'  => '6 ماه',
        'month_12' => '12 ماه',
    ],
    'charts'            => [
        'views'    => 'نمودار بازدیدها',
        'likes'    => 'نمودار لایک ها',
        'comments' => 'نمودار نظرات',
        'wishes'   => 'نمودار علاقه مندی ها',
        'empty'    => 'هیچ داده ای برای نمایش وجود ندارد',
    ],
    'lists'             => [
        'comments' => 'لیست نظرات',
        'wishes'   => 'لیست علاقه مندی ها',
        'views'    => 'لیست بازدیدها',
    ],
    'from_date_to_date' => 'از تاریخ :from تا تاریخ :to',
    'robots_type'       => [
        'index_follow'     => [
            'title' => 'Index, Follow',
            'hint'  => 'نمایه سازی و دنبال کردن لینک ها (index, follow)',
        ],
        'noindex_nofollow' => [
            'title' => 'Noindex, Nofollow',
            'hint'  => 'عدم نمایه سازی و عدم دنبال کردن لینک ها (noindex, nofollow)',
        ],
        'noindex_follow'   => [
            'title' => 'Noindex, Follow',
            'hint'  => 'عدم نمایه سازی و دنبال کردن لینک ها (noindex, follow)',
        ],
    ],
];
