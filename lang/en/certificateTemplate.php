<?php

declare(strict_types=1);

return [
    'model' => 'Certificate Template',
    'layout' => [
        'classic' => 'Classic',
        'minimal' => 'Minimal',
        'custom' => 'Custom',
    ],
    'placeholders' => [
        'student_name' => 'Student name',
        'course_title' => 'Course title',
        'course_level' => 'Course level',
        'issue_date' => 'Issue date',
        'grade' => 'Grade',
        'certificate_number' => 'Certificate number',
        'duration' => 'Duration',
        'institute_name' => 'Institute name',
    ],
    'cannot_delete_default' => 'Cannot delete the default certificate template.',
    'download_not_found' => 'Certificate file not found.',
    'verification' => 'Certificate Verification',
    'verification_valid' => 'This certificate is valid.',
    'verification_invalid' => 'This certificate could not be verified.',
    'placeholder_hint' => 'Use {{student_name}}, {{course_title}}, {{issue_date}}, etc.',
    'page' => [
        'index_title' => 'Certificate Templates',
        'create_title' => 'Create Certificate Template',
        'edit_title' => 'Edit Certificate Template',
        'title' => 'Title',
        'slug' => 'Slug',
        'is_default' => 'Default template',
        'layout' => 'Layout',
        'header_text' => 'Header text',
        'body_text' => 'Body text',
        'footer_text' => 'Footer text',
        'institute_name' => 'Institute name',
        'logo' => 'Logo',
        'background' => 'Background image',
        'signature' => 'Signature image',
        'preview' => 'Preview',
    ],
];
