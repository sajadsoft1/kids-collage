<?php

declare(strict_types=1);

return [
    'general' => [
        'user_model' => env('TICKET_CHAT_USER_MODEL', 'App\\Models\\User'),
        'branch_model' => env('TICKET_CHAT_BRANCH_MODEL', 'App\\Models\\Branch'),
        'table_prefix' => env('TICKET_CHAT_TABLE_PREFIX', 'tc_'),
        'database_connection' => env('TICKET_CHAT_DB_CONNECTION', null),
        'route_prefix' => null,
    ],

    'features' => [
        'direct_messages' => true,
        'groups' => true,
        'channels' => true,
        'departments' => true,
        'branches' => env('TICKET_CHAT_BRANCHES', true),
        'file_uploads' => true,
        'canned_responses' => true,
        'internal_notes' => true,
        'tags' => true,
        'csat' => true,
        'sla' => false,
        'auto_close' => true,
        'activity_log' => true,
    ],

    'broadcasting' => [
        'enabled' => env('TICKET_CHAT_BROADCASTING', true),
        'driver' => env('BROADCAST_DRIVER', 'reverb'),
        'events' => [
            'message_sent' => true,
            'message_read' => true,
            'conversation_closed' => true,
            'conversation_assigned' => true,
            'typing_indicator' => true,
            'participant_joined' => true,
            'participant_left' => true,
            'new_ticket_created' => true,
        ],
    ],

    'tickets' => [
        'priorities' => ['low', 'medium', 'high', 'urgent'],
        'statuses' => ['open', 'pending', 'in_progress', 'resolved', 'closed', 'archived'],
        'default_priority' => 'medium',
        'default_status' => 'open',
        'auto_close_days' => 7,
        'max_open_per_user' => 10,
        'ticket_number_prefix' => 'TKT-',
        'ticket_number_length' => 8,
    ],

    'messages' => [
        'max_length' => 5000,
        'rate_limit' => 30,
        'allow_edit' => true,
        'edit_window_minutes' => 15,
        'allow_delete' => false,
    ],

    'attachments' => [
        'disk' => env('TICKET_CHAT_DISK', 'public'),
        'path' => 'ticket-chat/attachments',
        'max_size_mb' => 10,
        'max_files_per_message' => 5,
        'allowed_mimes' => [
            'jpg', 'jpeg', 'png', 'gif', 'webp',
            'pdf', 'doc', 'docx', 'xls', 'xlsx',
            'zip', 'rar', 'txt',
        ],
        'generate_thumbnails' => true,
        'thumbnail_size' => [200, 200],
    ],

    'groups' => [
        'max_members' => 50,
        'who_can_create' => 'everyone',
        'allow_leave' => true,
    ],

    'channels' => [
        'who_can_create' => 'admin',
        'who_can_post' => 'admin_only',
        'allow_unsubscribe' => true,
    ],

    'notifications' => [
        'enabled' => true,
        'channels' => ['mail', 'database'],
        'events' => [
            'ticket_created' => true,
            'ticket_replied' => true,
            'ticket_assigned' => true,
            'ticket_status_changed' => true,
            'ticket_closed' => true,
            'mention' => true,
            'sla_breach_warning' => true,
        ],
    ],

    'sla' => [
        'enabled' => false,
        'response_times' => [
            'urgent' => 60,
            'high' => 240,
            'medium' => 1440,
            'low' => 4320,
        ],
        'resolution_times' => [
            'urgent' => 480,
            'high' => 1440,
            'medium' => 2880,
            'low' => 10080,
        ],
        'business_hours_only' => true,
    ],

    'rate_limiting' => [
        'enabled' => true,
        'max_messages_per_minute' => 30,
        'max_tickets_per_day' => 5,
        'cooldown_between_tickets' => 60,
    ],

    'csat' => [
        'enabled' => true,
        'rating_scale' => 5,
        'allow_comment' => true,
        'send_after_close' => true,
        'auto_send_delay_hours' => 1,
    ],
];
