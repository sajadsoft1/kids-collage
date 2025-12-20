# Metronic 4 Layout Documentation

## نمای کلی (Overview)

این پروژه شامل یک layout کامل بر اساس Metronic v9 Demo 4 است که با استفاده از Laravel 12، Livewire v3، Tailwind CSS v4، Alpine.js و DaisyUI ساخته شده است.

## ساختار فایل‌ها (File Structure)

```
resources/views/
├── components/
│   ├── layouts/
│   │   └── metronic-4.blade.php          # Main layout file
│   ├── metronic-4-header.blade.php       # Mobile header component
│   ├── metronic-4-sidebar.blade.php      # Sidebar component (two-column)
│   └── metronic-4-footer.blade.php       # Footer component
├── livewire/
│   └── network/
│       └── get-started.blade.php         # Sample page component
└── app/
    └── Livewire/
        └── Network/
            └── GetStarted.php            # Livewire component class

resources/css/
└── app.css                                # Custom Metronic 4 styles added
```

## ویژگی‌های اصلی (Key Features)

### ✅ 1. ساختار Layout دو ستونه (Two-Column Sidebar Layout)
- **Sidebar Primary**: نوار عمودی 70px با آیکون‌ها
- **Sidebar Secondary**: منوی اصلی 220px با آیتم‌های قابل گسترش

### ✅ 2. Responsive Design
- **Desktop (≥1024px)**: نمایش کامل sidebar
- **Mobile (<1024px)**: 
  - Header موبایل با دکمه toggle
  - Sidebar با انیمیشن slide
  - Backdrop برای بستن sidebar

### ✅ 3. حالت تاریک/روشن (Dark/Light Mode)
- دکمه toggle در sidebar footer
- ذخیره تنظیمات در localStorage
- پشتیبانی کامل از dark mode با Tailwind CSS

### ✅ 4. پشتیبانی RTL/LTR
- دکمه toggle جهت در sidebar footer
- تبدیل خودکار padding و margin
- ذخیره تنظیمات در localStorage

### ✅ 5. بدون اسکرول افقی (No Horizontal Overflow)
- بهینه‌سازی برای موبایل
- جداول با scroll افقی داخلی
- Container های responsive

## نحوه استفاده (Usage)

### 1. ایجاد صفحه جدید با Livewire

```bash
php artisan make:livewire YourPage/ComponentName --pest
```

### 2. استفاده از Layout در Blade

```blade
<x-layouts.metronic-4>
    
    {{-- Page Title --}}
    <x-slot name="pageTitle">
        صفحه من
    </x-slot>
    
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <a href="{{ route('admin.dashboard') }}">خانه</a>
        <span class="text-gray-400">/</span>
        <span>صفحه من</span>
    </x-slot>
    
    {{-- Toolbar Actions --}}
    <x-slot name="toolbar">
        <button class="btn">افزودن</button>
    </x-slot>
    
    {{-- Main Content --}}
    <div>
        محتوای صفحه شما
    </div>
    
</x-layouts.metronic-4>
```

### 3. تعریف Route

```php
// routes/admin.php
use App\Livewire\YourPage\ComponentName;

Route::get('admin/your-path', ComponentName::class)->name('admin.your.route');
```

## کامپوننت‌ها (Components)

### Metronic 4 Header
- فقط در موبایل نمایش داده می‌شود
- شامل لوگو و دکمه toggle sidebar
- در دسکتاپ مخفی است

### Metronic 4 Sidebar
- **Primary Sidebar**: 
  - آیکون‌های navigation اصلی
  - دکمه‌های Settings و Messages
  - دکمه Toggle Theme
  - دکمه Toggle Direction
  - Avatar کاربر
  
- **Secondary Sidebar**:
  - منوهای تو در تو با Alpine.js accordion
  - هایلایت خودکار مسیر فعال
  - انیمیشن باز/بسته شدن submenus

### Metronic 4 Footer
- Copyright و سال
- لینک‌های footer
- Responsive در موبایل

## استایل‌های سفارشی (Custom Styles)

فایل `resources/css/app.css` شامل استایل‌های سفارشی Metronic 4 است:

- CSS Variables برای رنگ‌ها و اندازه‌ها
- Scrollbar styling
- Button utilities
- Menu link styles
- RTL support utilities
- Alpine collapse directive

## Alpine.js Features

### Theme Toggle
```javascript
toggleTheme() {
    this.themeMode = this.themeMode === 'light' ? 'dark' : 'light';
    localStorage.setItem('theme', this.themeMode);
    document.documentElement.classList.remove('light', 'dark');
    document.documentElement.classList.add(this.themeMode);
}
```

### Direction Toggle
```javascript
toggleDirection() {
    this.direction = this.direction === 'ltr' ? 'rtl' : 'ltr';
    document.documentElement.setAttribute('dir', this.direction);
    localStorage.setItem('direction', this.direction);
}
```

### Mobile Sidebar Toggle
```javascript
@toggle-sidebar.window="mobileOpen = !mobileOpen"
```

## تست (Testing)

### URL تست
```
http://kids-collage.test/admin/metronic-test
```

### سناریوهای تست شده
- ✅ نمایش در Desktop (1920x1080)
- ✅ نمایش در Mobile (375x667)
- ✅ Toggle sidebar در موبایل
- ✅ Accordion menus
- ✅ بدون scroll افقی
- ✅ Responsive cards و tables
- ✅ Dark mode toggle
- ✅ RTL/LTR toggle

## نکات مهم (Important Notes)

### 1. Routes
مطمئن شوید route `admin.dashboard` تعریف شده است.

### 2. Assets
بعد از هر تغییر در CSS یا JS، assets را rebuild کنید:
```bash
npm run build
```

### 3. Images
لوگوهای زیر در مسیر `public/assets/media/app/` باید وجود داشته باشند:
- `mini-logo-gray.svg`
- `mini-logo-gray-dark.svg`

### 4. Authentication
در sidebar از `auth()->user()` استفاده شده. مطمئن شوید middleware authentication فعال است.

## Component Libraries

### استفاده شده:
- **Tailwind CSS v4**: برای تمام styling
- **DaisyUI**: برای theme management
- **Alpine.js**: برای interactivity
- **Livewire v3**: برای reactivity

### استفاده نشده:
- Mary UI: تصمیم گرفته شد از pure Tailwind استفاده شود

## Browser Support
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)

## مشکلات شناخته شده (Known Issues)

1. **Sidebar در موبایل**: در برخی تبلت‌ها ممکن است نیاز به تنظیم breakpoint باشد
2. **Logo**: اگر فایل‌های logo وجود نداشته باشند، 404 error نمایش می‌دهد

## بهبودهای آینده (Future Improvements)

- [ ] افزودن notification dropdown
- [ ] افزودن user profile dropdown
- [ ] افزودن search modal
- [ ] بهینه‌سازی بیشتر برای تبلت (768px-1023px)
- [ ] افزودن breadcrumb component پیشرفته
- [ ] افزودن page loader

## مجوز (License)

این layout بر اساس Metronic Template است. برای استفاده commercial، مجوز Metronic لازم است.

---

**تاریخ ایجاد**: 2025-12-20  
**نسخه**: 1.0.0  
**سازگار با**: Laravel 12, Livewire 3, Tailwind 4

