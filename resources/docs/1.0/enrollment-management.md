# Enrollment Management

---

* [Overview](#overview)
* [Features](#features)
* [Enrollment Process](#enrollment-process)
* [Creating Enrollments](#creating-enrollments)
* [Managing Enrollments](#managing-enrollments)
* [Enrollment Status](#enrollment-status)
* [Waitlists](#waitlists)
* [Examples](#examples)
* [Troubleshooting](#troubleshooting)

---

<a name="overview"></a>
## Overview

The Enrollment Management module handles student registration, course enrollments, waitlists, and enrollment status tracking throughout the academic term.

> {success} Complete enrollment lifecycle management from registration to completion

> {primary} Automated capacity checks and waitlist management

---

<a name="features"></a>
## Features

The Enrollment Management module includes:

- **Student Registration** - Enroll students in courses
- **Capacity Management** - Automatic enrollment limit enforcement
- **Enrollment Status Tracking** - Active, completed, dropped, withdrawn
- **Waitlist Management** - Automatic waitlist processing
- **Prerequisites Checking** - Verify course prerequisites
- **Payment Integration** - Link enrollments to orders and payments
- **Bulk Enrollment** - Enroll multiple students simultaneously
- **Enrollment Reports** - Comprehensive enrollment analytics
- **Drop/Add Management** - Handle course changes within deadlines

> {info} Enrollments are automatically validated against course capacity and prerequisites

---

<a name="enrollment-process"></a>
## Enrollment Process

The enrollment workflow:

1. **Student Selects Course** - Student browses available courses
2. **Validation** - System checks:
   - Course capacity
   - Prerequisites
   - Schedule conflicts
   - Registration period
3. **Payment Processing** - If required, process payment
4. **Enrollment Creation** - Create enrollment record
5. **Confirmation** - Send confirmation to student and instructor

> {primary} Failed validations automatically add students to waitlist if available

---

<a name="creating-enrollments"></a>
## Creating Enrollments

### Basic Enrollment

```php
use App\Actions\Enrollment\CreateEnrollmentAction;
use App\Models\User;
use App\Models\Course;

$enrollmentData = [
    'user_id' => $student->id,
    'course_id' => $course->id,
    'enrolled_at' => now(),
    'status' => 'active',
];

$enrollment = app(CreateEnrollmentAction::class)->execute($enrollmentData);
```

### Enrollment with Payment

```php
use App\Actions\Enrollment\CreateEnrollmentAction;

$enrollmentData = [
    'user_id' => $student->id,
    'course_id' => $course->id,
    'order_id' => $order->id,
    'enrolled_at' => now(),
    'status' => 'pending_payment',
];

$enrollment = app(CreateEnrollmentAction::class)->execute($enrollmentData);

// After successful payment
$enrollment->update(['status' => 'active']);
```

### Bulk Enrollment

```php
use App\Actions\Enrollment\CreateEnrollmentAction;

$students = User::whereIn('id', $studentIds)->get();
$course = Course::find($courseId);

foreach ($students as $student) {
    $enrollmentData = [
        'user_id' => $student->id,
        'course_id' => $course->id,
        'enrolled_at' => now(),
        'status' => 'active',
    ];
    
    app(CreateEnrollmentAction::class)->execute($enrollmentData);
}
```

> {success} Bulk enrollments are processed within database transactions for data integrity

---

<a name="managing-enrollments"></a>
## Managing Enrollments

### Updating Enrollment Status

```php
use App\Actions\Enrollment\UpdateEnrollmentAction;

// Drop a course
app(UpdateEnrollmentAction::class)->execute($enrollment, [
    'status' => 'dropped',
    'dropped_at' => now(),
]);

// Withdraw from course
app(UpdateEnrollmentAction::class)->execute($enrollment, [
    'status' => 'withdrawn',
    'withdrawn_at' => now(),
    'withdrawal_reason' => 'Schedule conflict',
]);

// Complete enrollment
app(UpdateEnrollmentAction::class)->execute($enrollment, [
    'status' => 'completed',
    'completed_at' => now(),
    'final_grade' => 'A',
]);
```

### Transferring Enrollment

```php
use App\Models\Enrollment;
use App\Models\Course;

$enrollment = Enrollment::find($enrollmentId);
$newCourse = Course::find($newCourseId);

// Validate capacity
if ($newCourse->hasAvailableCapacity()) {
    $enrollment->update([
        'course_id' => $newCourse->id,
        'transferred_at' => now(),
    ]);
}
```

---

<a name="enrollment-status"></a>
## Enrollment Status

Available enrollment statuses:

- **pending_payment** - Awaiting payment confirmation
- **active** - Currently enrolled and attending
- **completed** - Successfully completed the course
- **dropped** - Dropped during add/drop period
- **withdrawn** - Withdrawn after add/drop deadline
- **cancelled** - Enrollment cancelled (by admin)
- **waitlisted** - On waitlist pending availability

### Status Transitions

```php
use App\Models\Enrollment;

$enrollment = Enrollment::find($enrollmentId);

// Check if status transition is allowed
$canDrop = $enrollment->canDrop();        // Within add/drop period
$canWithdraw = $enrollment->canWithdraw(); // Within withdrawal period
$isActive = $enrollment->isActive();       // Currently active
```

> {warning} Status transitions are subject to term deadlines and institutional policies

---

<a name="waitlists"></a>
## Waitlists

### Adding to Waitlist

```php
use App\Models\Course;
use App\Models\Enrollment;

$course = Course::find($courseId);

if ($course->isFull()) {
    $enrollment = Enrollment::create([
        'user_id' => $student->id,
        'course_id' => $course->id,
        'status' => 'waitlisted',
        'waitlisted_at' => now(),
    ]);
}
```

### Processing Waitlist

```php
use App\Models\Course;
use App\Models\Enrollment;

$course = Course::find($courseId);

// When a spot opens up
if ($course->hasAvailableCapacity()) {
    $nextStudent = Enrollment::where('course_id', $course->id)
        ->where('status', 'waitlisted')
        ->orderBy('waitlisted_at')
        ->first();
    
    if ($nextStudent) {
        $nextStudent->update([
            'status' => 'active',
            'enrolled_at' => now(),
            'waitlisted_at' => null,
        ]);
        
        // Send notification to student
    }
}
```

### Automatic Waitlist Processing

```php
use App\Models\Enrollment;

// When a student drops
$droppedEnrollment->update(['status' => 'dropped']);

// Automatically promote from waitlist
$course = $droppedEnrollment->course;
$course->processWaitlist();
```

> {info} Waitlist is processed in first-come, first-served order

---

<a name="examples"></a>
## Examples

### Example 1: Enrollment with Prerequisites Check

```php
use App\Models\Course;
use App\Models\User;

$student = User::find($studentId);
$course = Course::with('prerequisites')->find($courseId);

// Check prerequisites
$completedCourses = $student->enrollments()
    ->where('status', 'completed')
    ->pluck('course_id')
    ->toArray();

$missingPrereqs = $course->prerequisites()
    ->whereNotIn('id', $completedCourses)
    ->get();

if ($missingPrereqs->isNotEmpty()) {
    throw new \Exception('Missing prerequisites: ' . 
        $missingPrereqs->pluck('name')->join(', '));
}

// Proceed with enrollment
$enrollment = app(CreateEnrollmentAction::class)->execute([
    'user_id' => $student->id,
    'course_id' => $course->id,
    'enrolled_at' => now(),
    'status' => 'active',
]);
```

### Example 2: Schedule Conflict Detection

```php
use App\Models\User;
use App\Models\Course;

$student = User::find($studentId);
$newCourse = Course::with('sessions')->find($courseId);

// Get student's current schedule
$currentEnrollments = $student->enrollments()
    ->where('status', 'active')
    ->with('course.sessions')
    ->get();

foreach ($newCourse->sessions as $newSession) {
    foreach ($currentEnrollments as $enrollment) {
        foreach ($enrollment->course->sessions as $existingSession) {
            if ($newSession->conflictsWith($existingSession)) {
                throw new \Exception('Schedule conflict detected');
            }
        }
    }
}
```

### Example 3: Enrollment Report

```php
use App\Models\Term;
use App\Models\Enrollment;

$term = Term::find($termId);

$report = [
    'total_enrollments' => Enrollment::whereHas('course', function ($query) use ($term) {
        $query->where('term_id', $term->id);
    })->count(),
    
    'active_enrollments' => Enrollment::where('status', 'active')
        ->whereHas('course', function ($query) use ($term) {
            $query->where('term_id', $term->id);
        })->count(),
    
    'completed_enrollments' => Enrollment::where('status', 'completed')
        ->whereHas('course', function ($query) use ($term) {
            $query->where('term_id', $term->id);
        })->count(),
    
    'waitlisted_students' => Enrollment::where('status', 'waitlisted')
        ->whereHas('course', function ($query) use ($term) {
            $query->where('term_id', $term->id);
        })->count(),
];
```

### Example 4: Drop Course with Refund Calculation

```php
use App\Models\Enrollment;
use Carbon\Carbon;

$enrollment = Enrollment::with('course.term')->find($enrollmentId);
$term = $enrollment->course->term;

// Calculate refund percentage based on drop date
$daysIntoTerm = Carbon::now()->diffInDays($term->start_date);
$refundPercentage = 0;

if ($daysIntoTerm <= 7) {
    $refundPercentage = 100; // Full refund
} elseif ($daysIntoTerm <= 14) {
    $refundPercentage = 75;
} elseif ($daysIntoTerm <= 21) {
    $refundPercentage = 50;
}

// Process drop and refund
$enrollment->update([
    'status' => 'dropped',
    'dropped_at' => now(),
    'refund_percentage' => $refundPercentage,
]);

// Process refund if applicable
if ($refundPercentage > 0 && $enrollment->order) {
    // Process refund logic
}
```

---

<a name="troubleshooting"></a>
## Troubleshooting

### Enrollment Failed - Course Full

**Problem:** Student cannot enroll because course is at capacity.

**Solution:**
```php
$course = Course::find($courseId);

// Check actual capacity
echo "Capacity: {$course->capacity}\n";
echo "Enrolled: {$course->enrollments()->where('status', 'active')->count()}\n";

// Add to waitlist
$enrollment = Enrollment::create([
    'user_id' => $studentId,
    'course_id' => $courseId,
    'status' => 'waitlisted',
    'waitlisted_at' => now(),
]);
```

### Cannot Drop Course

**Problem:** Drop button is disabled or throws error.

**Solution:**
```php
$enrollment = Enrollment::find($enrollmentId);
$term = $enrollment->course->term;

// Check drop deadline
if (now()->gt($term->add_drop_deadline)) {
    echo "Past add/drop deadline. Student must withdraw instead.\n";
} else {
    echo "Can drop until: {$term->add_drop_deadline}\n";
}
```

> {warning} After add/drop deadline, students must withdraw (may affect transcript)

### Duplicate Enrollment Prevention

**Problem:** Student enrolled in same course multiple times.

**Solution:**
```php
use App\Models\Enrollment;

// Check for existing enrollment
$existing = Enrollment::where('user_id', $studentId)
    ->where('course_id', $courseId)
    ->whereIn('status', ['active', 'pending_payment', 'waitlisted'])
    ->first();

if ($existing) {
    throw new \Exception('Already enrolled in this course');
}
```

### Waitlist Not Processing

**Problem:** Students not automatically moved from waitlist.

**Solution:**
```php
// Manually process waitlist for a course
$course = Course::find($courseId);
$course->processWaitlist();

// Or process all waitlists
Course::whereHas('enrollments', function ($query) {
    $query->where('status', 'waitlisted');
})->each(function ($course) {
    $course->processWaitlist();
});
```

> {danger} Always validate enrollment status before allowing course access

---

## Integration with Other Modules

### Order Management
Link enrollments to payment orders:

```php
$enrollment = Enrollment::create([
    'user_id' => $student->id,
    'course_id' => $course->id,
    'order_id' => $order->id,
    'status' => 'pending_payment',
]);
```

### Attendance Management
Track attendance for enrolled students:

```php
$enrollment = Enrollment::find($enrollmentId);
$attendanceRate = $enrollment->getAttendanceRate();
```

### Course Management
Enrollment affects course availability:

```php
$course = Course::withCount('activeEnrollments')->find($courseId);
$spotsRemaining = $course->capacity - $course->active_enrollments_count;
```

---

## Permissions

Enrollment management requires appropriate permissions:

- `enrollment.index` - View enrollments list
- `enrollment.create` - Create new enrollments
- `enrollment.edit` - Edit existing enrollments
- `enrollment.delete` - Cancel enrollments

> {info} Students can view and manage their own enrollments by default

