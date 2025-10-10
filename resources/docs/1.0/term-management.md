# Term Management

---

* [Overview](#overview)
* [Features](#features)
* [Term Structure](#term-structure)
* [Creating Terms](#creating-terms)
* [Managing Terms](#managing-terms)
* [Academic Calendar](#academic-calendar)
* [Examples](#examples)
* [Troubleshooting](#troubleshooting)

---

<a name="overview"></a>
## Overview

The Term Management module enables educational institutions to organize their academic schedule into semesters, trimesters, quarters, or custom academic periods.

> {success} Streamline academic scheduling with flexible term configurations

> {primary} Supports multiple concurrent terms and custom academic calendars

---

<a name="features"></a>
## Features

The Term Management module includes:

- **Flexible Term Types** - Semesters, trimesters, quarters, summer sessions
- **Date Management** - Define start dates, end dates, and key milestones
- **Registration Periods** - Set enrollment windows for each term
- **Term Status** - Active, upcoming, completed, archived
- **Multi-year Support** - Manage multiple academic years simultaneously
- **Holiday Configuration** - Define holidays and non-teaching days
- **Grade Deadlines** - Set grading and reporting deadlines

> {info} Terms can overlap for institutions running multiple concurrent programs

---

<a name="term-structure"></a>
## Term Structure

Each term includes:

- **Basic Information**
  - Name (e.g., "Fall 2025", "Spring 2026")
  - Academic year
  - Term type (semester/trimester/quarter)
  
- **Important Dates**
  - Term start date
  - Term end date
  - Registration opens
  - Registration closes
  - Add/drop deadline
  - Withdrawal deadline
  - Final exams period
  - Grade submission deadline

- **Status**
  - Draft - Planning stage
  - Upcoming - Published but not started
  - Active - Currently in session
  - Completed - Ended but grades in progress
  - Archived - Fully closed

> {primary} Terms must be activated before courses can be scheduled

---

<a name="creating-terms"></a>
## Creating Terms

To create a new academic term:

```php
use App\Actions\Term\CreateTermAction;
use Carbon\Carbon;

$termData = [
    'name' => 'Fall 2025',
    'academic_year' => '2025-2026',
    'type' => 'semester',
    'start_date' => Carbon::parse('2025-09-01'),
    'end_date' => Carbon::parse('2025-12-20'),
    'registration_start' => Carbon::parse('2025-07-01'),
    'registration_end' => Carbon::parse('2025-08-25'),
    'add_drop_deadline' => Carbon::parse('2025-09-15'),
    'withdrawal_deadline' => Carbon::parse('2025-11-15'),
    'final_exams_start' => Carbon::parse('2025-12-10'),
    'final_exams_end' => Carbon::parse('2025-12-20'),
    'grade_deadline' => Carbon::parse('2025-12-27'),
    'status' => 'upcoming',
    'is_active' => true,
];

$term = app(CreateTermAction::class)->execute($termData);
```

> {success} Terms are immediately available for course scheduling after creation

---

<a name="managing-terms"></a>
## Managing Terms

### Updating Term Information

```php
use App\Actions\Term\UpdateTermAction;

$updateData = [
    'registration_end' => Carbon::parse('2025-08-30'),
    'add_drop_deadline' => Carbon::parse('2025-09-20'),
];

app(UpdateTermAction::class)->execute($term, $updateData);
```

### Activating a Term

```php
use App\Models\Term;

$term = Term::find($termId);
$term->update(['status' => 'active']);
```

### Closing a Term

```php
use App\Models\Term;

$term = Term::find($termId);
$term->update(['status' => 'completed']);

// Archive after all grades are submitted
$term->update(['status' => 'archived']);
```

> {warning} Archiving a term prevents any further modifications to courses and enrollments

---

<a name="academic-calendar"></a>
## Academic Calendar

### Getting Current Term

```php
use App\Models\Term;
use Carbon\Carbon;

$currentTerm = Term::where('status', 'active')
    ->where('start_date', '<=', Carbon::now())
    ->where('end_date', '>=', Carbon::now())
    ->first();
```

### Checking Registration Status

```php
use App\Models\Term;

$term = Term::find($termId);

if ($term->isRegistrationOpen()) {
    // Allow student registration
} else {
    // Registration closed
}
```

### Term Timeline Methods

```php
// Check various term periods
$term->isRegistrationOpen();  // Registration period active
$term->isAddDropPeriod();      // Can add/drop courses
$term->isWithdrawalPeriod();   // Can withdraw from courses
$term->isFinalExamsPeriod();   // Finals week
$term->isGradingPeriod();      // Grade submission open
```

---

<a name="examples"></a>
## Examples

### Example 1: Creating a Summer Session

```php
$summerTerm = [
    'name' => 'Summer 2025',
    'academic_year' => '2024-2025',
    'type' => 'summer',
    'start_date' => Carbon::parse('2025-06-01'),
    'end_date' => Carbon::parse('2025-08-10'),
    'registration_start' => Carbon::parse('2025-04-01'),
    'registration_end' => Carbon::parse('2025-05-25'),
    'add_drop_deadline' => Carbon::parse('2025-06-08'),
    'withdrawal_deadline' => Carbon::parse('2025-07-20'),
    'final_exams_start' => Carbon::parse('2025-08-05'),
    'final_exams_end' => Carbon::parse('2025-08-10'),
    'grade_deadline' => Carbon::parse('2025-08-17'),
    'status' => 'upcoming',
    'is_active' => true,
];

$term = app(CreateTermAction::class)->execute($summerTerm);
```

### Example 2: Getting All Upcoming Terms

```php
use App\Models\Term;
use Carbon\Carbon;

$upcomingTerms = Term::where('status', 'upcoming')
    ->where('start_date', '>', Carbon::now())
    ->orderBy('start_date')
    ->get();

foreach ($upcomingTerms as $term) {
    echo "{$term->name} starts in " . 
         Carbon::now()->diffInDays($term->start_date) . " days\n";
}
```

### Example 3: Term Report

```php
use App\Models\Term;

$term = Term::with(['courses.enrollments'])->find($termId);

$stats = [
    'total_courses' => $term->courses->count(),
    'total_enrollments' => $term->courses->sum(function ($course) {
        return $course->enrollments->count();
    }),
    'days_remaining' => Carbon::now()->diffInDays($term->end_date),
];
```

### Example 4: Automatic Term Status Updates

```php
use App\Models\Term;
use Carbon\Carbon;

// Update term statuses based on dates
$terms = Term::where('is_active', true)->get();

foreach ($terms as $term) {
    $now = Carbon::now();
    
    if ($now->lt($term->start_date)) {
        $term->update(['status' => 'upcoming']);
    } elseif ($now->between($term->start_date, $term->end_date)) {
        $term->update(['status' => 'active']);
    } elseif ($now->gt($term->end_date) && $now->lt($term->grade_deadline)) {
        $term->update(['status' => 'completed']);
    } elseif ($now->gt($term->grade_deadline)) {
        $term->update(['status' => 'archived']);
    }
}
```

---

<a name="troubleshooting"></a>
## Troubleshooting

### Courses Not Appearing for Registration

**Problem:** Students cannot see courses during registration period.

**Solution:**
- Verify term status is 'active' or 'upcoming'
- Check registration dates: `registration_start <= now() <= registration_end`
- Ensure courses are properly assigned to the term
- Verify `is_active = true` for the term

```php
$term = Term::find($termId);
echo "Status: {$term->status}\n";
echo "Registration Open: " . ($term->isRegistrationOpen() ? 'Yes' : 'No') . "\n";
```

### Overlapping Terms

**Problem:** Multiple active terms causing conflicts.

**Solution:**
```php
use App\Models\Term;

// Find overlapping terms
$overlaps = Term::where('is_active', true)
    ->where('id', '!=', $currentTermId)
    ->where(function ($query) use ($startDate, $endDate) {
        $query->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function ($q) use ($startDate, $endDate) {
                  $q->where('start_date', '<=', $startDate)
                    ->where('end_date', '>=', $endDate);
              });
    })
    ->get();
```

> {info} Overlapping terms are allowed for institutions running multiple programs

### Grade Submission After Deadline

**Problem:** Faculty cannot submit grades after deadline.

**Solution:**
```php
// Extend grade deadline
$term = Term::find($termId);
$term->update([
    'grade_deadline' => Carbon::parse($term->grade_deadline)->addDays(7)
]);
```

> {warning} Only extend deadlines when absolutely necessary

### Term Deletion

**Problem:** Cannot delete term with existing courses.

**Solution:**
```php
// Check dependencies before deletion
$term = Term::withCount(['courses', 'enrollments'])->find($termId);

if ($term->courses_count > 0) {
    // Move courses to another term or delete them first
    throw new \Exception('Cannot delete term with existing courses');
}

// Safe to delete
app(DeleteTermAction::class)->execute($term);
```

> {danger} Never delete terms with historical data; archive them instead

---

## Integration with Other Modules

### Course Management
Terms are required when creating courses:

```php
$course = Course::create([
    'term_id' => $term->id,
    // other course data...
]);
```

### Enrollment Management
Students can only enroll during active registration periods:

```php
if (!$term->isRegistrationOpen()) {
    throw new \Exception('Registration is closed for this term');
}
```

### Attendance Management
Attendance tracking is term-specific:

```php
$attendances = Attendance::whereHas('courseSession.course', function ($query) use ($termId) {
    $query->where('term_id', $termId);
})->get();
```

---

## Permissions

Term management requires appropriate permissions:

- `term.index` - View terms list
- `term.create` - Create new terms
- `term.edit` - Edit existing terms
- `term.delete` - Delete terms

> {info} Permissions are managed through the Role Management module

