# Course Management System

---

* [Overview](#overview)
* [Architecture](#architecture)
* [Key Models](#models)
* [Enums](#enums)
* [Features](#features)
* [Business Methods](#methods)
* [Database Relationships](#relationships)
* [Security](#security)
* [Usage Examples](#examples)
* [Benefits](#benefits)

---

<a name="overview"></a>
## Overview

A comprehensive template-based course management system supporting multiple course types, terms, payments, and progress tracking.

> {success} Successfully refactored from simple Course→Session→Resource hierarchy to a flexible template-based system

> {primary} Supports in-person, online, hybrid, and self-paced courses

> {info} Complete progress tracking and certificate generation

---

<a name="architecture"></a>
## New Architecture

### Template-Based Design

The system separates **templates** (fixed content definitions) from **instances** (actual course runs):

| Component | Description |
|-----------|-------------|
| **CourseTemplate** | Defines course structure (e.g., English Level 1) |
| **SessionTemplate** | Defines individual lessons (e.g., Lesson 1: Alphabet) |
| **Course** | Actual course instance in a specific term |
| **Session** | Actual scheduled class sessions |

### Hierarchy Flow

```
CourseTemplate (1) → (n) SessionTemplate
       ↓
Course Instance → Session Instance
       ↓
   Enrollment → Attendance
       ↓
   Certificate
```

---

<a name="models"></a>
## Key Models Created

### Core Templates

#### 1. CourseTemplate
Course definitions with metadata, description, and pricing information.

```php
$template = CourseTemplate::create([
    'title' => 'English Level 1',
    'description' => 'Beginner English course',
    'duration_weeks' => 12,
]);
```

#### 2. SessionTemplate
Lesson definitions with ordering and content structure.

```php
$session = SessionTemplate::create([
    'course_template_id' => $template->id,
    'title' => 'Lesson 1: Alphabet',
    'order' => 1,
]);
```

#### 3. Resource
Polymorphic educational materials (PDFs, videos, images, links).

```php
$resource = Resource::create([
    'resourceable_type' => SessionTemplate::class,
    'resourceable_id' => $session->id,
    'type' => ResourceType::VIDEO,
    'url' => 'https://...',
]);
```

### Academic Structure

#### 4. Term
Academic periods (Fall 2025, Spring 2026).

```php
$term = Term::create([
    'name' => 'Fall 2025',
    'start_date' => '2025-09-01',
    'end_date' => '2025-12-31',
    'status' => TermStatus::ACTIVE,
]);
```

#### 5. Room
Physical locations for in-person courses.

```php
$room = Room::create([
    'name' => 'Room 101',
    'capacity' => 30,
    'location' => 'Building A, Floor 1',
]);
```

### Course Execution

#### 6. Course
Real course instances with teachers and schedules.

```php
$course = Course::create([
    'course_template_id' => $template->id,
    'term_id' => $term->id,
    'teacher_id' => $teacher->id,
    'type' => CourseType::ONLINE,
    'price' => 99.99,
    'capacity' => 30,
]);
```

#### 7. Session
Scheduled class sessions (virtual for self-paced).

```php
$session = Session::create([
    'course_id' => $course->id,
    'session_template_id' => $sessionTemplate->id,
    'start_time' => '2025-09-01 10:00:00',
    'end_time' => '2025-09-01 12:00:00',
    'type' => SessionType::ONLINE,
]);
```

### Student Management

#### 8. Enrollment
Student registrations with progress tracking.

```php
$enrollment = Enrollment::create([
    'course_id' => $course->id,
    'user_id' => $student->id,
    'order_item_id' => $orderItem->id,
    'status' => EnrollmentStatus::ACTIVE,
    'progress' => 0,
]);
```

#### 9. Attendance
Session attendance records.

```php
$attendance = Attendance::create([
    'enrollment_id' => $enrollment->id,
    'session_id' => $session->id,
    'status' => 'present',
    'attended_at' => now(),
]);
```

#### 10. Certificate
Course completion certificates with signatures.

```php
$certificate = Certificate::create([
    'enrollment_id' => $enrollment->id,
    'issued_at' => now(),
    'certificate_number' => 'CERT-2025-001',
    'signature' => '...',
]);
```

---

<a name="enums"></a>
## Enums Created

### CourseType
- `IN_PERSON` - Traditional classroom courses
- `ONLINE` - Live virtual sessions
- `HYBRID` - Combination of in-person and online
- `SELF_PACED` - Udemy-style courses with virtual sessions

### SessionType
- `IN_PERSON` - Physical classroom
- `ONLINE` - Virtual meeting
- `HYBRID` - Both formats

### CourseStatus
- `DRAFT` - Being prepared
- `SCHEDULED` - Ready to start
- `ACTIVE` - Currently running
- `FINISHED` - Completed
- `CANCELLED` - Not happening

### SessionStatus
- `PLANNED` - Scheduled but not conducted
- `DONE` - Completed
- `CANCELLED` - Not conducted

### EnrollmentStatus
- `PENDING` - Awaiting payment
- `PAID` - Payment received
- `ACTIVE` - Currently enrolled
- `DROPPED` - Student withdrew

### ResourceType
- `PDF` - PDF documents
- `VIDEO` - Video files
- `IMAGE` - Images
- `LINK` - External links

### TermStatus
- `DRAFT` - Planning phase
- `ACTIVE` - Current term
- `FINISHED` - Past term

---

<a name="features"></a>
## Key Features Implemented

### Course Types Support

> {success} **In-Person:** Traditional classroom courses with room assignments

> {primary} **Online:** Live virtual sessions with video conferencing

> {info} **Hybrid:** Flexible combination of in-person and online

> {warning} **Self-Paced:** Udemy-style courses with unlimited capacity

### Resource Management
- Polymorphic resources attached to templates or instances
- Support for PDFs, videos, images, and links
- Signed URLs for secure video access
- RBAC for access control

### Progress Tracking
- Enrollment progress percentage (0-100%)
- Granular session completion tracking
- Activity logging for detailed analytics
- Automatic certificate issuance on completion

### Payment Integration
- OrderItem model with polymorphic relationships
- Support for enrollment-based payments
- Refund policy handling
- Discount calculation support

### Scheduling System
- Automatic session generation from templates
- Support for multiple days per week
- Room availability checking
- Conflict detection and resolution

### Attendance Management
- Present/absent tracking with timestamps
- Excuse note support
- Quality scoring based on punctuality
- Attendance percentage calculations

---

<a name="methods"></a>
## Business Methods Implemented

### Course Management

#### enrollStudent()
Safe enrollment with capacity checking and race condition prevention.

```php
$enrollment = $course->enrollStudent($student->id, $orderItem->id);
```

#### reserveSeat()
Temporary seat reservation during checkout process.

```php
$reservation = $course->reserveSeat($student->id);
```

#### cloneSessions()
Auto-generate sessions from templates.

```php
$course->cloneSessions();
// Creates all sessions from course template
```

#### isAtCapacity()
Check if course is full.

```php
if ($course->isAtCapacity()) {
    // Show waitlist option
}
```

### Progress Tracking

#### updateProgress()
Update enrollment progress percentage.

```php
$enrollment->updateProgress(75.0); // 75% complete
```

#### markAsCompleted()
Mark course as completed and trigger certificate generation.

```php
$enrollment->markAsCompleted();
```

#### issueCertificate()
Generate certificate with signature.

```php
$certificate = $enrollment->issueCertificate();
```

### Resource Security

#### getSignedUrl()
Generate secure time-limited URLs for resources.

```php
$url = $resource->getSignedUrl(expiration: 3600);
// Valid for 1 hour
```

#### generateSignature()
Create certificate verification signature.

```php
$signature = $certificate->generateSignature();
```

#### verifySignature()
Validate certificate authenticity.

```php
if ($certificate->verifySignature($signature)) {
    // Valid certificate
}
```

### Analytics & Reporting

#### getUserStatistics()
Get student activity analytics.

```php
$stats = $enrollment->getUserStatistics();
// Returns: attendance rate, progress, activity count
```

#### getCourseStatistics()
Get course performance metrics.

```php
$stats = $course->getCourseStatistics();
// Returns: enrollment count, completion rate, revenue
```

#### getSalesStatistics()
Track revenue and sales.

```php
$stats = OrderItem::getSalesStatistics($term);
```

---

<a name="relationships"></a>
## Database Relationships

### Template Hierarchy
```
CourseTemplate (1) → (n) SessionTemplate
CourseTemplate (1) → (n) Course (instances)
SessionTemplate (1) → (n) Session (instances)
```

### Resource Polymorphism
```
Resource → morphTo → [CourseTemplate, SessionTemplate, Session]
```

### Student Journey
```
Course (1) → (n) Enrollment (1) → (n) Attendance
Enrollment (1) → (1) Certificate
```

### Payment Flow
```
OrderItem → morphTo → [Course, Enrollment]
```

---

<a name="security"></a>
## Security Features

> {success} **Signed URLs:** Secure video resource access with time limits

> {primary} **Certificate Verification:** Cryptographic signature validation

> {info} **RBAC:** Role-based access control for resources

> {warning} **Audit Logging:** Complete activity tracking via ActivityLog

> {success} **Race Condition Prevention:** Atomic capacity checking during enrollment

### Access Control Example

```php
// Check if user can access resource
if ($user->can('view', $resource)) {
    $url = $resource->getSignedUrl();
}
```

---

<a name="examples"></a>
## Usage Examples

### Creating a Self-Paced Course

```php
use App\Enums\CourseType;

$course = Course::create([
    'course_template_id' => $template->id,
    'term_id' => $longTerm->id,
    'teacher_id' => $teacher->id,
    'type' => CourseType::SELF_PACED,
    'price' => 99.99,
    'capacity' => null, // Unlimited
]);

// Create virtual sessions automatically
$course->cloneSessions();
```

### Enrolling a Student

```php
// Enroll with payment verification
$enrollment = $course->enrollStudent($student->id, $orderItem->id);

// Update progress as student completes lessons
$enrollment->updateProgress(25.0); // 25% complete
$enrollment->updateProgress(50.0); // 50% complete
$enrollment->updateProgress(100.0); // Automatically issues certificate
```

### Tracking Progress

```php
// Log activity
$enrollment->logActivity('video.watched', [
    'resource_id' => $video->id,
    'duration' => 1800, // 30 minutes
]);

$enrollment->logActivity('quiz.completed', [
    'score' => 85,
    'passed' => true,
]);

// Get statistics
$stats = $enrollment->getUserStatistics();
echo "Attendance: {$stats['attendance_rate']}%";
echo "Progress: {$stats['progress']}%";
```

### Recording Attendance

```php
$attendance = Attendance::create([
    'enrollment_id' => $enrollment->id,
    'session_id' => $session->id,
    'status' => 'present',
    'attended_at' => now(),
]);

// With excuse
$attendance = Attendance::create([
    'enrollment_id' => $enrollment->id,
    'session_id' => $session->id,
    'status' => 'absent',
    'excuse_note' => 'Medical appointment',
]);
```

---

<a name="benefits"></a>
## Benefits Achieved

### 1. Flexibility
Support for multiple course types and delivery methods (in-person, online, hybrid, self-paced).

### 2. Reusability
Templates eliminate content duplication - create once, use many times.

### 3. Scalability
Polymorphic design supports future expansion without schema changes.

### 4. Analytics
Comprehensive activity tracking and reporting for data-driven decisions.

### 5. Security
Proper access controls, signed URLs, and verification systems.

### 6. Maintainability
Clear separation of concerns and well-defined relationships.

---

## Migration Path

> {info} The new system is designed to work alongside existing data

Key migration steps:

1. Create new template records from existing courses
2. Migrate existing sessions to new Session model
3. Update resource relationships to polymorphic
4. Migrate enrollment data with progress tracking
5. Generate certificates for completed courses

---

This refactored system provides a solid foundation for a modern, scalable course management platform that can handle diverse educational needs while maintaining data integrity and performance.

