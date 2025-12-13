<?php

declare(strict_types=1);

return [
    'model' => 'Resource',
    'type' => 'Resource Type',
    'attached_to' => 'Attached To',
    'url' => 'URL',
    'file' => 'File',
    'order' => 'Display Order',
    'is_public' => 'Is Public',
    'description' => 'Description',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'enum' => [
        'pdf' => 'PDF Document',
        'video' => 'Video',
        'image' => 'Image',
        'audio' => 'Audio',
        'file' => 'File',
        'link' => 'Link',
    ],
    'notifications' => [
    ],
    'page' => [
        'session_resources' => 'Session Resources',
        'resource' => 'Resource',
        'loading_resource' => 'Loading resource...',
        'description' => 'Description',
        'browser_no_video_support' => 'Your browser does not support video playback',
        'browser_no_audio_support' => 'Your browser does not support audio playback',
        'open_link' => 'Open Link',
        'download_file' => 'Download File',
        'view' => 'View',
        'loading' => 'Loading...',
        'no_resources_for_session' => 'No resources have been defined for this session',
    ],
    'attached_resources' => 'Attached Resources',
    'add_relationship' => 'Add Relationship',
    'no_relationships' => 'No relationships defined',
    'click_add_to_create' => 'Click the add button to create a new relationship',
];
