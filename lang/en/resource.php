<?php

declare(strict_types=1);

return [
    'model'         => 'Resource',
    'type'          => 'Resource Type',
    'attached_to'   => 'Attached To',
    'url'           => 'URL',
    'file'          => 'File',
    'order'         => 'Display Order',
    'is_public'     => 'Is Public',
    'description'   => 'Description',
    'permissions'   => [
    ],
    'exceptions'    => [
    ],
    'validations'   => [
    ],
    'enum'          => [
        'pdf'   => 'PDF Document',
        'video' => 'Video',
        'image' => 'Image',
        'audio' => 'Audio',
        'file'  => 'File',
        'link'  => 'Link',
    ],
    'notifications' => [
    ],
    'page'          => [
    ],
];
