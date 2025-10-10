# Attendance Management

---

* [Overview](#overview)
* [Features](#features)
* [Attendance Tracking](#attendance-tracking)
* [Recording Attendance](#recording-attendance)
* [Attendance Reports](#attendance-reports)
* [Attendance Status](#attendance-status)
* [Automated Notifications](#automated-notifications)
* [Examples](#examples)
* [Troubleshooting](#troubleshooting)

---

<a name="overview"></a>
## Overview

The Attendance Management module provides comprehensive tools for tracking student attendance, generating reports, and monitoring attendance patterns across courses and sessions.

> {success} Real-time attendance tracking with automated alerts and reporting

> {primary} Support for multiple attendance methods including manual, biometric, and QR code

---

<a name="features"></a>
## Features

The Attendance Management module includes:

- **Flexible Tracking Methods** - Manual, biometric, QR code, geolocation
- **Session-Based Attendance** - Track attendance per course session
- **Real-Time Recording** - Instant attendance updates
- **Automated Alerts** - Notify parents/guardians of absences
- **Comprehensive Reports** - Attendance analytics and trends
- **Bulk Operations** - Mark attendance for entire classes
- **Late/Early Departure Tracking** - Record tardiness and early exits
- **Excuse Management** - Handle excused and unexcused absences
- **Integration** - Links with enrollment and course management

> {info} Attendance data is used for academic standing and compliance reporting

---

<a name="attendance-tracking"></a>
## Attendance Tracking

### Attendance Levels

Attendance can be tracked at multiple levels:

- **Course Level** - Overall course attendance percentage
- **Session Level** - Individual class session attendance
- **Student Level** - Individual student attendance record
- **Term Level** - Attendance across an entire academic term

### Attendance Policies

Configure attendance policies:

```php
use App\Models\Course;

$course = Course::find($courseId);

$attendancePolicy = [
    'required_attendance_percentage' => 75,
    'max_absences' => 5,
    'late_threshold_minutes' => 15,
    'notify_parents_after_absences' => 3,
];

$course->update(['attendance_policy' => $attendancePolicy]);
```

> {warning} Students falling below minimum attendance may receive warnings or fail

---

<a name="recording-attendance"></a>
## Recording Attendance

### Manual Attendance Entry

```php
use App\Actions\Attendance\CreateAttendanceAction;
use App\Models\CourseSession;
use App\Models\Enrollment;

$session = CourseSession::find($sessionId);
$enrollments = $session->course->enrollments()
    ->where('status', 'active')
    ->get();

foreach ($enrollments as $enrollment) {
    $attendanceData = [
        'course_session_id' => $session->id,
        'user_id' => $enrollment->user_id,
        'enrollment_id' => $enrollment->id,
        'status' => 'present',
        'recorded_at' => now(),
    ];
    
    app(CreateAttendanceAction::class)->execute($attendanceData);
}
```

### Quick Mark All Present

```php
use App\Models\CourseSession;

$session = CourseSession::find($sessionId);
$session->markAllPresent();
```

### Mark Individual Attendance

```php
use App\Actions\Attendance\CreateAttendanceAction;

$attendanceData = [
    'course_session_id' => $sessionId,
    'user_id' => $studentId,
    'enrollment_id' => $enrollmentId,
    'status' => 'absent',
    'is_excused' => false,
    'notes' => 'No notification received',
    'recorded_at' => now(),
];

$attendance = app(CreateAttendanceAction::class)->execute($attendanceData);
```

### Recording Late Arrival

```php
use App\Actions\Attendance\CreateAttendanceAction;
use Carbon\Carbon;

$session = CourseSession::find($sessionId);
$arrivalTime = Carbon::now();
$minutesLate = $arrivalTime->diffInMinutes($session->start_time);

$attendanceData = [
    'course_session_id' => $sessionId,
    'user_id' => $studentId,
    'enrollment_id' => $enrollmentId,
    'status' => 'late',
    'minutes_late' => $minutesLate,
    'recorded_at' => $arrivalTime,
];

app(CreateAttendanceAction::class)->execute($attendanceData);
```

---

<a name="attendance-status"></a>
## Attendance Status

Available attendance statuses:

- **present** - Student attended on time
- **absent** - Student did not attend
- **late** - Student arrived late
- **excused** - Absence with valid excuse
- **left_early** - Student departed before session end

### Updating Attendance Status

```php
use App\Actions\Attendance\UpdateAttendanceAction;
use App\Models\Attendance;

$attendance = Attendance::find($attendanceId);

// Mark absence as excused
app(UpdateAttendanceAction::class)->execute($attendance, [
    'status' => 'excused',
    'is_excused' => true,
    'excuse_reason' => 'Medical appointment',
    'excuse_document' => 'path/to/document.pdf',
]);
```

---

<a name="attendance-reports"></a>
## Attendance Reports

### Student Attendance Report

```php
use App\Models\User;
use App\Models\Course;

$student = User::find($studentId);
$course = Course::find($courseId);

$attendanceData = [
    'total_sessions' => $course->sessions()->count(),
    'attended' => $student->attendances()
        ->whereHas('courseSession', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })
        ->where('status', 'present')
        ->count(),
    'absent' => $student->attendances()
        ->whereHas('courseSession', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })
        ->where('status', 'absent')
        ->count(),
    'late' => $student->attendances()
        ->whereHas('courseSession', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })
        ->where('status', 'late')
        ->count(),
];

$attendanceData['percentage'] = 
    ($attendanceData['attended'] / $attendanceData['total_sessions']) * 100;
```

### Course Attendance Report

```php
use App\Models\Course;

$course = Course::with(['sessions.attendances'])->find($courseId);

$report = [];
foreach ($course->sessions as $session) {
    $report[] = [
        'session_date' => $session->start_time,
        'present' => $session->attendances()->where('status', 'present')->count(),
        'absent' => $session->attendances()->where('status', 'absent')->count(),
        'late' => $session->attendances()->where('status', 'late')->count(),
        'rate' => $session->getAttendanceRate(),
    ];
}
```

### Term Attendance Summary

```php
use App\Models\Term;

$term = Term::find($termId);

$summary = [
    'total_students' => $term->getEnrolledStudentsCount(),
    'average_attendance_rate' => $term->getAverageAttendanceRate(),
    'students_below_threshold' => $term->getStudentsBelowAttendanceThreshold(75),
    'total_absences' => $term->getTotalAbsences(),
];
```

---

<a name="automated-notifications"></a>
## Automated Notifications

### Absence Notifications

```php
use App\Models\Attendance;
use App\Notifications\StudentAbsenceNotification;

$attendance = Attendance::find($attendanceId);

if ($attendance->status === 'absent' && !$attendance->is_excused) {
    // Notify parents/guardians
    $student = $attendance->user;
    $parents = $student->parents; // Assuming relationship exists
    
    foreach ($parents as $parent) {
        $parent->notify(new StudentAbsenceNotification($attendance));
    }
}
```

### Attendance Threshold Alerts

```php
use App\Models\Enrollment;
use App\Notifications\AttendanceWarningNotification;

$enrollment = Enrollment::find($enrollmentId);
$attendanceRate = $enrollment->getAttendanceRate();

if ($attendanceRate < 75) {
    $enrollment->user->notify(
        new AttendanceWarningNotification($enrollment, $attendanceRate)
    );
}
```

---

<a name="examples"></a>
## Examples

### Example 1: Bulk Attendance Entry

```php
use App\Actions\Attendance\CreateAttendanceAction;
use App\Models\CourseSession;

$session = CourseSession::with('course.enrollments')->find($sessionId);

$attendanceStatuses = [
    1 => 'present',  // student_id => status
    2 => 'present',
    3 => 'absent',
    4 => 'late',
    5 => 'present',
];

foreach ($session->course->enrollments as $enrollment) {
    $status = $attendanceStatuses[$enrollment->user_id] ?? 'absent';
    
    $attendanceData = [
        'course_session_id' => $session->id,
        'user_id' => $enrollment->user_id,
        'enrollment_id' => $enrollment->id,
        'status' => $status,
        'recorded_at' => now(),
    ];
    
    app(CreateAttendanceAction::class)->execute($attendanceData);
}
```

### Example 2: QR Code Attendance

```php
use App\Models\CourseSession;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

$session = CourseSession::find($sessionId);

// Generate unique attendance token
$token = Str::random(32);
$session->update(['attendance_token' => $token]);

// Generate QR code
$qrCode = QrCode::size(300)->generate(
    route('attendance.mark', ['token' => $token])
);

// When student scans QR
public function markAttendance(Request $request, string $token)
{
    $session = CourseSession::where('attendance_token', $token)->firstOrFail();
    $student = auth()->user();
    
    // Verify session is active
    if (!$session->isActiveNow()) {
        return response()->json(['error' => 'Session not active'], 400);
    }
    
    // Mark attendance
    $attendance = app(CreateAttendanceAction::class)->execute([
        'course_session_id' => $session->id,
        'user_id' => $student->id,
        'enrollment_id' => $student->enrollments()
            ->where('course_id', $session->course_id)
            ->first()->id,
        'status' => 'present',
        'recorded_at' => now(),
    ]);
    
    return response()->json(['success' => true]);
}
```

### Example 3: Attendance Trend Analysis

```php
use App\Models\User;
use Carbon\Carbon;

$student = User::find($studentId);
$term = Term::current();

// Get attendance by week
$weeks = [];
$startDate = $term->start_date;
$endDate = $term->end_date;

while ($startDate->lt($endDate)) {
    $weekEnd = $startDate->copy()->addWeek();
    
    $weekAttendance = $student->attendances()
        ->whereBetween('recorded_at', [$startDate, $weekEnd])
        ->get();
    
    $weeks[] = [
        'week' => $startDate->format('Y-m-d'),
        'present' => $weekAttendance->where('status', 'present')->count(),
        'absent' => $weekAttendance->where('status', 'absent')->count(),
        'rate' => $weekAttendance->isNotEmpty() 
            ? ($weekAttendance->where('status', 'present')->count() / $weekAttendance->count()) * 100
            : 0,
    ];
    
    $startDate = $weekEnd;
}
```

### Example 4: Export Attendance Report

```php
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

$course = Course::with(['enrollments.user', 'sessions.attendances'])->find($courseId);

$csvData = [];
$csvData[] = ['Student Name', 'Email', 'Total Sessions', 'Present', 'Absent', 'Late', 'Attendance %'];

foreach ($course->enrollments as $enrollment) {
    $student = $enrollment->user;
    $attendances = $student->attendances()
        ->whereHas('courseSession', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })
        ->get();
    
    $totalSessions = $course->sessions->count();
    $present = $attendances->where('status', 'present')->count();
    $absent = $attendances->where('status', 'absent')->count();
    $late = $attendances->where('status', 'late')->count();
    $percentage = $totalSessions > 0 ? ($present / $totalSessions) * 100 : 0;
    
    $csvData[] = [
        $student->name,
        $student->email,
        $totalSessions,
        $present,
        $absent,
        $late,
        number_format($percentage, 2) . '%',
    ];
}

// Save to CSV
$filename = "attendance_{$course->id}_" . now()->format('Y-m-d') . ".csv";
$file = fopen(storage_path("app/public/{$filename}"), 'w');

foreach ($csvData as $row) {
    fputcsv($file, $row);
}

fclose($file);
```

---

<a name="troubleshooting"></a>
## Troubleshooting

### Duplicate Attendance Records

**Problem:** Multiple attendance records for same student/session.

**Solution:**
```php
use App\Models\Attendance;

// Find duplicates
$duplicates = Attendance::select('course_session_id', 'user_id')
    ->groupBy('course_session_id', 'user_id')
    ->havingRaw('COUNT(*) > 1')
    ->get();

// Keep latest record, delete others
foreach ($duplicates as $duplicate) {
    $records = Attendance::where('course_session_id', $duplicate->course_session_id)
        ->where('user_id', $duplicate->user_id)
        ->orderBy('recorded_at', 'desc')
        ->get();
    
    // Keep first (latest), delete rest
    $records->slice(1)->each->delete();
}
```

> {warning} Add unique constraint to prevent duplicates: `['course_session_id', 'user_id']`

### Incorrect Attendance Percentage

**Problem:** Attendance percentage doesn't match manual count.

**Solution:**
```php
$enrollment = Enrollment::find($enrollmentId);

// Recalculate attendance
$totalSessions = $enrollment->course->sessions()
    ->where('start_time', '<=', now())
    ->count();

$presentCount = $enrollment->user->attendances()
    ->whereHas('courseSession', function ($query) use ($enrollment) {
        $query->where('course_id', $enrollment->course_id)
              ->where('start_time', '<=', now());
    })
    ->whereIn('status', ['present', 'excused'])
    ->count();

$percentage = $totalSessions > 0 ? ($presentCount / $totalSessions) * 100 : 0;
```

### Missing Attendance Records

**Problem:** Some sessions missing attendance records.

**Solution:**
```php
use App\Models\Course;

$course = Course::with('sessions')->find($courseId);

foreach ($course->sessions as $session) {
    $expectedCount = $course->enrollments()->where('status', 'active')->count();
    $actualCount = $session->attendances()->count();
    
    if ($actualCount < $expectedCount) {
        // Create missing records as absent
        $recordedUsers = $session->attendances()->pluck('user_id');
        $missingUsers = $course->enrollments()
            ->where('status', 'active')
            ->whereNotIn('user_id', $recordedUsers)
            ->get();
        
        foreach ($missingUsers as $enrollment) {
            app(CreateAttendanceAction::class)->execute([
                'course_session_id' => $session->id,
                'user_id' => $enrollment->user_id,
                'enrollment_id' => $enrollment->id,
                'status' => 'absent',
                'recorded_at' => $session->start_time,
            ]);
        }
    }
}
```

> {danger} Always verify attendance data before generating official reports

---

## Integration with Other Modules

### Enrollment Management
Attendance is linked to active enrollments:

```php
$enrollment = Enrollment::find($enrollmentId);
$attendanceRate = $enrollment->getAttendanceRate();
```

### Course Management
Course sessions require attendance tracking:

```php
$session = CourseSession::find($sessionId);
$session->recordAttendance($attendanceData);
```

### Notification System
Automated attendance alerts:

```php
// Configure in course settings
$course->update([
    'notify_on_absence' => true,
    'notify_threshold' => 3, // Alert after 3 absences
]);
```

---

## Permissions

Attendance management requires appropriate permissions:

- `attendance.index` - View attendance records
- `attendance.create` - Record attendance
- `attendance.edit` - Edit attendance records
- `attendance.delete` - Delete attendance records
- `attendance.report` - Generate attendance reports

> {info} Faculty can only manage attendance for their assigned courses

