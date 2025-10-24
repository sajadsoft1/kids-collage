<?php

declare(strict_types=1);

return [
    // Global Settings
    'show_future_modules' => env('SHOW_FUTURE_MODULES', false),
    'translation'         => env('MODULE_TRANSLATION', false),
    'seo'                 => env('MODULE_SEO', false),

    // Education Management
    'education'           => [
        'courses'           => env('MODULE_EDUCATION_COURSES', true),
        'rooms'             => env('MODULE_EDUCATION_ROOMS', true),
        'terms'             => env('MODULE_EDUCATION_TERMS', true),
        'enrollments'       => env('MODULE_EDUCATION_ENROLLMENTS', true),
        'attendance'        => env('MODULE_EDUCATION_ATTENDANCE', true),
        'gradebook'         => env('MODULE_EDUCATION_GRADEBOOK', false),
        'assignments'       => env('MODULE_EDUCATION_ASSIGNMENTS', false),
        'exams'             => env('MODULE_EDUCATION_EXAMS', false),
        'report_cards'      => env('MODULE_EDUCATION_REPORT_CARDS', false),
        'certificates'      => env('MODULE_EDUCATION_CERTIFICATES', false),
        'curriculum'        => env('MODULE_EDUCATION_CURRICULUM', false),
        'parent_portal'     => env('MODULE_EDUCATION_PARENT_PORTAL', false),
        'student_portal'    => env('MODULE_EDUCATION_STUDENT_PORTAL', false),
        'events'            => env('MODULE_EDUCATION_EVENTS', false),
        'academic_calendar' => env('MODULE_EDUCATION_ACADEMIC_CALENDAR', false),
    ],

    // HRM - Human Resource Management
    'hrm'                 => [
        'users'         => env('MODULE_HRM_USERS', true),
        'employees'     => env('MODULE_HRM_EMPLOYEES', true),
        'teachers'      => env('MODULE_HRM_TEACHERS', true),
        'parents'       => env('MODULE_HRM_PARENTS', true),
        'roles'         => env('MODULE_HRM_ROLES', true),
        'payroll'       => env('MODULE_HRM_PAYROLL', false),
        'leave'         => env('MODULE_HRM_LEAVE', false),
        'performance'   => env('MODULE_HRM_PERFORMANCE', false),
        'time_tracking' => env('MODULE_HRM_TIME_TRACKING', false),
        'contracts'     => env('MODULE_HRM_CONTRACTS', false),
        'documents'     => env('MODULE_HRM_DOCUMENTS', false),
        'recruitment'   => env('MODULE_HRM_RECRUITMENT', false),
        'training'      => env('MODULE_HRM_TRAINING', false),
        'scheduling'    => env('MODULE_HRM_SCHEDULING', false),
    ],

    // Accounting Management
    'accounting'          => [
        'orders'               => env('MODULE_ACCOUNTING_ORDERS', true),
        'payments'             => env('MODULE_ACCOUNTING_PAYMENTS', true),
        'invoices'             => env('MODULE_ACCOUNTING_INVOICES', false),
        'installments'         => env('MODULE_ACCOUNTING_INSTALLMENTS', false),
        'expenses'             => env('MODULE_ACCOUNTING_EXPENSES', false),
        'income'               => env('MODULE_ACCOUNTING_INCOME', false),
        'financial_reports'    => env('MODULE_ACCOUNTING_FINANCIAL_REPORTS', false),
        'budget'               => env('MODULE_ACCOUNTING_BUDGET', false),
        'cash_flow'            => env('MODULE_ACCOUNTING_CASH_FLOW', false),
        'accounting_documents' => env('MODULE_ACCOUNTING_DOCUMENTS', false),
        'tax'                  => env('MODULE_ACCOUNTING_TAX', false),
        'cashbox'              => env('MODULE_ACCOUNTING_CASHBOX', false),
        'banks'                => env('MODULE_ACCOUNTING_BANKS', false),
    ],

    // CRM - Customer Relationship Management
    'crm'                 => [
        'boards'          => env('MODULE_CRM_BOARDS', true),
        'tickets'         => env('MODULE_CRM_TICKETS', true),
        'comments'        => env('MODULE_CRM_COMMENTS', true),
        'contact_us'      => env('MODULE_CRM_CONTACT_US', true),
        'discounts'       => env('MODULE_CRM_DISCOUNTS', true),
        'leads'           => env('MODULE_CRM_LEADS', false),
        'campaigns'       => env('MODULE_CRM_CAMPAIGNS', false),
        'email_marketing' => env('MODULE_CRM_EMAIL_MARKETING', false),
        'sms_marketing'   => env('MODULE_CRM_SMS_MARKETING', false),
        'sales_pipeline'  => env('MODULE_CRM_SALES_PIPELINE', false),
        'followups'       => env('MODULE_CRM_FOLLOWUPS', false),
        'segments'        => env('MODULE_CRM_SEGMENTS', false),
        'crm_reports'     => env('MODULE_CRM_REPORTS', false),
    ],

    // Content Management
    'content'             => [
        'blog'          => env('MODULE_CONTENT_BLOG', true),
        'portfolio'     => env('MODULE_CONTENT_PORTFOLIO', true),
        'pages'         => env('MODULE_CONTENT_PAGES', true),
        'categories'    => env('MODULE_CONTENT_CATEGORIES', true),
        'tags'          => env('MODULE_CONTENT_TAGS', true),
        'bulletins'     => env('MODULE_CONTENT_BULLETINS', true),
        'licenses'      => env('MODULE_CONTENT_LICENSES', true),
        'media_library' => env('MODULE_CONTENT_MEDIA_LIBRARY', false),
        'video_gallery' => env('MODULE_CONTENT_VIDEO_GALLERY', false),
        'podcasts'      => env('MODULE_CONTENT_PODCASTS', false),
        'ebooks'        => env('MODULE_CONTENT_EBOOKS', false),
        'downloads'     => env('MODULE_CONTENT_DOWNLOADS', false),
        'documentation' => env('MODULE_CONTENT_DOCUMENTATION', false),
        'newsletter'    => env('MODULE_CONTENT_NEWSLETTER', false),
    ],

    // Base Settings & Information
    'base_settings'       => [
        'sliders'                => env('MODULE_BASE_SLIDERS', true),
        'faqs'                   => env('MODULE_BASE_FAQS', true),
        'banners'                => env('MODULE_BASE_BANNERS', true),
        'clients'                => env('MODULE_BASE_CLIENTS', true),
        'teammates'              => env('MODULE_BASE_TEAMMATES', true),
        'social_media'           => env('MODULE_BASE_SOCIAL_MEDIA', true),
        'opinions'               => env('MODULE_BASE_OPINIONS', true),
        'system_settings'        => env('MODULE_BASE_SYSTEM_SETTINGS', false),
        'notification_templates' => env('MODULE_BASE_NOTIFICATION_TEMPLATES', false),
        'email_templates'        => env('MODULE_BASE_EMAIL_TEMPLATES', false),
        'sms_templates'          => env('MODULE_BASE_SMS_TEMPLATES', false),
        'activity_logs'          => env('MODULE_BASE_ACTIVITY_LOGS', false),
        'backup'                 => env('MODULE_BASE_BACKUP', false),
        'languages'              => env('MODULE_BASE_LANGUAGES', false),
        'seo_settings'           => env('MODULE_BASE_SEO_SETTINGS', false),
    ],
];
