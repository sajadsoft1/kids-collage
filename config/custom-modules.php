<?php

declare(strict_types=1);

return [
    'translation' => env('MODULE_TRANSLATION', false),
    'seo'         => env('MODULE_SEO', false),
    'content'     => [
        'blog'     => env('MODULE_CONTENT_BLOG', true),
        'category' => env('MODULE_CONTENT_CATEGORY', true),
        'tag'      => env('MODULE_CONTENT_TAG', true),
        'comment'  => env('MODULE_CONTENT_COMMENT', true),
    ],
    'crm'         => [
        'client'      => env('MODULE_CRM_CLIENT', false),
        'lead'        => env('MODULE_CRM_LEAD', false),
        'contact'     => env('MODULE_CRM_CONTACT', false),
        'opportunity' => env('MODULE_CRM_OPPORTUNITY', false),
        'quote'       => env('MODULE_CRM_QUOTE', false),
        'invoice'     => env('MODULE_CRM_INVOICE', false),
        'payment'     => env('MODULE_CRM_PAYMENT', false),
        'task'        => env('MODULE_CRM_TASK', false),
        'event'       => env('MODULE_CRM_EVENT', false),
        'note'        => env('MODULE_CRM_NOTE', false),
        'document'    => env('MODULE_CRM_DOCUMENT', false),
        'call'        => env('MODULE_CRM_CALL', false),
        'email'       => env('MODULE_CRM_EMAIL', false),
        'sms'         => env('MODULE_CRM_SMS', false),
    ],
    'school'      => [
        'student'   => env('MODULE_SCHOOL_STUDENT', true),
        'teacher'   => env('MODULE_SCHOOL_TEACHER', true),
        'class'     => env('MODULE_SCHOOL_CLASS', true),
        'subject'   => env('MODULE_SCHOOL_SUBJECT', true),
        'schedule'  => env('MODULE_SCHOOL_SCHEDULE', false),
        'timetable' => env('MODULE_SCHOOL_TIMETABLE', false),
    ],
    'library'     => [
        'book'      => env('MODULE_LIBRARY_BOOK', false),
        'author'    => env('MODULE_LIBRARY_AUTHOR', false),
        'publisher' => env('MODULE_LIBRARY_PUBLISHER', false),
        'category'  => env('MODULE_LIBRARY_CATEGORY', false),
    ],
    'inventory'   => [
        'product'  => env('MODULE_INVENTORY_PRODUCT', false),
        'category' => env('MODULE_INVENTORY_CATEGORY', false),
        'supplier' => env('MODULE_INVENTORY_SUPPLIER', false),
        'customer' => env('MODULE_INVENTORY_CUSTOMER', false),
    ],
    'finance'     => [
        'account'     => env('MODULE_FINANCE_ACCOUNT', false),
        'transaction' => env('MODULE_FINANCE_TRANSACTION', false),
        'report'      => env('MODULE_FINANCE_REPORT', false),
    ],
    'hr'          => [
        'employee'   => env('MODULE_HR_EMPLOYEE', false),
        'department' => env('MODULE_HR_DEPARTMENT', false),
        'position'   => env('MODULE_HR_POSITION', false),
        'leave'      => env('MODULE_HR_LEAVE', false),
        'attendance' => env('MODULE_HR_ATTENDANCE', false),
    ],
    'marketing'   => [
        'campaign' => env('MODULE_MARKETING_CAMPAIGN', false),
        'lead'     => env('MODULE_MARKETING_LEAD', false),
        'contact'  => env('MODULE_MARKETING_CONTACT', false),
        'event'    => env('MODULE_MARKETING_EVENT', false),
    ],
    'project'     => [
        'project'   => env('MODULE_PROJECT_PROJECT', false),
        'task'      => env('MODULE_PROJECT_TASK', false),
        'milestone' => env('MODULE_PROJECT_MILESTONE', false),
        'document'  => env('MODULE_PROJECT_DOCUMENT', false),
    ],
    'support'     => [
        'ticket'   => env('MODULE_SUPPORT_TICKET', false),
        'message'  => env('MODULE_SUPPORT_MESSAGE', false),
        'status'   => env('MODULE_SUPPORT_STATUS', false),
        'priority' => env('MODULE_SUPPORT_PRIORITY', false),
    ],
];
