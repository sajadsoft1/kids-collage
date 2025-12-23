<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Sidebar Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the Frest sidebar component including widths
    | for collapsed and expanded states.
    |
    */

    'sidebar' => [
        'width' => [
            'collapsed' => 20, // Tailwind class: w-20 (5rem = 80px)
            'expanded' => 72,  // Tailwind class: w-72 (18rem = 288px)
        ],
    ],
];
