<?php

declare(strict_types=1);

return [
    'model' => 'Ticket',
    'permissions' => [
    ],
    'exceptions' => [
    ],
    'validations' => [
    ],
    'enum' => [
        'status' => [
            'open' => 'Open',
            'close' => 'Closed',
        ],
        'department' => [
            'all' => 'All',
            'finance_and_administration' => 'Finance & Administration',
            'Sale' => 'Sales',
            'technical' => 'Technical Support',
        ],
        'priority' => [
            'all' => 'All',
            'critical' => 'Critical',
            'high' => 'High',
            'medium' => 'Medium',
            'low' => 'Low',
        ],
    ],
    'page' => [
        'subject_info' => 'What subject do you want to create a ticket for?',
        'department_info' => 'Select the responsible department so your ticket is properly assigned',
        'priority_info' => 'Help us review your request quickly by selecting the appropriate priority',
        'status_info' => 'You can change the latest status of the ticket from this section',
        'ticket_information' => 'Ticket Information',
        'ticket_status_is_close' => 'Ticket status is closed',
        'ticket_status_change_to_close' => 'Close Ticket',
        'ticket_status_changed_to_close' => 'Ticket has been closed',
        'ticket_status_change_to_open' => 'Open Ticket',
        'ticket_status_changed_to_open' => 'Ticket has been opened',
        'ticket_details' => 'Ticket Details',
        'new_ticket' => 'New Ticket',
        'select_ticket' => 'Please select a ticket',
        'open_tickets_list' => 'Open tickets list',
        'message_placeholder' => 'Type your message...',
    ],
    'not_found' => 'Ticket not found',
    'unauthorized' => 'You are not authorized to perform this action',
    'cannot_message_closed' => 'Cannot send messages to closed tickets',
    'message_sent' => 'Message sent successfully',
    'table' => [
        'ticket_number' => 'Ticket Number',
        'subject' => 'Subject',
        'user' => 'User',
        'priority' => 'Priority',
        'status' => 'Status',
        'created_at' => 'Created At',
        'actions' => 'Actions',
    ],
];
