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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Registration Successful',
                    'title' => 'Registration Successful',
                    'subtitle' => 'Your registration has been completed',
                    'body' => 'Hi {{user_name}},

Welcome to Kids Collage! Your registration has been completed successfully.

To access your account and start using our services, click on the link below:
{{action_url}}

If you encounter any issues, please contact our support team.

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Account Verification Code',
                    'title' => 'Account Verification',
                    'subtitle' => 'Enter the verification code to activate your account',
                    'body' => 'Hi {{user_name}},

To activate your account, please enter the verification code below on the verification page:

Verification Code: {{verification_code}}

This code is valid for 10 minutes.

If needed, you can use the link below:
{{action_url}}

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Password Reset',
                    'title' => 'Password Reset',
                    'subtitle' => 'Password reset request',
                    'body' => 'Hi {{user_name}},

A password reset request has been submitted for your account.

To set a new password, click on the link below:
{{reset_password_url}}

This link is valid for 60 minutes.

If you did not request this, please ignore this email.

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Welcome',
                    'title' => 'Welcome',
                    'subtitle' => 'Welcome to Kids Collage',
                    'body' => 'Hi {{user_name}},

Welcome to Kids Collage! We are happy to have you with us.

You can now use all platform features:
• Browse educational courses
• Enroll in your favorite courses
• Access educational content
• Connect with instructors

To get started, click on the link below:
{{action_url}}

If you need guidance, our support team is ready to help.

Best wishes,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | New Order',
                    'title' => 'New Order',
                    'subtitle' => 'Your order has been placed successfully',
                    'body' => 'Hi {{user_name}},

Your order #{{order_number}} has been placed successfully.

Order Details:
• Total Amount: ${{order_amount}}
• Order Date: {{order_date}}
• Status: Pending Payment

To complete your order and make payment, click on the link below:
{{action_url}}

After successful payment, your access to purchased courses will be activated.

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Payment Successful',
                    'title' => 'Payment Successful',
                    'subtitle' => 'Your order payment has been completed',
                    'body' => 'Hi {{user_name}},

Payment for your order #{{order_number}} has been completed successfully.

Payment Details:
• Amount Paid: ${{payment_amount}}
• Transaction ID: {{transaction_id}}
• Payment Date: {{payment_date}}

Your access to purchased courses has been activated. You can start learning now.

To view your courses, click on the link below:
{{action_url}}

Best wishes for your learning journey,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Order Cancelled',
                    'title' => 'Order Cancelled',
                    'subtitle' => 'Your order has been cancelled',
                    'body' => 'Hi {{user_name}},

Unfortunately, your order #{{order_number}} has been cancelled.

Reason: {{cancellation_reason}}

If payment was made, the amount will be refunded to your account.

To view more details, click on the link below:
{{action_url}}

If you need assistance, please contact our support team.

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Payment Successful',
                    'title' => 'Payment Successful',
                    'subtitle' => 'Your payment has been completed',
                    'body' => 'Hi {{user_name}},

Your payment has been completed successfully.

Payment Details:
• Amount: ${{payment_amount}}
• Transaction ID: {{transaction_id}}
• Date: {{payment_date}}
• Payment Method: {{payment_method}}

Your access to purchased content has been activated.

To view the content, click on the link below:
{{action_url}}

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Payment Failed',
                    'title' => 'Payment Failed',
                    'subtitle' => 'Your payment was not completed',
                    'body' => 'Hi {{user_name}},

Unfortunately, your payment was not completed.

Reason: {{failure_reason}}

Please try again or contact our support team if you encounter any issues.

To try again, click on the link below:
{{action_url}}

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Course Enrollment',
                    'title' => 'Course Enrollment',
                    'subtitle' => 'Your enrollment request has been submitted',
                    'body' => 'Hi {{user_name}},

Your enrollment request for "{{course_title}}" has been submitted.

This request is under review and your access to the course will be activated after approval.

To check your enrollment status, click on the link below:
{{action_url}}

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Enrollment Approved',
                    'title' => 'Enrollment Approved',
                    'subtitle' => 'Your course enrollment has been approved',
                    'body' => 'Hi {{user_name}},

Great news! Your enrollment in "{{course_title}}" has been approved.

You now have access to all course content and can participate in sessions.

To start learning, click on the link below:
{{action_url}}

Best wishes for your learning,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Enrollment Rejected',
                    'title' => 'Enrollment Rejected',
                    'subtitle' => 'Your course enrollment has been rejected',
                    'body' => 'Hi {{user_name}},

Unfortunately, your enrollment in "{{course_title}}" has been rejected.

Reason: {{rejection_reason}}

If you need more information or want to re-enroll, please contact our support team.

To view other courses, click on the link below:
{{action_url}}

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Course Session Reminder',
                    'title' => 'Session Reminder',
                    'subtitle' => 'Upcoming session reminder',
                    'body' => 'Hi {{user_name}},

This is a reminder that the session for "{{course_title}}" will start soon.

Session Details:
• Date: {{session_date}}
• Time: {{session_time}}
• Duration: {{session_duration}}

Please be prepared and attend the session on time.

To join the session, click on the link below:
{{action_url}}

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Session Started',
                    'title' => 'Session Started',
                    'subtitle' => 'The course session has started',
                    'body' => 'Hi {{user_name}},

The session for "{{course_title}}" has now started.

To join the session, click on the link below:
{{action_url}}

We hope you enjoy this session.

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Session Ended',
                    'title' => 'Session Ended',
                    'subtitle' => 'The course session has ended',
                    'body' => 'Hi {{user_name}},

The session for "{{course_title}}" has ended.

We hope you enjoyed this session. You can view details, recorded video, and additional materials at the link below:
{{action_url}}

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | New Ticket',
                    'title' => 'New Ticket',
                    'subtitle' => 'Your ticket has been created successfully',
                    'body' => 'Hi {{user_name}},

Your ticket #{{ticket_number}} has been created successfully.

Subject: {{ticket_subject}}

Our support team will respond to your ticket as soon as possible.

To check your ticket status, click on the link below:
{{action_url}}

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Ticket Reply',
                    'title' => 'Ticket Reply',
                    'subtitle' => 'New reply to your ticket',
                    'body' => 'Hi {{user_name}},

A new reply has been sent to your ticket #{{ticket_number}}.

{{reply_message}}

To view the full reply, click on the link below:
{{action_url}}

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Ticket Resolved',
                    'title' => 'Ticket Resolved',
                    'subtitle' => 'Your ticket has been resolved',
                    'body' => 'Hi {{user_name}},

Your ticket #{{ticket_number}} has been resolved.

If you need further assistance, you can create a new ticket.

To view details, click on the link below:
{{action_url}}

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | {{announcement_title}}',
                    'title' => 'New Announcement',
                    'subtitle' => '{{announcement_title}}',
                    'body' => 'Hi {{user_name}},

New Announcement:

{{announcement_title}}

{{announcement_body}}

To view more details, click on the link below:
{{action_url}}

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | System Alert',
                    'title' => 'System Alert',
                    'subtitle' => '{{alert_title}}',
                    'body' => 'Hi {{user_name}},

System Alert:

{{alert_message}}

Please review this alert.

To view details, click on the link below:
{{action_url}}

Thank you,
Kids Collage Team',
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
            'channel' => NotificationChannelEnum::EMAIL,
            'data' => [
                'en' => [
                    'subject' => 'Kids Collage | Happy Birthday!',
                    'title' => 'Happy Birthday!',
                    'subtitle' => 'Happy Birthday, {{user_name}}!',
                    'body' => 'Hi {{user_name}},

Happy Birthday! 🎉

We hope your new year is full of success, joy, and learning.

To celebrate your birthday, we have a special gift for you:
{{birthday_gift}}

To claim your gift, click on the link below:
{{action_url}}

Best wishes,
Kids Collage Team',
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
