<?php

declare(strict_types=1);

return [
    'title' => 'Dashboard',
    'refresh' => 'Refresh',
    'refreshing' => 'Refreshing',
    'refresh_error' => 'Error refreshing dashboard data',
    'loading' => 'Loading...',

    'statistics' => [
        'total_users' => 'Total Users',
        'total_blogs' => 'Total Blogs',
        'unresolved_tickets' => 'Unresolved Tickets',
        'last_month_users' => 'Last Month Users',
        'total_contacts' => 'Total Contacts',
        'total_portfolios' => 'Total Portfolios',
        'total_categories' => 'Total Categories',
        'total_opinions' => 'Total Opinions',
    ],

    'today' => [
        'title' => 'Today\'s Statistics',
        'new_users' => 'New Users',
        'new_blogs' => 'New Blogs',
        'new_tickets' => 'New Tickets',
        'new_contacts' => 'New Contacts',
        'weekly_growth' => 'Weekly Growth',
    ],

    'charts' => [
        'users_registration' => 'Users Registration Over Time',
        'user_gender_distribution' => 'User Gender Distribution',
        'monthly_growth' => 'Monthly Growth',
        'ticket_status_distribution' => 'Ticket Status Distribution',
        'total_tickets' => 'Total Tickets',
        'total_users' => 'Total Users',
        'periods' => [
            'today' => 'Today',
            'week' => 'Last Week',
            'month' => 'Last Month',
            'year' => 'Last Year',
        ],
        'genders' => [
            'men' => 'Men',
            'women' => 'Women',
        ],
        'metrics' => [
            'users' => 'Users',
            'blogs' => 'Blogs',
            'tickets' => 'Tickets',
        ],
    ],

    'tickets' => [
        'last_tickets' => 'Last Tickets',
        'ticket_number' => 'Ticket Number',
        'subject' => 'Subject',
        'user' => 'User',
        'priority' => 'Priority',
        'status' => 'Status',
        'created_at' => 'Created At',
        'actions' => 'Actions',
        'view_all_tickets' => 'View All Tickets',
        'no_tickets_found' => 'No tickets found',
        'priorities' => [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'unknown' => 'Unknown',
        ],
        'statuses' => [
            'open' => 'Open',
            'close' => 'Closed',
        ],
    ],

    'recent' => [
        'recent_users' => 'Recent Users',
        'recent_blogs' => 'Recent Blogs',
        'view_all' => 'View All',
        'no_data' => 'No data found',
    ],

    'system' => [
        'system_health' => 'System Health',
        'php_version' => 'PHP Version',
        'laravel_version' => 'Laravel Version',
        'database_status' => 'Database Status',
        'cache_status' => 'Cache Status',
        'queue_status' => 'Queue Status',
        'connected' => 'Connected',
        'active' => 'Active',
        'running' => 'Running',
    ],

    'quick_actions' => [
        'title' => 'Quick Actions',
        'add_user' => 'Add User',
        'create_blog' => 'Create Blog',
        'view_tickets' => 'View Tickets',
        'manage_categories' => 'Manage Categories',
    ],

    'notifications' => [
        'success' => 'Success',
        'error' => 'Error',
        'warning' => 'Warning',
        'info' => 'Information',
    ],

    'summary' => [
        'title' => 'Dashboard Summary',
        'total_items' => 'Total Items',
        'growth' => 'Growth',
        'status' => 'Status',
        'last_updated' => 'Last Updated',
        'weekly' => [
            'percentage' => 'Weekly Growth Percentage',
        ],
    ],

    'performance' => [
        'title' => 'Performance Metrics',
        'database_query_time' => 'Database Query Time',
        'cache_hit_rate' => 'Cache Hit Rate',
        'total_response_time' => 'Total Response Time',
        'memory_usage' => 'Memory Usage',
        'peak_memory' => 'Peak Memory',
        'seconds' => 'seconds',
        'mb' => 'MB',
        'percent' => '%',
    ],

    'date_filter' => [
        'title' => 'Date Filter',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'apply' => 'Apply',
        'cancel' => 'Cancel',
        'custom_range' => 'Custom Range',
        'showing_data_from' => 'Showing data from',
        'to' => 'to',
        'periods' => [
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'last_7_days' => 'Last 7 days',
            'last_30_days' => 'Last 30 days',
            'last_90_days' => 'Last 90 days',
            'this_month' => 'This month',
            'last_month' => 'Last month',
            'this_year' => 'This year',
            'custom' => 'Custom range',
        ],
    ],
];
