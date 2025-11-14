<?php

declare(strict_types=1);

return [
    'model' => 'Notification Template',
    'fields' => [
        'event' => 'Event',
        'channel' => 'Channel',
        'locale' => 'Locale',
        'name' => 'Template name',
        'icon' => 'Icon',
        'subject' => 'Subject',
        'title' => 'Title',
        'subtitle' => 'Short description',
        'body' => 'Body',
        'placeholders' => 'Placeholders',
        'cta_label' => 'Button label',
        'cta_url' => 'Button link',
        'is_active' => 'Active',
    ],
    'hints' => [
        'icon' => 'Provide a Lucide/Heroicon class name (e.g. o-bell).',
        'subject' => 'Used for email notifications; optional for other channels.',
        'placeholders' => 'List the variables you use in the body without braces (e.g. user_name).',
    ],
    'examples' => [
        'body_hint' => 'Hello {user_name}, your request has been processed successfully.',
    ],
    'messages' => [
        'duplicate_event_channel_locale' => 'A template already exists for this event, channel and locale.',
    ],
];
