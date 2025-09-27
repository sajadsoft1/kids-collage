<?php

declare(strict_types=1);

return [
    'model'         => 'Course',
    'permissions'   => [
        'view_course'   => 'View Course',
        'create_course' => 'Create Course',
        'update_course' => 'Update Course',
        'delete_course' => 'Delete Course',
    ],
    'exceptions'    => [
        'course_not_found'     => 'Course not found',
        'course_access_denied' => 'Access to course denied',
    ],
    'validations'   => [
        'title_required'       => 'Course title is required',
        'description_required' => 'Course description is required',
        'price_required'       => 'Course price is required',
        'start_date_required'  => 'Course start date is required',
        'end_date_required'    => 'Course end date is required',
        'end_date_after_start' => 'End date must be after start date',
    ],
    'enum'          => [
        'type' => [
            'in-person' => 'In-Person',
            'online'    => 'Online',
            'hybrid'    => 'Hybrid',
        ],
    ],
    'notifications' => [
        'course_created' => 'Course created successfully',
        'course_updated' => 'Course updated successfully',
        'course_deleted' => 'Course deleted successfully',
    ],
    'page'          => [
        'index_title'  => 'Courses List',
        'create_title' => 'Create New Course',
        'edit_title'   => 'Edit Course',
        'show_title'   => 'Course Details',
    ],
];
