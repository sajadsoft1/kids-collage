# Project Architecture & Component Analysis

---

* [Overall Architecture](#architecture)
* [Key Technologies](#technologies)
* [Folder Structure](#structure)
* [Main Responsibilities](#responsibilities)
* [Special Patterns](#patterns)
* [UI/UX Framework](#ui-framework)

---

<a name="architecture"></a>
## Overall Architecture & Main Components

This is a **modern Laravel 12 CMS/Portfolio application** built with a **Livewire-first approach** and follows a **modular, action-based architecture**.

The application serves both as a content management system and a public-facing portfolio website.

> {success} Production-ready, enterprise-grade CMS

> {primary} Modern Laravel development practices

> {info} Focus on maintainability, scalability, and user experience

---

<a name="technologies"></a>
## Key Technologies & Libraries

### Backend Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 12 | Core framework (PHP 8.3+) |
| **Livewire** | 3 | Full-stack reactive components |
| **Laravel Actions** | Latest | Single-action classes for business logic |
| **Spatie Media Library** | Latest | Advanced file handling |
| **Spatie Permissions** | Latest | Role-based access control |
| **Spatie Activity Log** | Latest | Audit trails |
| **PowerGrid** | Latest | Advanced data tables |
| **Mary UI** | Latest | Component library for Livewire |

### Frontend Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| **Tailwind CSS** | 4 | Utility-first CSS framework |
| **DaisyUI** | Latest | Component library for Tailwind |
| **Alpine.js** | 3 | Lightweight JavaScript framework |
| **Vite** | Latest | Build tool and dev server |

### Special Features

> {success} **Multilingual Support:** English/Farsi with Persian date handling (Jalali calendar)

> {primary} **SEO Tools:** Comprehensive SEO management with meta tags, Open Graph, JSON-LD

> {info} **Media Management:** Advanced file handling with automatic conversions

> {warning} **Role-based Permissions:** Granular access control with policies

---

<a name="structure"></a>
## Folder & File Structure

### Core Architecture

```
app/
├── Actions/          # Single-action classes (CRUD operations)
│   ├── Attendance/
│   ├── Auth/
│   ├── Banner/
│   ├── Blog/
│   ├── Category/
│   ├── Course/
│   └── ...
├── Livewire/         # Reactive components (Admin & Web)
│   ├── Admin/
│   │   ├── Pages/
│   │   ├── Shared/
│   │   └── Widget/
│   └── Web/
├── Models/           # Eloquent models with traits
│   ├── User.php
│   ├── Blog.php
│   ├── Course.php
│   └── ...
├── Services/         # Business logic services
│   ├── SmartCache.php
│   ├── SeoService.php
│   └── ...
├── Enums/           # Type-safe enumerations
│   ├── CourseType.php
│   ├── UserRole.php
│   └── ...
├── Traits/          # Reusable model traits
│   ├── HasModelCache.php
│   ├── HasScheduledPublishing.php
│   └── ...
└── Policies/        # Authorization policies
    ├── BlogPolicy.php
    ├── CoursePolicy.php
    └── ...
```

### Key Patterns

#### 1. Action Pattern
Each CRUD operation is encapsulated in a single-action class.

```php
// app/Actions/Blog/CreateBlog.php
class CreateBlog
{
    public function handle(array $data): Blog
    {
        return DB::transaction(function () use ($data) {
            $blog = Blog::create($data);
            $blog->syncTags($data['tags']);
            return $blog;
        });
    }
}
```

#### 2. Livewire Components
Both admin panel and public website use Livewire for reactive interfaces.

```php
// app/Livewire/Admin/Pages/Blog/BlogTable.php
class BlogTable extends Component
{
    use WithPagination;
    
    public function render()
    {
        return view('livewire.admin.pages.blog.blog-table', [
            'blogs' => Blog::paginate(10),
        ]);
    }
}
```

#### 3. Service Layer
Business logic separated into service classes for reusability.

```php
// app/Services/SeoService.php
class SeoService
{
    public function generateMetaTags(Model $model): array
    {
        // Generate meta tags
    }
}
```

#### 4. Enum-based Configuration
Type-safe configuration using PHP 8.3+ enums.

```php
// app/Enums/CourseType.php
enum CourseType: string
{
    case IN_PERSON = 'in-person';
    case ONLINE = 'online';
    case HYBRID = 'hybrid';
    case SELF_PACED = 'self-paced';
}
```

---

<a name="responsibilities"></a>
## Main Responsibilities

### Admin Panel (`/admin`)

#### Content Management
- **Blog System:** Article creation, editing, scheduling, categories, tags
- **Portfolio:** Project showcase with media galleries
- **Pages:** Static page management with SEO
- **Categories & Tags:** Taxonomy management

#### Media Management
- **Banners:** Hero banners with responsive images
- **Sliders:** Image carousels for homepage
- **Media Library:** Advanced file uploads with automatic conversions

#### User Management
- **Users:** User CRUD with roles and permissions
- **Roles:** Role-based access control (RBAC)
- **Permissions:** Granular permission management

#### System Settings
- **SEO Settings:** Site-wide SEO configuration
- **General Settings:** Application settings
- **Social Media:** Social media links and integration

#### Support System
- **Tickets:** Customer support ticket system
- **Comments:** Comment moderation
- **FAQ Management:** Frequently asked questions

#### Course Management
- **Course Templates:** Reusable course definitions
- **Courses:** Actual course instances
- **Sessions:** Scheduled classes
- **Enrollments:** Student registrations
- **Attendance:** Attendance tracking
- **Certificates:** Certificate generation

### Public Website (`/`)

#### Portfolio Showcase
- Project displays with filtering by category/tag
- Lightbox image galleries
- Project detail pages

#### Blog System
- Article listing with pagination
- Article detail with comments
- Category and tag filtering
- Search functionality

#### Service Pages
- SEO services
- Web development services
- Application development
- Custom service pages

#### Contact & Support
- Contact forms
- FAQ display
- Opinion/testimonial system
- Newsletter subscription

---

<a name="patterns"></a>
## Special Patterns & Architecture

### 1. Action-Based Architecture

> {success} Clean, testable business logic

Each CRUD operation is encapsulated in a single-action class using the `Laravel Actions` package.

**Benefits:**
- Single Responsibility Principle
- Easy to test
- Reusable across different contexts
- Clear separation of concerns

**Example:**
```php
// Usage in controller
$blog = app(CreateBlog::class)->handle($request->validated());

// Usage in Livewire
$blog = CreateBlog::run($this->form);

// Usage in job
dispatch(fn() => CreateBlog::run($data));
```

### 2. Livewire-First Approach

> {primary} Real-time updates without page refreshes

Both admin and public interfaces built with Livewire for better UX.

**Benefits:**
- No page refreshes needed
- Component-based architecture
- Better code organization
- Reactive data binding

**Example:**
```php
class BlogUpdateOrCreate extends Component
{
    public $form;
    
    public function save()
    {
        $this->validate();
        CreateBlog::run($this->form);
        $this->success('Blog created!');
    }
}
```

### 3. Multilingual Support

> {info} Built-in English/Farsi language support

**Features:**
- Route-based language switching
- Persian date handling with Jalali calendar
- RTL layout support
- Translatable content

**Example:**
```php
// routes/web.php
Route::multilingual(function () {
    Route::get('/', HomeController::class);
    Route::get('/blog', BlogController::class);
});

// Automatic routes:
// /en/blog (English)
// /fa/blog (فارسی)
```

### 4. Advanced Media Management

> {warning} Spatie Media Library integration

**Features:**
- Automatic image conversions (thumbnails, medium, large)
- File organization by model
- Image optimization
- Responsive images

**Example:**
```php
$blog->addMedia($request->file('image'))
    ->toMediaCollection('featured');

// Get responsive images
$blog->getFirstMediaUrl('featured', 'thumb');
$blog->getFirstMediaUrl('featured', 'medium');
```

### 5. SEO-First Design

> {success} Comprehensive SEO management

**Features:**
- Dynamic meta tags (title, description, keywords)
- Open Graph tags
- Twitter Card tags
- JSON-LD structured data
- Sitemap generation
- Canonical URLs

**Example:**
```php
// In controller
SEOMeta::setTitle($blog->title);
SEOMeta::setDescription($blog->excerpt);
OpenGraph::setType('article');
OpenGraph::addImage($blog->featured_image_url);
JsonLd::setType('Article');
```

### 6. Role-Based Security

> {danger} Granular permissions system

**Features:**
- Spatie Permissions package
- Policy-based authorization
- Activity logging for audit trails
- Gate-based access control

**Example:**
```php
// Define permission
Permission::create(['name' => 'edit blogs']);

// Assign to role
$role->givePermissionTo('edit blogs');

// Check permission
if ($user->can('edit blogs')) {
    // Allow editing
}

// Use in Livewire
#[Can('edit', 'blog')]
public function edit($id) { }
```

### 7. Smart Caching System

> {primary} Custom caching with automatic invalidation

**Features:**
- Model-based cache prefixing
- Tag-based cache drivers support
- Automatic cache flushing on model changes
- Fluent builder API

**Example:**
```php
use App\Traits\HasModelCache;

class Blog extends Model
{
    use HasModelCache;
}

// Cache data
$blog->putCache('stats', ['views' => 100], now()->addHour());

// Retrieve cached data
$stats = $blog->getCache('stats', []);

// Auto-flush on update
$blog->update(['title' => 'New Title']); // Cache automatically cleared
```

### 8. Scheduled Publishing

> {info} Automatic content publishing at scheduled times

**Features:**
- Queue-based publishing
- Scheduled commands
- Progress tracking
- Activity logging

**Example:**
```php
use App\Traits\HasScheduledPublishing;

class Blog extends Model
{
    use HasScheduledPublishing;
}

// Schedule for publishing
$blog->update([
    'published' => false,
    'published_at' => now()->addDays(7),
]);

// Automatic publishing happens via scheduled command
```

---

<a name="ui-framework"></a>
## UI/UX Framework

### Component System

> {success} DaisyUI + Mary UI for consistent design

**DaisyUI Components:**
- Cards, Modals, Dropdowns
- Forms, Inputs, Buttons
- Navigation, Breadcrumbs
- Alerts, Badges, Toasts

**Mary UI (Livewire Components):**
- Data tables with PowerGrid
- Forms with validation
- File uploads with progress
- Toast notifications
- Modal dialogs

### Design Features

> {primary} Modern, responsive, accessible design

- **Responsive Design:** Mobile-first approach with breakpoints
- **Dark/Light Theme:** Theme switcher with local storage
- **Persian RTL:** Full RTL layout support for Persian
- **Accessibility:** ARIA labels, keyboard navigation
- **Loading States:** Skeleton screens, spinners
- **Transitions:** Smooth animations with Alpine.js

### Example Component Usage

```blade
{{-- Mary UI Table --}}
<x-table :headers="$headers" :rows="$blogs">
    @scope('cell_actions', $blog)
        <x-button wire:click="edit({{ $blog->id }})" icon="o-pencil" />
        <x-button wire:click="delete({{ $blog->id }})" icon="o-trash" />
    @endscope
</x-table>

{{-- DaisyUI Card --}}
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title">{{ $blog->title }}</h2>
        <p>{{ $blog->excerpt }}</p>
        <div class="card-actions justify-end">
            <button class="btn btn-primary">Read More</button>
        </div>
    </div>
</div>
```

---

## Summary

This is a **production-ready, enterprise-grade CMS** that demonstrates modern Laravel development practices with:

✅ Clean architecture with separation of concerns
✅ Type-safe code using PHP 8.3+ features
✅ Reactive UI with Livewire 3
✅ Comprehensive testing coverage
✅ Security best practices
✅ Performance optimization
✅ Maintainable and scalable codebase

The application is designed for:
- Content-heavy websites
- Portfolio and blog platforms
- Course management systems
- Multi-tenant applications
- API-first architectures

