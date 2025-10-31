<?php

declare(strict_types=1);

return [
    'show'                                 => 'Show',
    'create'                               => 'Create',
    'edit'                                 => 'Edit',
    'delete'                               => 'Delete',
    'back'                                 => 'Back',
    'submit'                               => 'Submit',
    'cancel'                               => 'Cancel',

    'please_select_an_option'              => 'Please select an option',
    'yes'                                  => 'Yes',
    'no'                                   => 'No',
    'active'                               => 'Active',
    'inactive'                             => 'Inactive',

    'translation_has_updated_successfully' => 'Translation has been updated successfully',

    'model_has_stored_successfully'        => ':model has been stored successfully',
    'model_has_updated_successfully'       => ':model has been updated successfully',
    'model_has_deleted_successfully'       => ':model has been deleted successfully',
    'model_has_toggled_successfully'       => ':model has been toggled successfully',
    'model_has_upload_successfully'        => ':model has been uploaded successfully',
    'model_has_Failed_to_upload'           => ':model has failed to upload',
    'model_has_retrieved_successfully'     => ':model has been retrieved successfully',
    'model_has_Failed_to_store'            => ':model has failed to store',
    'store_success'                        => 'Store :model successfully',
    'store_failed'                         => 'Store :model failed, please report the problem',
    'update_success'                       => 'Update :model successfully',
    'update_failed'                        => 'Update :model failed, please report the problem',
    'delete_success'                       => 'Delete :model successfully',
    'delete_failed'                        => 'Delete :model failed, please report the problem',
    'delete_can_not'                       => 'You do not have permission to delete :model',
    'toggle_success'                       => 'Toggle :model successfully',
    'toggle_failed'                        => 'Toggle :model failed, please report the problem',
    'toggle_can_not'                       => 'You do not have permission to toggle :model',
    'like_add'                             => 'Like store success',
    'like_remove'                          => 'Like removed',
    'to_do_action_please_login'            => 'Please login to handle action',
    'model_has_not_set'                    => 'model has not set.',
    'menu'                                 => [
        'index' => ':model s',
    ],

    'page'                                 => [
        'index'   => [
            'page_title' => ':model s',
            'title'      => 'All :model',
            'desc'       => 'All :model',
            'create'     => 'Create :model',
        ],
        'create'  => [
            'page_title' => 'Create :model',
            'title'      => 'Create :model',
            'desc'       => 'Please be sure to get the approval of the person in charge of content production to register a new item',
        ],
        'edit'    => [
            'page_title' => 'Edit :model',
            'title'      => 'Edit :model',
            'desc'       => 'Please be sure to have the approval of the person responsible for content production to edit this item',
        ],
        'show'    => [
            'page_title' => 'Details :model',
            'title'      => ':model :name',
            'desc'       => 'Details of :model :name',
        ],
        'public'  => [
            'info'         => 'Information',
            'addresses'    => 'Addresses',
            'security'     => 'Security',
            'ticket_reply' => 'Reply to ticket',
            'permissions'  => 'Permissions',
            'exceptions'   => 'Exceptions',
            'rules'        => 'Rules',
        ],
        'buttons' => [
            'add' => 'Add',
        ],
    ],

    'clients'                              => [
        'SHOP_APPLICATION'       => 'SHOP_APPLICATION',
        'ADMIN_SHOP_APPLICATION' => 'ADMIN_SHOP_APPLICATION',
        'SHOP_WEBSITE'           => 'SHOP_WEBSITE',
        'SHOP_PANEL_ADMIN'       => 'SHOP_PANEL_ADMIN',
    ],

    'exceptions'                           => [
        'bad_request' => 'Bad request',
        'not_allowed' => 'Not allowed',
    ],

    'page_sections'                        => [
        'data'                 => 'Information',
        'seo_options'          => 'SEO settings',
        'upload_image'         => 'Upload image',
        'publish_config'       => 'Publish settings',
        'end_of_work_settings' => 'End of work settings',
    ],

    'signs'                                => [
        '='  => 'Equal',
        '>'  => 'Greater than',
        '<'  => 'Less than',
        '>=' => 'Greater than or equal',
        '<=' => 'Less than or equal',
    ],

    'calendar'                             => [
        'persian'   => 'Persian Calendar',
        'gregorian' => 'Gregorian Calendar',
        'hijri'     => 'Hijri Calendar',
    ],

    'notification_template'                => 'Notification Template',

    'channels'                             => [
        'sms'          => 'SMS',
        'email'        => 'Email',
        'notification' => 'In-App Notification',
    ],

    'languages'                            => [
        'en' => 'English',
        'fa' => 'Persian (Farsi)',
    ],

    'notification_template_hints'          => [
        'name'             => 'A unique name to identify this template',
        'channel'          => 'Select the communication channel for this template',
        'message_template' => 'Write your message with variables in curly braces, e.g., {user_name}',
        'languages'        => 'Select which languages this template supports',
        'inputs'           => 'Define variables that can be used in the template',
        'inputs_example'   => 'Example: user_name, order_id, course_name',
        'published'        => 'Enable or disable this template',
    ],

    'available_variables'                  => 'Available Variables',
    'example'                              => 'Example',

    'variables'                            => [
        'user_name'   => 'User name',
        'user_email'  => 'User email address',
        'order_id'    => 'Order ID',
        'course_name' => 'Course name',
    ],
];
