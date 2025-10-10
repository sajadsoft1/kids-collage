# Content Management

---

* [Overview](#overview)
* [Blog Management](#blog-management)
* [Portfolio Management](#portfolio-management)
* [Page Management](#page-management)
* [Category & Tag Management](#category-tag-management)
* [Bulletin Management](#bulletin-management)
* [SEO Optimization](#seo-optimization)
* [Examples](#examples)

---

<a name="overview"></a>
## Overview

The Content Management system provides comprehensive tools for managing various types of content including blogs, portfolios, custom pages, bulletins, and more.

> {success} Flexible content creation with SEO optimization and scheduling

> {primary} Multi-language support and media library integration

---

<a name="blog-management"></a>
## Blog Management

### Creating Blog Posts

```php
use App\Actions\Blog\CreateBlogAction;

$blogData = [
    'title' => 'New Features in Our Learning Platform',
    'slug' => 'new-features-learning-platform',
    'content' => 'We are excited to announce...',
    'excerpt' => 'Brief description',
    'author_id' => auth()->id(),
    'category_id' => $categoryId,
    'status' => 'published',
    'published_at' => now(),
    'featured_image' => 'path/to/image.jpg',
    'is_featured' => true,
];

$blog = app(CreateBlogAction::class)->execute($blogData);

// Add tags
$blog->tags()->attach($tagIds);
```

### Blog Status

- **draft** - Work in progress
- **published** - Live on website
- **scheduled** - Scheduled for future publication
- **archived** - No longer active

### Blog Features

```php
// Featured posts
$featuredPosts = Blog::where('is_featured', true)
    ->where('status', 'published')
    ->latest('published_at')
    ->take(3)
    ->get();

// Related posts
$relatedPosts = Blog::where('category_id', $blog->category_id)
    ->where('id', '!=', $blog->id)
    ->take(5)
    ->get();

// Popular posts
$popularPosts = Blog::orderBy('views_count', 'desc')
    ->take(10)
    ->get();
```

> {info} Blog posts support scheduled publishing for content automation

---

<a name="portfolio-management"></a>
## Portfolio Management

### Creating Portfolio Items

```php
use App\Actions\PortFolio\CreatePortFolioAction;

$portfolioData = [
    'title' => 'Student Art Exhibition 2025',
    'slug' => 'student-art-exhibition-2025',
    'description' => 'Showcasing amazing student artworks',
    'category_id' => $categoryId,
    'featured_image' => 'path/to/image.jpg',
    'client' => 'Art Department',
    'project_date' => '2025-06-15',
    'status' => 'published',
];

$portfolio = app(CreatePortFolioAction::class)->execute($portfolioData);

// Add gallery images
foreach ($images as $image) {
    $portfolio->addMedia($image)->toMediaCollection('gallery');
}
```

### Portfolio Features

- **Gallery Support** - Multiple images per portfolio item
- **Client Information** - Link to clients/projects
- **Categories** - Organize by type/department
- **Tags** - Flexible categorization
- **Status Management** - Draft, published, featured

---

<a name="page-management"></a>
## Page Management

### Creating Custom Pages

```php
use App\Actions\Page\CreatePageAction;

$pageData = [
    'title' => 'About Us',
    'slug' => 'about-us',
    'content' => '<h1>About Our Institution</h1><p>...</p>',
    'template' => 'default',
    'status' => 'published',
    'is_home' => false,
    'parent_id' => null, // For nested pages
    'meta_title' => 'About Us - Kids Collage',
    'meta_description' => 'Learn about our educational institution',
];

$page = app(CreatePageAction::class)->execute($pageData);
```

### Page Templates

Available templates:
- **default** - Standard page layout
- **full-width** - Full-width content
- **landing** - Landing page layout
- **contact** - Contact page with form

### Nested Pages

```php
// Create parent page
$parent = Page::create([
    'title' => 'Programs',
    'slug' => 'programs',
    'content' => 'Overview of our programs',
]);

// Create child pages
$childPage = Page::create([
    'title' => 'Math Program',
    'slug' => 'math-program',
    'parent_id' => $parent->id,
    'content' => 'Details about math program',
]);

// Get page hierarchy
$pages = Page::whereNull('parent_id')
    ->with('children')
    ->get();
```

---

<a name="category-tag-management"></a>
## Category & Tag Management

### Categories

```php
use App\Actions\Category\CreateCategoryAction;

$categoryData = [
    'name' => 'Educational News',
    'slug' => 'educational-news',
    'description' => 'News about education',
    'parent_id' => null,
    'type' => 'blog', // blog, portfolio, course
    'is_active' => true,
];

$category = app(CreateCategoryAction::class)->execute($categoryData);
```

### Tags

```php
use App\Actions\Tag\CreateTagAction;

$tagData = [
    'name' => 'Mathematics',
    'slug' => 'mathematics',
    'type' => 'blog',
    'is_active' => true,
];

$tag = app(CreateTagAction::class)->execute($tagData);
```

### Using Categories and Tags

```php
// Assign category to blog post
$blog->update(['category_id' => $category->id]);

// Attach tags to blog post
$blog->tags()->attach([$tag1->id, $tag2->id, $tag3->id]);

// Get posts by category
$posts = Blog::where('category_id', $categoryId)->get();

// Get posts by tag
$posts = Blog::whereHas('tags', function ($query) use ($tagId) {
    $query->where('tags.id', $tagId);
})->get();
```

---

<a name="bulletin-management"></a>
## Bulletin Management

### Creating Bulletins

```php
use App\Actions\Bulletin\CreateBulletinAction;

$bulletinData = [
    'title' => 'School Closure Notice',
    'content' => 'School will be closed on Friday...',
    'type' => 'announcement', // announcement, alert, info
    'priority' => 'high', // low, medium, high, urgent
    'start_date' => now(),
    'end_date' => now()->addDays(7),
    'is_active' => true,
    'show_on_homepage' => true,
];

$bulletin = app(CreateBulletinAction::class)->execute($bulletinData);

// Target specific user types
$bulletin->targetAudiences()->attach(['student', 'parent']);
```

### Bulletin Types

- **announcement** - General announcements
- **alert** - Important alerts
- **info** - Informational messages
- **event** - Event notifications
- **emergency** - Emergency notifications

### Display Bulletins

```php
// Active bulletins for current date
$activeBulletins = Bulletin::where('is_active', true)
    ->where('start_date', '<=', now())
    ->where('end_date', '>=', now())
    ->orderBy('priority', 'desc')
    ->orderBy('created_at', 'desc')
    ->get();

// Homepage bulletins
$homepageBulletins = Bulletin::where('show_on_homepage', true)
    ->where('is_active', true)
    ->where('start_date', '<=', now())
    ->where('end_date', '>=', now())
    ->get();
```

---

<a name="seo-optimization"></a>
## SEO Optimization

### SEO Fields

All content types support SEO optimization:

```php
$content = [
    'title' => 'Main Title',
    'meta_title' => 'SEO Title - Brand Name',
    'meta_description' => 'SEO description for search engines',
    'meta_keywords' => 'keyword1, keyword2, keyword3',
    'og_title' => 'Social Media Title',
    'og_description' => 'Social media description',
    'og_image' => 'path/to/social-image.jpg',
];
```

### Automatic SEO Generation

```php
use App\Services\SeoService;

$blog = Blog::find($blogId);

// Auto-generate SEO fields
$seoData = SeoService::generateForBlog($blog);

$blog->update([
    'meta_title' => $seoData['meta_title'],
    'meta_description' => $seoData['meta_description'],
    'meta_keywords' => $seoData['meta_keywords'],
]);
```

### Sitemap Generation

```php
use App\Models\Blog;
use App\Models\Page;

public function sitemap()
{
    $blogs = Blog::where('status', 'published')->get();
    $pages = Page::where('status', 'published')->get();
    
    return response()->view('sitemap', [
        'blogs' => $blogs,
        'pages' => $pages,
    ])->header('Content-Type', 'text/xml');
}
```

---

<a name="examples"></a>
## Examples

### Example 1: Complete Blog Creation

```php
use App\Actions\Blog\CreateBlogAction;
use App\Models\Category;
use App\Models\Tag;

// Create or get category
$category = Category::firstOrCreate(
    ['slug' => 'news'],
    ['name' => 'News', 'type' => 'blog']
);

// Create or get tags
$tags = collect(['education', 'announcement', 'students'])
    ->map(function ($tagName) {
        return Tag::firstOrCreate(
            ['slug' => Str::slug($tagName)],
            ['name' => $tagName, 'type' => 'blog']
        );
    });

// Create blog post
$blog = app(CreateBlogAction::class)->execute([
    'title' => 'New Academic Year Begins',
    'slug' => 'new-academic-year-begins',
    'content' => '<p>We are excited to announce...</p>',
    'excerpt' => 'The new academic year begins next week.',
    'author_id' => auth()->id(),
    'category_id' => $category->id,
    'status' => 'published',
    'published_at' => now(),
    'meta_title' => 'New Academic Year Begins - Kids Collage',
    'meta_description' => 'Learn about the new academic year at our institution.',
]);

// Attach tags
$blog->tags()->attach($tags->pluck('id'));

// Add featured image
$blog->addMedia($request->file('image'))
    ->toMediaCollection('featured_image');
```

### Example 2: Content Scheduling

```php
use App\Models\Blog;
use Carbon\Carbon;

// Schedule blog post for future
$blog = Blog::create([
    'title' => 'Upcoming Event Announcement',
    'content' => 'Event details...',
    'status' => 'scheduled',
    'published_at' => Carbon::parse('2025-12-01 09:00:00'),
]);

// Scheduled job to publish content
// In App\Console\Kernel or job
public function publishScheduledContent()
{
    Blog::where('status', 'scheduled')
        ->where('published_at', '<=', now())
        ->update(['status' => 'published']);
}
```

### Example 3: Multi-language Content

```php
// Store translations
$blog = Blog::create([
    'title' => [
        'en' => 'Welcome to Our School',
        'fa' => 'به مدرسه ما خوش آمدید',
    ],
    'content' => [
        'en' => '<p>Welcome message in English</p>',
        'fa' => '<p>پیام خوش‌آمدگویی به فارسی</p>',
    ],
]);

// Retrieve in current language
$title = $blog->getTranslation('title', app()->getLocale());
```

### Example 4: Content Statistics

```php
use App\Models\Blog;

$stats = [
    'total_blogs' => Blog::count(),
    'published_blogs' => Blog::where('status', 'published')->count(),
    'draft_blogs' => Blog::where('status', 'draft')->count(),
    'total_views' => Blog::sum('views_count'),
    'most_viewed' => Blog::orderBy('views_count', 'desc')->take(10)->get(),
    'recent_posts' => Blog::where('status', 'published')
        ->latest('published_at')
        ->take(5)
        ->get(),
];
```

---

## Media Management

### Adding Media to Content

```php
// Single image
$blog->addMedia($request->file('image'))
    ->toMediaCollection('featured_image');

// Multiple images
foreach ($request->file('gallery') as $image) {
    $portfolio->addMedia($image)->toMediaCollection('gallery');
}

// With custom properties
$blog->addMedia($request->file('image'))
    ->withCustomProperties(['alt' => 'Image description'])
    ->toMediaCollection('featured_image');
```

### Retrieving Media

```php
// Get featured image
$featuredImage = $blog->getFirstMediaUrl('featured_image');

// Get all gallery images
$galleryImages = $portfolio->getMedia('gallery');

// With conversions
$thumbnail = $blog->getFirstMediaUrl('featured_image', 'thumb');
```

---

## Permissions

Content management requires appropriate permissions:

- `blog.index` - View blogs list
- `blog.create` - Create new blogs
- `blog.edit` - Edit blogs
- `blog.delete` - Delete blogs
- `page.manage` - Manage pages
- `category.manage` - Manage categories
- `tag.manage` - Manage tags

> {info} Content creators have access to create and edit their own content

