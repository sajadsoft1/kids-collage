<?php

declare(strict_types=1);

use App\Enums\NotificationChannelEnum;
use App\Enums\NotificationEventEnum;

$projectName = 'Kids Collage';

return [
    'data' => [
        // Authentication Events
        [
            'event' => NotificationEventEnum::AUTH_REGISTER,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Registration Successful',
                    'subtitle' => 'Your registration has been completed',
                    'body' => 'Hi {{user_name}}, welcome to Kids Collage! Your registration has been completed successfully.',
                    'cta' => [
                        'label' => 'Access Account',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::AUTH_CONFIRM,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Account Verification',
                    'subtitle' => 'Enter the verification code to activate your account',
                    'body' => 'Hi {{user_name}}, your verification code is {{verification_code}}. This code is valid for 10 minutes.',
                    'cta' => [
                        'label' => 'Verify Account',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'verification_code',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::AUTH_FORGOT_PASSWORD,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Password Reset',
                    'subtitle' => 'Password reset request',
                    'body' => 'Hi {{user_name}}, a password reset request has been submitted for your account. This link is valid for 60 minutes.',
                    'cta' => [
                        'label' => 'Reset Password',
                        'url' => '{{reset_password_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'reset_password_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::AUTH_WELCOME,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Welcome',
                    'subtitle' => 'Welcome to Kids Collage',
                    'body' => 'Hi {{user_name}}, welcome to Kids Collage! You can now use all platform features.',
                    'cta' => [
                        'label' => 'Get Started',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'action_url',
                    ],
                ],
            ],
        ],
        // Order Events
        [
            'event' => NotificationEventEnum::ORDER_CREATED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'New Order',
                    'subtitle' => 'Your order has been placed successfully',
                    'body' => 'Hi {{user_name}}, your order #{{order_number}} has been placed successfully. Total amount: ${{order_amount}}. Please complete your payment.',
                    'cta' => [
                        'label' => 'Pay Order',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'order_number',
                        'order_amount',
                        'order_date',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::ORDER_PAID,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Payment Successful',
                    'subtitle' => 'Your order payment has been completed',
                    'body' => 'Hi {{user_name}}, payment for your order #{{order_number}} has been completed successfully. Your access to purchased courses has been activated.',
                    'cta' => [
                        'label' => 'View Courses',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'order_number',
                        'payment_amount',
                        'transaction_id',
                        'payment_date',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::ORDER_CANCELLED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Order Cancelled',
                    'subtitle' => 'Your order has been cancelled',
                    'body' => 'Hi {{user_name}}, unfortunately your order #{{order_number}} has been cancelled. If payment was made, the amount will be refunded.',
                    'cta' => [
                        'label' => 'View Details',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'order_number',
                        'cancellation_reason',
                        'action_url',
                    ],
                ],
            ],
        ],
        // Payment Events
        [
            'event' => NotificationEventEnum::PAYMENT_SUCCESS,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Payment Successful',
                    'subtitle' => 'Your payment has been completed',
                    'body' => 'Hi {{user_name}}, your payment of ${{payment_amount}} has been completed successfully. Transaction ID: {{transaction_id}}',
                    'cta' => [
                        'label' => 'View Content',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'payment_amount',
                        'transaction_id',
                        'payment_date',
                        'payment_method',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::PAYMENT_FAILED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Payment Failed',
                    'subtitle' => 'Your payment was not completed',
                    'body' => 'Hi {{user_name}}, unfortunately your payment was not completed. Please try again or contact our support team if you encounter any issues.',
                    'cta' => [
                        'label' => 'Try Again',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'failure_reason',
                        'action_url',
                    ],
                ],
            ],
        ],
        // Enrollment Events
        [
            'event' => NotificationEventEnum::ENROLLMENT_CREATED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Course Enrollment',
                    'subtitle' => 'Your enrollment request has been submitted',
                    'body' => 'Hi {{user_name}}, your enrollment request for "{{course_title}}" has been submitted. This request is under review.',
                    'cta' => [
                        'label' => 'Check Status',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'course_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::ENROLLMENT_APPROVED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Enrollment Approved',
                    'subtitle' => 'Your course enrollment has been approved',
                    'body' => 'Hi {{user_name}}, great news! Your enrollment in "{{course_title}}" has been approved. You now have access to all course content.',
                    'cta' => [
                        'label' => 'Start Course',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'course_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::ENROLLMENT_REJECTED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Enrollment Rejected',
                    'subtitle' => 'Your course enrollment has been rejected',
                    'body' => 'Hi {{user_name}}, unfortunately your enrollment in "{{course_title}}" has been rejected. If you need more information, please contact our support team.',
                    'cta' => [
                        'label' => 'View Courses',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'course_title',
                        'rejection_reason',
                        'action_url',
                    ],
                ],
            ],
        ],
        // Course Events
        [
            'event' => NotificationEventEnum::COURSE_SESSION_REMINDER,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Session Reminder',
                    'subtitle' => 'Upcoming session reminder',
                    'body' => 'Hi {{user_name}}, this is a reminder that the session for "{{course_title}}" will start soon. Date: {{session_date}} - Time: {{session_time}}',
                    'cta' => [
                        'label' => 'Join Session',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'course_title',
                        'session_date',
                        'session_time',
                        'session_duration',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::COURSE_SESSION_STARTED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Session Started',
                    'subtitle' => 'The course session has started',
                    'body' => 'Hi {{user_name}}, the session for "{{course_title}}" has now started. Please join the session.',
                    'cta' => [
                        'label' => 'Join Session',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'course_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::COURSE_SESSION_ENDED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Session Ended',
                    'subtitle' => 'The course session has ended',
                    'body' => 'Hi {{user_name}}, the session for "{{course_title}}" has ended. You can view details, recorded video, and additional materials.',
                    'cta' => [
                        'label' => 'View Details',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'course_title',
                        'action_url',
                    ],
                ],
            ],
        ],
        // Ticket Events
        [
            'event' => NotificationEventEnum::TICKET_CREATED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'New Ticket',
                    'subtitle' => 'Your ticket has been created successfully',
                    'body' => 'Hi {{user_name}}, your ticket #{{ticket_number}} has been created successfully. Subject: {{ticket_subject}}',
                    'cta' => [
                        'label' => 'View Ticket',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'ticket_number',
                        'ticket_subject',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::TICKET_REPLIED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Ticket Reply',
                    'subtitle' => 'New reply to your ticket',
                    'body' => 'Hi {{user_name}}, a new reply has been sent to your ticket #{{ticket_number}}.',
                    'cta' => [
                        'label' => 'View Reply',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'ticket_number',
                        'reply_message',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::TICKET_RESOLVED,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Ticket Resolved',
                    'subtitle' => 'Your ticket has been resolved',
                    'body' => 'Hi {{user_name}}, your ticket #{{ticket_number}} has been resolved. If you need further assistance, you can create a new ticket.',
                    'cta' => [
                        'label' => 'View Ticket',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'ticket_number',
                        'action_url',
                    ],
                ],
            ],
        ],
        // General Events
        [
            'event' => NotificationEventEnum::ANNOUNCEMENT,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'New Announcement',
                    'subtitle' => '{{announcement_title}}',
                    'body' => 'Hi {{user_name}}, new announcement: {{announcement_title}}',
                    'cta' => [
                        'label' => 'View Announcement',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'announcement_title',
                        'announcement_body',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::SYSTEM_ALERT,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'System Alert',
                    'subtitle' => '{{alert_title}}',
                    'body' => 'Hi {{user_name}}, system alert: {{alert_message}}',
                    'cta' => [
                        'label' => 'View Details',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'alert_title',
                        'alert_message',
                        'action_url',
                    ],
                ],
            ],
        ],
        [
            'event' => NotificationEventEnum::BIRTHDAY_REMINDER,
            'channel' => NotificationChannelEnum::DATABASE,
            'data' => [
                'en' => [
                    'subject' => null,
                    'title' => 'Happy Birthday!',
                    'subtitle' => 'Happy Birthday, {{user_name}}!',
                    'body' => 'Hi {{user_name}}, happy birthday! 🎉 We hope your new year is full of success, joy, and learning.',
                    'cta' => [
                        'label' => 'Claim Gift',
                        'url' => '{{action_url}}',
                    ],
                    'placeholders' => [
                        'user_name',
                        'birthday_gift',
                        'action_url',
                    ],
                ],
            ],
        ],
    ],
];
