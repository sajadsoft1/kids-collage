<?php

declare(strict_types=1);

return [
    'model' => 'User',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'validation' => [
        'at_least_one_parent_required' => 'At least one of father or mother must be selected.',
    ],
    'enum' => [
    ],
    'notifications' => [
    ],
    'page' => [
        'resume_section' => 'Resume',
        'resume_description' => 'Resume description',
        'resume_image' => 'Resume image',
        'education_section' => 'Education certificates',
        'education_description' => 'Education certificates description',
        'education_image' => 'Education certificates image',
        'courses_section' => 'Completed courses',
        'courses_description' => 'Completed courses description',
        'courses_image' => 'Completed courses image',
        'no_image' => 'No image selected',
        'tabs' => [
            'basic' => 'Basic info',
            'images' => 'Images',
            'parents' => 'Parents / Children',
            'salary' => 'Salary & cooperation',
            'resume' => 'Resume & certificates',
            'settings' => 'Settings',
        ],
    ],
];
