# Role & Permission Management

---

* [Overview](#overview)
* [Features](#features)
* [Role Structure](#role-structure)
* [Creating Roles](#creating-roles)
* [Managing Permissions](#managing-permissions)
* [Role Assignment](#role-assignment)
* [Permission Checking](#permission-checking)
* [Examples](#examples)
* [Troubleshooting](#troubleshooting)

---

<a name="overview"></a>
## Overview

The Role & Permission Management module provides a comprehensive system for managing user access control through roles and permissions based on Laravel Spatie Permission package.

> {success} Granular access control with role-based and direct permission assignment

> {primary} Hierarchical roles with permission inheritance

---

<a name="features"></a>
## Features

The Role Management module includes:

- **Role-Based Access Control (RBAC)** - Assign permissions through roles
- **Direct Permissions** - Assign permissions directly to users
- **Permission Groups** - Organize permissions logically
- **Role Hierarchy** - Super admin > Admin > Manager > User
- **Dynamic Permissions** - Create permissions for new features
- **Permission Caching** - High-performance permission checks
- **Guard Support** - Separate permissions for web and API
- **Audit Trail** - Track permission changes

> {info} Permissions are cached for optimal performance

---

<a name="role-structure"></a>
## Role Structure

### Default Roles

The system includes predefined roles:

- **super-admin** - Full system access
- **admin** - Administrative access
- **teacher** - Instructor access
- **parent** - Parent/guardian access
- **student** - Student access
- **employee** - Staff member access

### Permission Naming Convention

Permissions follow the pattern: `{model}.{action}`

Examples:
- `course.index` - View courses list
- `course.create` - Create new courses
- `course.edit` - Edit existing courses
- `course.delete` - Delete courses

> {primary} Consistent naming convention improves maintainability

---

<a name="creating-roles"></a>
## Creating Roles

### Basic Role Creation

```php
use App\Actions\Role\CreateRoleAction;

$roleData = [
    'name' => 'content-manager',
    'display_name' => 'Content Manager',
    'description' => 'Manages blog posts, pages, and media content',
    'guard_name' => 'web',
];

$role = app(CreateRoleAction::class)->execute($roleData);
```

### Role with Permissions

```php
use App\Actions\Role\CreateRoleAction;
use Spatie\Permission\Models\Permission;

// Create role
$role = app(CreateRoleAction::class)->execute([
    'name' => 'course-coordinator',
    'display_name' => 'Course Coordinator',
]);

// Assign permissions
$permissions = [
    'course.index',
    'course.create',
    'course.edit',
    'enrollment.index',
    'enrollment.create',
];

foreach ($permissions as $permissionName) {
    $permission = Permission::findOrCreate($permissionName);
    $role->givePermissionTo($permission);
}
```

### Creating Custom Permissions

```php
use Spatie\Permission\Models\Permission;

// Create single permission
$permission = Permission::create([
    'name' => 'blog.publish',
    'guard_name' => 'web',
]);

// Create multiple permissions for a model
$model = 'Course';
$actions = ['index', 'create', 'edit', 'delete', 'restore'];

foreach ($actions as $action) {
    Permission::findOrCreate(strtolower($model) . '.' . strtolower($action));
}
```

> {success} Permissions are automatically created when using make:policy command

---

<a name="managing-permissions"></a>
## Managing Permissions

### Assigning Permissions to Role

```php
use Spatie\Permission\Models\Role;

$role = Role::findByName('teacher');

// Assign single permission
$role->givePermissionTo('course.create');

// Assign multiple permissions
$role->givePermissionTo([
    'course.index',
    'course.create',
    'course.edit',
]);

// Sync permissions (replace all existing)
$role->syncPermissions([
    'course.index',
    'attendance.index',
    'attendance.create',
]);
```

### Removing Permissions from Role

```php
$role = Role::findByName('teacher');

// Remove single permission
$role->revokePermissionTo('course.delete');

// Remove multiple permissions
$role->revokePermissionTo(['course.delete', 'course.restore']);
```

### Updating Role Information

```php
use App\Actions\Role\UpdateRoleAction;

$updateData = [
    'display_name' => 'Senior Teacher',
    'description' => 'Experienced teacher with additional privileges',
];

app(UpdateRoleAction::class)->execute($role, $updateData);
```

---

<a name="role-assignment"></a>
## Role Assignment

### Assigning Roles to Users

```php
use App\Models\User;

$user = User::find($userId);

// Assign single role
$user->assignRole('teacher');

// Assign multiple roles
$user->assignRole(['teacher', 'content-creator']);

// Remove role
$user->removeRole('content-creator');

// Sync roles (replace all existing)
$user->syncRoles(['teacher']);
```

### Checking User Roles

```php
$user = User::find($userId);

// Check if user has role
if ($user->hasRole('admin')) {
    // User is admin
}

// Check if user has any of the roles
if ($user->hasAnyRole(['admin', 'super-admin'])) {
    // User is admin or super-admin
}

// Check if user has all roles
if ($user->hasAllRoles(['teacher', 'content-creator'])) {
    // User has both roles
}

// Get user's roles
$roles = $user->getRoleNames(); // Returns collection of role names
```

---

<a name="permission-checking"></a>
## Permission Checking

### In Controllers

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        // Using middleware
        $this->authorize('course.index');
        
        // Or using Gate
        if (Gate::allows('course.index')) {
            // User can view courses
        }
        
        // Or using User model
        if (auth()->user()->can('course.index')) {
            // User can view courses
        }
    }
    
    public function create()
    {
        $this->authorize('course.create');
        
        // Create course logic
    }
}
```

### In Blade Templates

```blade
@can('course.create')
    <a href="{{ route('admin.course.create') }}" class="btn">
        Create Course
    </a>
@endcan

@role('admin')
    <div class="admin-panel">
        <!-- Admin-only content -->
    </div>
@endrole

@hasrole('teacher')
    <div class="teacher-tools">
        <!-- Teacher-only tools -->
    </div>
@endhasrole
```

### In Routes

```php
use Illuminate\Support\Facades\Route;

// Using middleware
Route::middleware(['permission:course.create'])->group(function () {
    Route::post('/courses', [CourseController::class, 'store']);
});

// Using role middleware
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});

// Multiple permissions (OR logic)
Route::middleware(['permission:course.create|course.edit'])->group(function () {
    Route::get('/courses/manage', [CourseController::class, 'manage']);
});
```

### In Livewire Components

```php
namespace App\Livewire\Admin\Course;

use Livewire\Component;

class CreateCourse extends Component
{
    public function mount()
    {
        $this->authorize('course.create');
    }
    
    public function create()
    {
        if (!auth()->user()->can('course.create')) {
            abort(403);
        }
        
        // Create course logic
    }
    
    public function render()
    {
        return view('livewire.admin.course.create-course');
    }
}
```

---

<a name="examples"></a>
## Examples

### Example 1: Setup Complete Role Structure

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// Define role hierarchy
$roles = [
    'super-admin' => [
        'display_name' => 'Super Administrator',
        'description' => 'Full system access',
        'permissions' => ['*'], // All permissions
    ],
    'admin' => [
        'display_name' => 'Administrator',
        'description' => 'Administrative access',
        'permissions' => ['user.*', 'course.*', 'order.*', 'payment.*'],
    ],
    'teacher' => [
        'display_name' => 'Teacher',
        'description' => 'Instructor access',
        'permissions' => [
            'course.index',
            'attendance.index',
            'attendance.create',
            'attendance.edit',
            'enrollment.index',
        ],
    ],
    'student' => [
        'display_name' => 'Student',
        'description' => 'Student access',
        'permissions' => [
            'course.index',
            'enrollment.index',
            'enrollment.create',
        ],
    ],
];

foreach ($roles as $roleName => $roleData) {
    $role = Role::firstOrCreate(['name' => $roleName], [
        'guard_name' => 'web',
    ]);
    
    if ($roleData['permissions'] === ['*']) {
        // Super admin gets all permissions
        $role->syncPermissions(Permission::all());
    } else {
        $permissions = [];
        foreach ($roleData['permissions'] as $permission) {
            if (str_contains($permission, '*')) {
                // Wildcard permission (e.g., 'user.*')
                $prefix = str_replace('.*', '', $permission);
                $permissions = array_merge(
                    $permissions,
                    Permission::where('name', 'like', $prefix . '.%')->pluck('name')->toArray()
                );
            } else {
                $permissions[] = $permission;
            }
        }
        $role->syncPermissions($permissions);
    }
}
```

### Example 2: Dynamic Permission Generation

```php
use Spatie\Permission\Models\Permission;

$models = [
    'User',
    'Course',
    'Enrollment',
    'Attendance',
    'Order',
    'Payment',
    'Blog',
    'Page',
];

$actions = ['index', 'create', 'edit', 'delete', 'restore'];

foreach ($models as $model) {
    foreach ($actions as $action) {
        Permission::findOrCreate(
            strtolower($model) . '.' . strtolower($action),
            'web'
        );
    }
}
```

### Example 3: Check Multiple Permissions

```php
use App\Services\Permissions\PermissionsService;

$user = auth()->user();

// Check if user can manage courses
$canManageCourses = $user->hasAnyPermission(
    PermissionsService::generatePermissionsByModel('Course', ['Index', 'Create', 'Edit'])
);

if ($canManageCourses) {
    // Show course management interface
}

// Check specific model instance
$course = Course::find($courseId);

if ($user->can('edit', $course)) {
    // User can edit this specific course
}
```

### Example 4: Permission Report

```php
use Spatie\Permission\Models\Role;
use App\Models\User;

// Get role usage statistics
$roles = Role::withCount('users')->get();

foreach ($roles as $role) {
    echo "{$role->name}: {$role->users_count} users\n";
    
    $permissions = $role->permissions->pluck('name');
    echo "  Permissions: " . $permissions->join(', ') . "\n";
}

// Find users with specific permission
$usersWithPermission = User::permission('course.delete')->get();

// Find users with specific role
$admins = User::role('admin')->get();
```

---

<a name="troubleshooting"></a>
## Troubleshooting

### Permission Denied After Assignment

**Problem:** User still gets permission denied after assigning role/permission.

**Solution:**
```bash
# Clear permission cache
php artisan permission:cache-reset

# Or in code
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
```

```php
// Verify permission assignment
$user = User::with('roles.permissions', 'permissions')->find($userId);

echo "Roles:\n";
foreach ($user->roles as $role) {
    echo "  - {$role->name}\n";
}

echo "Permissions:\n";
foreach ($user->getAllPermissions() as $permission) {
    echo "  - {$permission->name}\n";
}
```

### Role Cannot Be Deleted

**Problem:** Error when trying to delete role with assigned users.

**Solution:**
```php
use Spatie\Permission\Models\Role;

$role = Role::findByName('old-role');

// Check if role has users
if ($role->users()->count() > 0) {
    // Reassign users to different role
    $newRole = Role::findByName('new-role');
    
    foreach ($role->users as $user) {
        $user->removeRole($role);
        $user->assignRole($newRole);
    }
}

// Now safe to delete
$role->delete();
```

### Super Admin Losing Permissions

**Problem:** Super admin getting permission denied errors.

**Solution:**
```php
// Add to AuthServiceProvider
public function boot()
{
    $this->registerPolicies();
    
    // Super admins bypass all gates
    Gate::before(function ($user, $ability) {
        if ($user->hasRole('super-admin')) {
            return true;
        }
    });
}
```

### Permission Not Working in Blade

**Problem:** `@can` directive not working in blade templates.

**Solution:**
```blade
{{-- Ensure user is authenticated --}}
@auth
    @can('course.create')
        <button>Create Course</button>
    @endcan
@endauth

{{-- Check if permission exists --}}
@if(auth()->check() && auth()->user()->can('course.create'))
    <button>Create Course</button>
@endif
```

> {danger} Always clear permission cache after making role/permission changes

---

## Permission Generation Helper

Use the included helper to generate permissions:

```php
use App\Services\Permissions\PermissionsService;

// Generate CRUD permissions for a model
$permissions = PermissionsService::generatePermissionsByModel('Course', 'Index');
// Returns: ['course.index']

$permissions = PermissionsService::generatePermissionsByModel('Course', ['Index', 'Create', 'Edit', 'Delete']);
// Returns: ['course.index', 'course.create', 'course.edit', 'course.delete']

// Use in authorization
if ($user->hasAnyPermission($permissions)) {
    // User has at least one of the permissions
}
```

---

## Integration with Policies

Define authorization logic in policies:

```php
namespace App\Policies;

use App\Models\User;
use App\Models\Course;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('course.index');
    }
    
    public function create(User $user): bool
    {
        return $user->can('course.create');
    }
    
    public function update(User $user, Course $course): bool
    {
        // Check permission and ownership
        return $user->can('course.edit') && 
               ($user->id === $course->teacher_id || $user->hasRole('admin'));
    }
    
    public function delete(User $user, Course $course): bool
    {
        return $user->can('course.delete');
    }
}
```

---

## Permissions

Role management requires appropriate permissions:

- `role.index` - View roles list
- `role.create` - Create new roles
- `role.edit` - Edit existing roles
- `role.delete` - Delete roles
- `permission.assign` - Assign permissions to roles
- `permission.revoke` - Revoke permissions from roles

> {info} Only super admins can manage roles and permissions by default

