<?php

declare(strict_types=1);

return [
    '401' => [
        'title' => 'Authentication Required',
        'message' => 'You need to log in to access this page.',
        'description' => 'You must first log in to the system to access this page.',
    ],
    '403' => [
        'title' => 'Access Forbidden',
        'message' => 'You do not have permission to access this page.',
        'description' => 'Unfortunately, you do not have the necessary permissions to view this content. If needed, contact the system administrator.',
    ],
    '404' => [
        'title' => 'Page Not Found',
        'message' => 'The page you are looking for does not exist or has been removed.',
        'description' => 'The entered address is incorrect or the page has been moved to another location.',
    ],
    '419' => [
        'title' => 'Session Expired',
        'message' => 'Your session has expired. Please try again.',
        'description' => 'For security reasons, your session becomes inactive after a while. Please refresh the page and try again.',
    ],
    '429' => [
        'title' => 'Too Many Requests',
        'message' => 'You have sent too many requests. Please wait a moment.',
        'description' => 'To prevent abuse, the number of requests is limited. Please wait a moment and try again.',
    ],
    '500' => [
        'title' => 'Server Error',
        'message' => 'Unfortunately, an error occurred on the server.',
        'description' => 'We are investigating the issue. Please wait a moment and try again.',
    ],
    '503' => [
        'title' => 'Service Unavailable',
        'message' => 'The service is currently unavailable.',
        'description' => 'We are performing maintenance or updates. Please try again later.',
    ],
    'back_to_dashboard' => 'Back to Dashboard',
    'back_to_home' => 'Back to Home',
    'go_back' => 'Go Back',
    'try_again' => 'Try Again',
];
