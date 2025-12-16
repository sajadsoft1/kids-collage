<?php

declare(strict_types=1);

return [
    'view_mode' => [
        'month' => 'Month',
        'week' => 'Week',
        'day' => 'Day',
        'list' => 'List',
    ],
    'calendar_type' => [
        'gregorian' => 'Gregorian',
        'jalali' => 'Jalali',
    ],
    'filters' => [
        'tasks' => 'Tasks',
        'classes' => 'Classes',
    ],
    'form' => [
        'create_task' => 'Create new task',
        'edit_task' => 'Edit task',
        'title' => 'Task title',
        'title_hint' => 'Enter the task title',
        'description' => 'Description (optional)',
        'description_hint' => 'Additional task details (optional)',
        'scheduled_for' => 'Date & time',
        'submit' => 'Add task',
        'update' => 'Update task',
    ],
    'event' => [
        'details' => 'Event details',
        'date' => 'Date',
        'time' => 'Time',
        'description' => 'Description',
        'location' => 'Location',
    ],
    'empty' => [
        'no_events' => 'No events scheduled.',
    ],
    'actions' => [
        'create_task' => 'Add new task',
        'reset_filters' => 'Reset filters',
        'cancel' => 'Cancel',
        'close' => 'Close',
    ],
    'messages' => [
        'task_created' => 'Task created successfully.',
        'task_updated' => 'Task updated successfully.',
        'task_moved' => 'Task moved successfully.',
        'task_not_found' => 'Task not found.',
        'event_not_found' => 'Event not found.',
        'unauthorized' => 'You are not authorized to perform this action.',
        'class_move_not_supported' => 'Moving classes is not currently supported.',
    ],
    'legend' => [
        'title' => 'Color Legend',
        'inactive_days' => 'Inactive Days',
    ],
];
