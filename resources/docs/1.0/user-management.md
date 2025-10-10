# User Management

---

* [Overview](#overview)
* [Features](#features)
* [User Types](#user-types)
* [Creating Users](#creating-users)
* [Managing Users](#managing-users)
* [User Roles & Permissions](#user-roles-permissions)
* [User Authentication](#user-authentication)
* [Examples](#examples)
* [Troubleshooting](#troubleshooting)

---

<a name="overview"></a>
## Overview

The User Management module is the core system for managing all user accounts including students, teachers, parents, employees, and administrators.

> {success} Centralized user management with role-based access control

> {primary} Supports multiple user types with customizable attributes and permissions

---

<a name="features"></a>
## Features

The User Management module includes:

- **Multi-Type User System** - Students, teachers, parents, employees, admins
- **Role-Based Access Control** - Granular permission management
- **Profile Management** - Customizable user profiles
- **Authentication** - Secure login with Laravel Sanctum
- **Account Status** - Active, inactive, suspended, pending
- **Bulk Operations** - Import/export users, bulk updates
- **Audit Trail** - Track user actions and changes
- **Two-Factor Authentication** - Enhanced security
- **Password Management** - Reset, change, and policy enforcement

> {info} All user data is encrypted and stored securely following GDPR guidelines

---

<a name="user-types"></a>
## User Types

The system supports multiple user types:

### Student
Children enrolled in courses and programs.

```php
use App\Models\User;

$student = User::create([
    'name' => 'Ahmad Rezaei',
    'email' => 'ahmad@example.com',
    'type' => 'student',
    'date_of_birth' => '2015-05-10',
    'parent_id' => $parentId,
]);
```

### Teacher
Instructors who teach courses.

```php
$teacher = User::create([
    'name' => 'Sara Mohammadi',
    'email' => 'sara@example.com',
    'type' => 'teacher',
    'specialization' => 'Mathematics',
    'hire_date' => now(),
]);
```

### Parent
Guardians of students.

```php
$parent = User::create([
    'name' => 'Ali Karimi',
    'email' => 'ali@example.com',
    'type' => 'parent',
    'phone' => '+98912345678',
]);

// Link children
$parent->children()->attach($studentIds);
```

### Employee
Staff members (administrative, support, etc.)

```php
$employee = User::create([
    'name' => 'Mina Ahmadi',
    'email' => 'mina@example.com',
    'type' => 'employee',
    'department' => 'Administration',
    'position' => 'Secretary',
]);
```

### Admin
System administrators.

```php
$admin = User::create([
    'name' => 'Reza Hosseini',
    'email' => 'reza@example.com',
    'type' => 'admin',
]);

$admin->assignRole('super-admin');
```

> {primary} User types determine available features and default permissions

---

<a name="creating-users"></a>
## Creating Users

### Basic User Creation

```php
use App\Actions\User\CreateUserAction;

$userData = [
    'name' => 'محمد رضایی',
    'email' => 'mohammad@example.com',
    'password' => 'SecurePassword123!',
    'type' => 'student',
    'phone' => '+98912345678',
    'date_of_birth' => '2015-03-15',
    'gender' => 'male',
    'national_id' => '1234567890',
    'address' => 'تهران، خیابان ولیعصر',
    'is_active' => true,
];

$user = app(CreateUserAction::class)->execute($userData);
```

### User with Role Assignment

```php
$userData = [
    'name' => 'فاطمه احمدی',
    'email' => 'fatemeh@example.com',
    'password' => 'SecurePassword123!',
    'type' => 'teacher',
];

$user = app(CreateUserAction::class)->execute($userData);
$user->assignRole('teacher');
```

### Bulk User Import

```php
use App\Actions\User\CreateUserAction;
use Illuminate\Support\Facades\DB;

$users = [
    ['name' => 'User 1', 'email' => 'user1@example.com', 'type' => 'student'],
    ['name' => 'User 2', 'email' => 'user2@example.com', 'type' => 'student'],
    // ... more users
];

DB::transaction(function () use ($users) {
    foreach ($users as $userData) {
        $userData['password'] = 'DefaultPassword123!';
        app(CreateUserAction::class)->execute($userData);
    }
});
```

> {success} All users receive email verification and welcome notifications

---

<a name="managing-users"></a>
## Managing Users

### Updating User Information

```php
use App\Actions\User\UpdateUserAction;

$updateData = [
    'phone' => '+98913456789',
    'address' => 'تهران، خیابان آزادی',
    'emergency_contact' => '+98914567890',
];

app(UpdateUserAction::class)->execute($user, $updateData);
```

### Changing User Status

```php
use App\Models\User;

$user = User::find($userId);

// Deactivate user
$user->update(['is_active' => false]);

// Suspend user
$user->update(['status' => 'suspended', 'suspended_reason' => 'Policy violation']);

// Reactivate user
$user->update(['is_active' => true, 'status' => 'active']);
```

### Deleting Users

```php
use App\Actions\User\DeleteUserAction;

// Soft delete - user data retained
app(DeleteUserAction::class)->execute($user);

// Restore deleted user
$user->restore();

// Permanent deletion (requires special permission)
$user->forceDelete();
```

> {warning} Deleting users with enrollments or orders may cause data integrity issues

---

<a name="user-roles-permissions"></a>
## User Roles & Permissions

### Assigning Roles

```php
use App\Models\User;

$user = User::find($userId);

// Assign single role
$user->assignRole('teacher');

// Assign multiple roles
$user->assignRole(['teacher', 'content-creator']);

// Remove role
$user->removeRole('content-creator');

// Sync roles (replace all roles)
$user->syncRoles(['teacher']);
```

### Checking Permissions

```php
// Check if user has permission
if ($user->can('course.create')) {
    // User can create courses
}

// Check if user has any of the permissions
if ($user->hasAnyPermission(['course.create', 'course.edit'])) {
    // User can create or edit courses
}

// Check if user has all permissions
if ($user->hasAllPermissions(['course.create', 'course.edit'])) {
    // User has both permissions
}
```

### Direct Permission Assignment

```php
// Give direct permission (bypass roles)
$user->givePermissionTo('special.feature');

// Check direct permission
if ($user->hasDirectPermission('special.feature')) {
    // User has direct permission
}

// Revoke direct permission
$user->revokePermissionTo('special.feature');
```

> {info} Role-based permissions are preferred over direct permission assignment

---

<a name="user-authentication"></a>
## User Authentication

### Login

```php
use Illuminate\Support\Facades\Auth;

$credentials = [
    'email' => 'user@example.com',
    'password' => 'password',
];

if (Auth::attempt($credentials)) {
    $user = Auth::user();
    // Login successful
}
```

### API Token Authentication

```php
use App\Models\User;

$user = User::find($userId);

// Create token
$token = $user->createToken('api-token')->plainTextToken;

// Revoke all tokens
$user->tokens()->delete();

// Revoke specific token
$user->tokens()->where('name', 'api-token')->delete();
```

### Password Reset

```php
use Illuminate\Support\Facades\Password;

// Send reset link
Password::sendResetLink(['email' => 'user@example.com']);

// Reset password
Password::reset($credentials, function ($user, $password) {
    $user->password = bcrypt($password);
    $user->save();
});
```

> {danger} Always use bcrypt or Hash facade for password encryption

---

<a name="examples"></a>
## Examples

### Example 1: Student Registration with Parent

```php
use App\Actions\User\CreateUserAction;

// Create parent account
$parentData = [
    'name' => 'علی کریمی',
    'email' => 'ali@example.com',
    'password' => 'SecurePass123!',
    'type' => 'parent',
    'phone' => '+98912345678',
];

$parent = app(CreateUserAction::class)->execute($parentData);

// Create student account
$studentData = [
    'name' => 'محمد کریمی',
    'email' => 'mohammad@example.com',
    'password' => 'StudentPass123!',
    'type' => 'student',
    'date_of_birth' => '2015-05-10',
    'parent_id' => $parent->id,
];

$student = app(CreateUserAction::class)->execute($studentData);

// Link parent to student
$parent->children()->attach($student->id);
```

### Example 2: Teacher Assignment to Course

```php
use App\Models\User;
use App\Models\Course;

$teacher = User::where('type', 'teacher')->find($teacherId);
$course = Course::find($courseId);

// Assign teacher to course
$course->update(['teacher_id' => $teacher->id]);

// Give course-specific permissions
$teacher->givePermissionTo("course.{$courseId}.manage");
```

### Example 3: User Search and Filter

```php
use App\Models\User;

// Search by name or email
$users = User::where('name', 'like', "%{$searchTerm}%")
    ->orWhere('email', 'like', "%{$searchTerm}%")
    ->get();

// Filter by type and status
$activeStudents = User::where('type', 'student')
    ->where('is_active', true)
    ->orderBy('name')
    ->get();

// Filter by role
$teachers = User::role('teacher')->get();

// Complex filters with Pipeline
use App\Pipelines\User\FilterByType;
use App\Pipelines\User\FilterByStatus;
use Illuminate\Pipeline\Pipeline;

$users = app(Pipeline::class)
    ->send(User::query())
    ->through([
        FilterByType::class,
        FilterByStatus::class,
    ])
    ->thenReturn()
    ->get();
```

### Example 4: User Activity Report

```php
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

$user = User::find($userId);

// Get user's recent activity
$activities = Activity::forSubject($user)
    ->latest()
    ->take(50)
    ->get();

// Get user's actions
$actions = Activity::causedBy($user)
    ->latest()
    ->take(50)
    ->get();

foreach ($actions as $activity) {
    echo "{$activity->description} at {$activity->created_at}\n";
}
```

---

<a name="troubleshooting"></a>
## Troubleshooting

### User Cannot Login

**Problem:** User enters correct credentials but cannot login.

**Solution:**
```php
$user = User::where('email', 'user@example.com')->first();

// Check account status
if (!$user->is_active) {
    echo "Account is deactivated\n";
}

// Check email verification
if (!$user->hasVerifiedEmail()) {
    echo "Email not verified\n";
    $user->sendEmailVerificationNotification();
}

// Check password
if (Hash::check('password', $user->password)) {
    echo "Password is correct\n";
}
```

### Permission Denied Errors

**Problem:** User receives permission denied when accessing features.

**Solution:**
```php
$user = User::with('roles.permissions')->find($userId);

// List user's roles
foreach ($user->roles as $role) {
    echo "Role: {$role->name}\n";
}

// List user's permissions
$permissions = $user->getAllPermissions();
foreach ($permissions as $permission) {
    echo "Permission: {$permission->name}\n";
}

// Assign missing permission
$user->givePermissionTo('required.permission');
```

> {warning} Clear permission cache after role/permission changes: `php artisan permission:cache-reset`

### Duplicate Email Registration

**Problem:** System allows duplicate email addresses.

**Solution:**
```php
// Add unique validation
$validated = $request->validate([
    'email' => 'required|email|unique:users,email',
]);

// Check before creation
$existing = User::where('email', $email)->first();
if ($existing) {
    throw new \Exception('Email already registered');
}
```

### User Data Not Updating

**Problem:** User profile changes not saving.

**Solution:**
```php
$user = User::find($userId);

// Ensure fillable fields are set in model
// protected $fillable = ['name', 'email', 'phone', ...];

// Update with validated data
$user->update($validatedData);

// Verify update
$user->refresh();
echo "Name: {$user->name}\n";
```

> {danger} Always validate user input before updating

---

## Integration with Other Modules

### Enrollment Management
Link users to courses:

```php
$student = User::find($studentId);
$enrollments = $student->enrollments()->with('course')->get();
```

### Attendance Management
Track user attendance:

```php
$attendanceRate = $user->getAttendanceRate($courseId);
```

### Order Management
User purchase history:

```php
$orders = $user->orders()->with('payments')->get();
```

---

## Permissions

User management requires appropriate permissions:

- `user.index` - View users list
- `user.create` - Create new users
- `user.edit` - Edit existing users
- `user.delete` - Delete users
- `user.restore` - Restore deleted users
- `user.impersonate` - Login as another user (admin only)

> {info} Super admins have all permissions by default

