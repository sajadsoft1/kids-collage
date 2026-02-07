<?php

declare(strict_types=1);

return [
    'title' => 'Support Tickets',
    'my_tickets' => 'My Tickets',
    'create_ticket' => 'Create Ticket',
    'ticket_number' => 'Ticket #',
    'subject' => 'Subject',
    'department' => 'Department',
    'priority' => 'Priority',
    'status' => 'Status',
    'created_at' => 'Created',
    'last_message' => 'Last message',
    'unread' => 'unread',
    'ticket_created' => 'Ticket :number has been created.',
    'ticket_closed' => 'This ticket is closed.',
    'ticket_closed_success' => 'Ticket has been closed.',
    'reply' => 'Reply',
    'send' => 'Send',
    'close_ticket' => 'Close Ticket',
    'internal_note' => 'Internal note',
    'priority_low' => 'Low',
    'priority_medium' => 'Medium',
    'priority_high' => 'High',
    'priority_urgent' => 'Urgent',
    'status_open' => 'Open',
    'status_pending' => 'Pending',
    'status_in_progress' => 'In progress',
    'status_resolved' => 'Resolved',
    'status_closed' => 'Closed',
    'status_archived' => 'Archived',
    'back_to_list' => 'Back to list',
    'reopen_ticket' => 'Reopen ticket',
    'change_status' => 'Change status',
    'change_priority' => 'Change priority',
    'assign_operator' => 'Assign operator',
    'transfer_department' => 'Transfer to department',
    'manage_tags' => 'Manage tags',
    'tag' => 'Tag',
    'tags' => 'Tags',
    'no_tags' => 'No tags',
    'no_tags_message' => 'Add tags to categorize tickets.',
    'tags_empty' => 'No tags yet.',
    'unassigned' => 'Unassigned',
    'status_updated' => 'Status updated.',
    'status_transition_not_allowed' => 'This status transition is not allowed.',
    'no_status_transitions' => 'No further status transitions are available for this ticket.',
    'status_guide_title' => 'Ticket status guide',
    'status_guide_intro' => 'Each status has a specific meaning and effect. Only allowed transitions (buttons below) are shown based on the current ticket status.',
    'status_guide_open' => 'Ticket is newly created and awaiting review. Can be moved to Pending, In progress, or Closed.',
    'status_guide_pending' => 'Waiting for user reply or internal action. Ticket is temporarily on hold. Can move to Open, In progress, or Closed.',
    'status_guide_in_progress' => 'An agent is actively working on the ticket. Can move to Pending, Resolved, or Closed.',
    'status_guide_resolved' => 'Issue is considered resolved by support. Can be Closed after user confirmation, or reopened to Open if the user disagrees.',
    'status_guide_closed' => 'Ticket is officially closed. Can be reopened to Open or moved to Archived for filing.',
    'status_guide_archived' => 'Ticket is archived and has no further transitions. Used only for long-term storage.',
    'priority_updated' => 'Priority updated.',
    'assigned_success' => 'Operator assigned.',
    'transferred_success' => 'Ticket transferred.',
    'tags_updated' => 'Tags updated.',
    'ticket_reopened' => 'Ticket reopened.',
    'no_tickets' => 'No tickets yet.',
    'no_tickets_message' => 'Create your first ticket to get support.',
    'attachments' => 'Attachments',
    'add_file' => 'Add file',
    'max_files' => 'Max 5 files, 10MB each.',
    'confirm_close' => 'Are you sure you want to close this ticket?',
    'stats' => [
        'total' => 'All',
        'open' => 'Open',
        'in_progress' => 'In progress',
        'closed' => 'Closed',
    ],
    'internal_notes_list' => 'Internal notes',
    'uploading' => 'Uploading...',
    'creator' => 'Creator',
    'operator' => 'Operator',
    'departments_manage' => 'Manage departments',
    'filter_scope' => 'Display',
    'no_departments' => 'No departments yet.',
    'no_departments_message' => 'Add a department to manage tickets.',
    'auto_assign_strategy' => 'Auto assign',
    'auto_assign_manual' => 'Manual',
    'auto_assign_round_robin' => 'Round robin',
    'auto_assign_load_balance' => 'Load balance',
    'department_agents' => 'Department agents',
    'insert_canned_response' => 'Insert canned response',
    'csat_title' => 'How would you rate this ticket?',
    'csat_comment' => 'Comment (optional)',
    'csat_submit' => 'Submit rating',
    'csat_your_rating' => 'Your rating',
    'csat_submitted' => 'Your rating has been submitted.',
    'ticket_actions' => 'Ticket actions',
    'only_agents_can_manage' => 'Only department agents can assign or manage this ticket. ',
    'filter_assigned' => 'Assigned',
    'filter_assigned_all' => 'All',
    'filter_assigned_mine' => 'Assigned to me',
    'filter_assigned_unassigned' => 'Unassigned',
    'feedback_report' => 'Feedback report',
    'feedback_average' => 'Average rating',
    'feedback_total' => 'Total feedbacks',
    'feedback_distribution' => 'Rating distribution',
    'feedback_recent' => 'Recent feedbacks',
    'feedback_report_disabled' => 'Feedback report is disabled.',
    'no_feedbacks_yet' => 'No feedbacks yet.',

    'learning' => [
        'overview' => [
            'title' => 'Start & end',
            'icon' => 'o-document-text',
            'content' => '
                <h3>Ticket start</h3>
                <p>A ticket starts when <strong>created by a user or operator</strong>: department, subject, priority and the first message are submitted. After creation, the ticket is <strong>Open</strong> and queued in the department.</p>
                <h3>Ticket end</h3>
                <p>A ticket ends when it is <strong>Closed</strong>. It can be moved to <strong>Resolved</strong> first and then closed after user confirmation. Closed tickets can be <strong>reopened</strong> or moved to <strong>Archived</strong> for storage.</p>
            ',
        ],
        'goal' => [
            'title' => 'Goal',
            'icon' => 'o-flag',
            'content' => '
                <p>The goal is <strong>centralized support request management</strong>: each request becomes a ticket, is routed to the right department, assigned to an operator, and tracked through status changes and rating until closure and (if needed) archiving.</p>
            ',
        ],
        'rating' => [
            'title' => 'Rating',
            'icon' => 'o-star',
            'content' => '
                <p>After a ticket is closed, <strong>the user can submit a rating (1–5) and optional comment</strong>. These are collected in the <strong>Feedback report</strong>; average and distribution are used to evaluate support quality.</p>
            ',
        ],
        'statuses' => [
            'title' => 'Statuses',
            'icon' => 'o-check-circle',
            'content' => '
                <p><strong>Open:</strong> newly created, awaiting review. <strong>Pending:</strong> waiting for user reply or internal action. <strong>In progress:</strong> an operator is handling it. <strong>Resolved:</strong> resolved by support; can be closed or reopened if the user disagrees. <strong>Closed:</strong> officially closed. <strong>Archived:</strong> for long-term storage only.</p>
                <p>Only allowed status transitions (based on current status) are shown on the ticket page.</p>
            ',
        ],
        'department' => [
            'title' => 'Department',
            'icon' => 'o-building-office-2',
            'content' => '
                <p>Each ticket belongs to a <strong>department</strong>. Departments are defined in «Manage departments». Operators are linked to one or more departments and only see and manage tickets in those departments; they can <strong>transfer</strong> a ticket to another department.</p>
            ',
        ],
        'tag' => [
            'title' => 'Tags',
            'icon' => 'o-tag',
            'content' => '
                <p><strong>Tags</strong> are used to categorize and filter tickets. Tags are added or edited in «Manage tags». Multiple tags can be applied to a ticket for easier search and reporting.</p>
            ',
        ],
        'operator' => [
            'title' => 'Assign operator',
            'icon' => 'o-user-plus',
            'content' => '
                <h3>Assigning an operator</h3>
                <p>Only operators of the ticket’s <strong>department</strong> can assign or manage it. From the ticket page, use «Assign operator» to assign one of the department’s operators to the ticket.</p>
                <h3>Filters on the ticket list</h3>
                <p><strong>Display:</strong> «My tickets» shows only tickets you participate in; «All» shows tickets in your departments (with optional filter: assigned to me / unassigned / all). Use these to see your assigned tickets or unassigned ones.</p>
            ',
        ],
    ],
];
