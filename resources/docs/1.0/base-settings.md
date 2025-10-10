# Base Settings & Management

---

* [Overview](#overview)
* [Slider Management](#slider-management)
* [FAQ Management](#faq-management)
* [Banner Management](#banner-management)
* [Client Management](#client-management)
* [Teammate Management](#teammate-management)
* [Social Media Management](#social-media-management)
* [Opinion Management](#opinion-management)
* [Examples](#examples)

---

<a name="overview"></a>
## Overview

Base Settings & Management provides tools for managing fundamental website components including sliders, FAQs, banners, team members, clients, social media links, and testimonials.

> {success} Centralized management of website components

> {primary} Easy-to-use interfaces for non-technical staff

---

<a name="slider-management"></a>
## Slider Management

### Creating Sliders

```php
use App\Actions\Slider\CreateSliderAction;

$sliderData = [
    'title' => 'Welcome to Kids Collage',
    'subtitle' => 'Quality Education for Young Minds',
    'description' => 'Join us for an amazing learning experience',
    'image' => 'path/to/slider-image.jpg',
    'button_text' => 'Learn More',
    'button_link' => '/about-us',
    'order' => 1,
    'is_active' => true,
];

$slider = app(CreateSliderAction::class)->execute($sliderData);
```

### Slider Features

- **Image/Video Support** - Display images or video backgrounds
- **Multiple CTA Buttons** - Primary and secondary buttons
- **Ordering** - Control slide sequence
- **Animation Effects** - Fade, slide, zoom effects
- **Responsive** - Mobile-optimized

### Managing Slider Order

```php
use App\Models\Slider;

// Get sliders in order
$sliders = Slider::where('is_active', true)
    ->orderBy('order')
    ->get();

// Reorder slides
$slider1->update(['order' => 1]);
$slider2->update(['order' => 2]);
$slider3->update(['order' => 3]);
```

> {info} Only active sliders are displayed on the website

---

<a name="faq-management"></a>
## FAQ Management

### Creating FAQs

```php
use App\Actions\Faq\CreateFaqAction;

$faqData = [
    'question' => 'What are the admission requirements?',
    'answer' => 'Our admission requirements include...',
    'category' => 'admissions',
    'order' => 1,
    'is_active' => true,
];

$faq = app(CreateFaqAction::class)->execute($faqData);
```

### FAQ Categories

Common categories:
- **admissions** - Enrollment and registration questions
- **courses** - Course-related questions
- **payments** - Payment and billing questions
- **technical** - Technical support questions
- **general** - General information

### Displaying FAQs

```php
use App\Models\Faq;

// Get FAQs by category
$admissionFaqs = Faq::where('category', 'admissions')
    ->where('is_active', true)
    ->orderBy('order')
    ->get();

// Search FAQs
$searchResults = Faq::where('is_active', true)
    ->where(function ($query) use ($searchTerm) {
        $query->where('question', 'like', "%{$searchTerm}%")
              ->orWhere('answer', 'like', "%{$searchTerm}%");
    })
    ->get();
```

---

<a name="banner-management"></a>
## Banner Management

### Creating Banners

```php
use App\Actions\Banner\CreateBannerAction;

$bannerData = [
    'title' => 'Summer Camp 2025',
    'description' => 'Register now for summer programs',
    'image' => 'path/to/banner.jpg',
    'link' => '/summer-camp',
    'position' => 'homepage_top',
    'start_date' => now(),
    'end_date' => now()->addMonths(2),
    'is_active' => true,
];

$banner = app(CreateBannerAction::class)->execute($bannerData);
```

### Banner Positions

Available positions:
- **homepage_top** - Top of homepage
- **homepage_sidebar** - Homepage sidebar
- **courses_page** - Courses page
- **blog_sidebar** - Blog sidebar
- **global_header** - Site-wide header

### Banner Scheduling

```php
use App\Models\Banner;

// Get active banners for current date and position
$activeBanners = Banner::where('is_active', true)
    ->where('position', 'homepage_top')
    ->where('start_date', '<=', now())
    ->where('end_date', '>=', now())
    ->orderBy('order')
    ->get();
```

---

<a name="client-management"></a>
## Client Management

### Creating Clients

```php
use App\Actions\Client\CreateClientAction;

$clientData = [
    'name' => 'Partner School Name',
    'logo' => 'path/to/logo.png',
    'website' => 'https://partnerschool.com',
    'description' => 'Long-time educational partner',
    'order' => 1,
    'is_active' => true,
];

$client = app(CreateClientAction::class)->execute($clientData);
```

### Displaying Clients

```php
use App\Models\Client;

// Get active clients
$clients = Client::where('is_active', true)
    ->orderBy('order')
    ->get();
```

---

<a name="teammate-management"></a>
## Teammate Management

### Creating Team Members

```php
use App\Actions\Teammate\CreateTeammateAction;

$teammateData = [
    'name' => 'Dr. Sara Mohammadi',
    'position' => 'Principal',
    'bio' => 'Dr. Mohammadi has over 20 years of experience...',
    'photo' => 'path/to/photo.jpg',
    'email' => 'sara@kidscollage.com',
    'phone' => '+98912345678',
    'social_links' => [
        'linkedin' => 'https://linkedin.com/in/saramohammadi',
        'twitter' => 'https://twitter.com/saramohammadi',
    ],
    'order' => 1,
    'is_active' => true,
];

$teammate = app(CreateTeammateAction::class)->execute($teammateData);
```

### Team Categories

```php
// Filter by department
$teachers = Teammate::where('position', 'like', '%Teacher%')
    ->where('is_active', true)
    ->orderBy('order')
    ->get();

$administration = Teammate::whereIn('position', ['Principal', 'Vice Principal', 'Administrator'])
    ->where('is_active', true)
    ->orderBy('order')
    ->get();
```

---

<a name="social-media-management"></a>
## Social Media Management

### Creating Social Media Links

```php
use App\Actions\SocialMedia\CreateSocialMediaAction;

$socialMediaData = [
    'platform' => 'instagram',
    'url' => 'https://instagram.com/kidscollage',
    'icon' => 'fab fa-instagram',
    'order' => 1,
    'is_active' => true,
];

$socialMedia = app(CreateSocialMediaAction::class)->execute($socialMediaData);
```

### Supported Platforms

```php
$platforms = [
    'facebook' => ['icon' => 'fab fa-facebook', 'color' => '#1877f2'],
    'instagram' => ['icon' => 'fab fa-instagram', 'color' => '#e4405f'],
    'twitter' => ['icon' => 'fab fa-twitter', 'color' => '#1da1f2'],
    'linkedin' => ['icon' => 'fab fa-linkedin', 'color' => '#0077b5'],
    'youtube' => ['icon' => 'fab fa-youtube', 'color' => '#ff0000'],
    'telegram' => ['icon' => 'fab fa-telegram', 'color' => '#0088cc'],
    'whatsapp' => ['icon' => 'fab fa-whatsapp', 'color' => '#25d366'],
];
```

### Displaying Social Media Links

```php
use App\Models\SocialMedia;

// Get active social media links
$socialLinks = SocialMedia::where('is_active', true)
    ->orderBy('order')
    ->get();
```

```blade
{{-- In Blade template --}}
<div class="social-links">
    @foreach($socialLinks as $link)
        <a href="{{ $link->url }}" target="_blank" class="social-link">
            <i class="{{ $link->icon }}"></i>
        </a>
    @endforeach
</div>
```

---

<a name="opinion-management"></a>
## Opinion Management

### Creating Testimonials

```php
use App\Actions\Opinion\CreateOpinionAction;

$opinionData = [
    'name' => 'محمد کریمی',
    'title' => 'والدین دانش‌آموز',
    'photo' => 'path/to/photo.jpg',
    'content' => 'My child has learned so much at Kids Collage...',
    'rating' => 5,
    'is_featured' => true,
    'is_active' => true,
    'order' => 1,
];

$opinion = app(CreateOpinionAction::class)->execute($opinionData);
```

### Testimonial Features

- **Rating System** - 1-5 star ratings
- **Featured Testimonials** - Highlight best testimonials
- **Photo Support** - Display customer photos
- **Verification** - Mark verified testimonials
- **Categories** - Group by course, service, etc.

### Displaying Testimonials

```php
use App\Models\Opinion;

// Get featured testimonials
$featuredTestimonials = Opinion::where('is_active', true)
    ->where('is_featured', true)
    ->orderBy('order')
    ->take(3)
    ->get();

// Get all active testimonials
$testimonials = Opinion::where('is_active', true)
    ->orderBy('order')
    ->get();

// Calculate average rating
$averageRating = Opinion::where('is_active', true)
    ->avg('rating');
```

---

<a name="examples"></a>
## Examples

### Example 1: Complete Homepage Setup

```php
use App\Actions\Slider\CreateSliderAction;
use App\Actions\Banner\CreateBannerAction;
use App\Actions\Opinion\CreateOpinionAction;

// Create hero sliders
$sliders = [
    [
        'title' => 'Welcome to Kids Collage',
        'subtitle' => 'Quality Education',
        'image' => 'slider1.jpg',
        'order' => 1,
    ],
    [
        'title' => 'Join Our Summer Camp',
        'subtitle' => 'Fun Learning Activities',
        'image' => 'slider2.jpg',
        'order' => 2,
    ],
];

foreach ($sliders as $sliderData) {
    app(CreateSliderAction::class)->execute($sliderData);
}

// Create promotional banner
$banner = app(CreateBannerAction::class)->execute([
    'title' => 'Early Bird Discount',
    'description' => '20% off registration',
    'position' => 'homepage_top',
    'is_active' => true,
]);

// Add featured testimonials
$testimonials = [
    [
        'name' => 'Ali Karimi',
        'content' => 'Best educational experience...',
        'rating' => 5,
        'is_featured' => true,
    ],
    // More testimonials...
];

foreach ($testimonials as $testimonialData) {
    app(CreateOpinionAction::class)->execute($testimonialData);
}
```

### Example 2: FAQ Search System

```php
use App\Models\Faq;

public function searchFaqs(Request $request)
{
    $searchTerm = $request->input('q');
    
    $results = Faq::where('is_active', true)
        ->where(function ($query) use ($searchTerm) {
            $query->where('question', 'like', "%{$searchTerm}%")
                  ->orWhere('answer', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%");
        })
        ->orderBy('order')
        ->get()
        ->groupBy('category');
    
    return view('faq.search-results', compact('results', 'searchTerm'));
}
```

### Example 3: Dynamic Footer Content

```php
use App\Models\SocialMedia;
use App\Models\Page;

// Get footer data
$footerData = [
    'social_links' => SocialMedia::where('is_active', true)
        ->orderBy('order')
        ->get(),
    
    'quick_links' => Page::where('show_in_footer', true)
        ->where('status', 'published')
        ->orderBy('order')
        ->get(),
    
    'contact_info' => Setting::getGroup('contact'),
];

return view('layouts.partials.footer', $footerData);
```

### Example 4: Team Directory

```php
use App\Models\Teammate;

// Organize team by department
$teamDirectory = [
    'Administration' => Teammate::where('is_active', true)
        ->whereIn('position', ['Principal', 'Vice Principal'])
        ->orderBy('order')
        ->get(),
    
    'Teachers' => Teammate::where('is_active', true)
        ->where('position', 'like', '%Teacher%')
        ->orderBy('name')
        ->get(),
    
    'Support Staff' => Teammate::where('is_active', true)
        ->whereNotIn('position', ['Principal', 'Vice Principal'])
        ->where('position', 'not like', '%Teacher%')
        ->orderBy('name')
        ->get(),
];

return view('about.team', compact('teamDirectory'));
```

---

## Widget Integration

### Homepage Widgets

```php
// Slider widget
@include('widgets.hero-slider', [
    'sliders' => $sliders
])

// Testimonials widget
@include('widgets.testimonials', [
    'testimonials' => $featuredTestimonials
])

// Partners/Clients widget
@include('widgets.partners', [
    'clients' => $clients
])

// Team widget
@include('widgets.team-preview', [
    'team' => $featuredTeam
])
```

---

## Caching Strategies

```php
use Illuminate\Support\Facades\Cache;

// Cache homepage sliders
$sliders = Cache::remember('homepage.sliders', 3600, function () {
    return Slider::where('is_active', true)
        ->orderBy('order')
        ->get();
});

// Cache FAQs by category
$faqs = Cache::remember("faqs.{$category}", 3600, function () use ($category) {
    return Faq::where('category', $category)
        ->where('is_active', true)
        ->orderBy('order')
        ->get();
});

// Clear cache when content updates
Slider::saved(function () {
    Cache::forget('homepage.sliders');
});
```

---

## Permissions

Base settings management requires appropriate permissions:

- `slider.manage` - Manage sliders
- `faq.manage` - Manage FAQs
- `banner.manage` - Manage banners
- `client.manage` - Manage clients
- `teammate.manage` - Manage team members
- `social-media.manage` - Manage social media links
- `opinion.manage` - Manage testimonials

> {info} These are typically admin-only features

