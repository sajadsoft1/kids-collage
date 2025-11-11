<?php

declare(strict_types=1);

return [
    // Card Types
    'task' => 'Task',
    'bug' => 'Bug',
    'feature' => 'Feature',
    'call' => 'Call',
    'meeting' => 'Meeting',
    'email' => 'Email',
    'other' => 'Other',

    // Priorities
    'priority' => [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
    ],

    // Statuses
    'status' => [
        'draft' => 'Draft',
        'active' => 'Active',
        'completed' => 'Completed',
        'archived' => 'Archived',
    ],

    // Board Roles
    'roles' => [
        'owner' => 'Owner',
        'admin' => 'Admin',
        'member' => 'Member',
        'viewer' => 'Viewer',
    ],

    // Card Roles
    'card_roles' => [
        'assignee' => 'Assignee',
        'reviewer' => 'Reviewer',
        'watcher' => 'Watcher',
    ],

    // Actions
    'actions' => [
        'create' => 'Create',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'move' => 'Move',
        'assign' => 'Assign',
        'unassign' => 'Unassign',
        'archive' => 'Archive',
        'restore' => 'Restore',
    ],

    // Messages
    'messages' => [
        'board_created' => 'Board created successfully.',
        'board_updated' => 'Board updated successfully.',
        'board_deleted' => 'Board deleted successfully.',
        'card_created' => 'Card created successfully.',
        'card_updated' => 'Card updated successfully.',
        'card_deleted' => 'Card deleted successfully.',
        'card_moved' => 'Card moved successfully.',
        'flow_rule_violated' => 'Cannot move card: flow rule violation.',
        'wip_limit_reached' => 'Cannot add card: WIP limit reached.',
        'access_denied' => 'Access denied.',
        'board_not_found' => 'Board not found.',
        'card_not_found' => 'Card not found.',
        'column_not_found' => 'Column not found.',
        'no_board_selected' => 'Please select a board to get started.',
        'no_boards_found' => 'No boards found matching your criteria.',
    ],

    // Labels
    'labels' => [
        'board' => 'Board',
        'boards' => 'Boards',
        'column' => 'Column',
        'columns' => 'Columns',
        'card' => 'Card',
        'cards' => 'Cards',
        'flow' => 'Flow',
        'flows' => 'Flows',
        'history' => 'History',
        'name' => 'Name',
        'description' => 'Description',
        'color' => 'Color',
        'order' => 'Order',
        'wip_limit' => 'WIP Limit',
        'due_date' => 'Due Date',
        'priority' => 'Priority',
        'status' => 'Status',
        'type' => 'Type',
        'assignees' => 'Assignees',
        'reviewers' => 'Reviewers',
        'watchers' => 'Watchers',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'actions' => 'Actions',
        'conditions' => 'Conditions',
        'from_column' => 'From Column',
        'to_column' => 'To Column',
        'is_active' => 'Active',
        'is_overdue' => 'Overdue',
        'days_until_due' => 'Days Until Due',
        'extra_attributes' => 'Extra Attributes',
        'no_board' => 'No Board',
        'no_boards' => 'No Boards',
    ],

    // Placeholders
    'placeholders' => [
        'board_name' => 'Enter board name...',
        'board_description' => 'Enter board description...',
        'column_name' => 'Enter column name...',
        'column_description' => 'Enter column description...',
        'card_title' => 'Enter card title...',
        'card_description' => 'Enter card description...',
        'search_cards' => 'Search cards...',
        'search_boards' => 'Search boards...',
    ],

    // Tooltips
    'tooltips' => [
        'add_board' => 'Add new board',
        'add_column' => 'Add new column',
        'add_card' => 'Add new card',
        'edit_board' => 'Edit board',
        'edit_column' => 'Edit column',
        'edit_card' => 'Edit card',
        'delete_board' => 'Delete board',
        'delete_column' => 'Delete column',
        'delete_card' => 'Delete card',
        'move_card' => 'Move card',
        'assign_card' => 'Assign card',
        'archive_card' => 'Archive card',
        'restore_card' => 'Restore card',
        'view_history' => 'View history',
        'view_details' => 'View details',
    ],

    // Validation
    'validation' => [
        'board_name_required' => 'Board name is required.',
        'column_name_required' => 'Column name is required.',
        'card_title_required' => 'Card title is required.',
        'invalid_color' => 'Invalid color format.',
        'invalid_date' => 'Invalid date format.',
        'wip_limit_positive' => 'WIP limit must be a positive number.',
        'order_positive' => 'Order must be a positive number.',
    ],

    // Conditions
    'conditions' => [
        'equals' => 'Equals',
        'not_equals' => 'Not Equals',
        'contains' => 'Contains',
        'greater_than' => 'Greater Than',
        'less_than' => 'Less Than',
        'greater_than_or_equal' => 'Greater Than or Equal',
        'less_than_or_equal' => 'Less Than or Equal',
        'before' => 'Before',
        'after' => 'After',
        'on' => 'On',
        'before_or_on' => 'Before or On',
        'after_or_on' => 'After or On',
        'is_null' => 'Is Null',
        'is_not_null' => 'Is Not Null',
    ],

    // Extra Attributes (for different card types)
    'extra_attributes' => [
        'call' => [
            'phone_number' => 'Phone Number',
            'call_duration' => 'Call Duration',
            'call_notes' => 'Call Notes',
        ],
        'meeting' => [
            'meeting_time' => 'Meeting Time',
            'meeting_duration' => 'Meeting Duration',
            'meeting_location' => 'Meeting Location',
            'attendees' => 'Attendees',
        ],
        'email' => [
            'email_subject' => 'Email Subject',
            'email_recipients' => 'Email Recipients',
            'email_content' => 'Email Content',
        ],
    ],
];
