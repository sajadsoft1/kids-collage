<?php

declare(strict_types=1);

return [
    'model' => 'Attendance',
    'permissions' => [
    ],
    'exceptions' => [
        'unauthorized_action' => 'You do not have permission to perform this action',
        'attendance_must_be_marked_first' => 'You must first mark the student as present',
        'leave_time_only_for_present' => 'Leave time can only be recorded for present students',
        'select_at_least_one_student' => 'Please select at least one student',
    ],
    'validations' => [
    ],
    'enum' => [
    ],
    'notifications' => [
        'attendance_marked_successfully' => 'Attendance marked successfully',
        'leave_time_recorded_successfully' => 'Leave time recorded successfully',
        'students_marked_present' => ':count students marked as present',
        'students_marked_absent' => ':count students marked as absent',
        'absence_marked_successfully' => 'Absence marked successfully',
    ],
    'page' => [
        'present' => 'Present',
        'absent' => 'Absent',
        'all_present' => 'All Present',
        'all_absent' => 'All Absent',
        'mark_all_present' => 'All Present',
        'mark_all_absent' => 'All Absent',
        'record_leave_time' => 'Record Leave Time',
        'record_excuse' => 'Record Excuse',
        'excuse_note' => 'Excuse Note',
        'excuse_note_placeholder' => 'Enter excuse note...',
        'mark_absent' => 'Mark Absent',
        'bulk_action_hint' => 'Use checkboxes to change status of multiple students',
        'my_attendance_status' => 'My Attendance Status',
        'status' => 'Status',
        'arrival' => 'Arrival',
        'departure' => 'Departure',
        'arrival_time' => 'Arrival Time',
        'delay' => 'Delay',
        'minutes' => 'minutes',
        'excuse' => 'Excuse',
        'attendance_not_recorded' => 'Your attendance for this session has not been recorded',
    ],
];
