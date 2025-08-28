# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

This is a modern **Laravel 12 CMS/Portfolio application** called "Kids Collage" built with a **Livewire-first approach** and **action-based architecture**. The application serves both as a content management system and a public-facing portfolio website with multilingual support (English/Farsi).

## Core Architecture

### Action-Based Pattern
- Each CRUD operation is encapsulated in a single-action class located in `app/Actions/`
- Actions follow the pattern: `Store{Model}Action`, `Update{Model}Action`, `Delete{Model}Action`
- Actions handle validation, database transactions, and file operations
- Use Laravel Actions package for clean, testable business logic

### Livewire-First Approach  
- Both admin panel and public website built with Livewire 3
- Components located in `app/Livewire/` with Admin and Web subdirectories
- Real-time updates without page refreshes
- Component-based architecture for reusability

### Model Structure
- Models use `HasTranslationAuto` trait for automatic translation handling
- Translation data stored in `languages` JSON field
- Models cast `published` to `BooleanEnum` for status management
- Extensive use of Spatie packages for media, permissions, and activity logging

## Technology Stack

### Backend
- **Laravel 12** (PHP 8.2+) - Core framework
- **Livewire 3** - Full-stack reactive components  
- **Laravel Actions** - Single-action classes for business logic
- **Mary UI** - Component library for Livewire
- **PowerGrid** - Advanced data tables
- **Spatie Media Library** - Advanced file handling with conversions
- **Spatie Permissions** - Role-based access control
- **Spatie Activity Log** - Audit trails
- **Spatie Tags** - Tagging system

### Frontend
- **Tailwind CSS 4** - Utility-first CSS framework
- **DaisyUI** - Component library for Tailwind  
- **Alpine.js** - Lightweight JavaScript framework
- **Vite 6** - Build tool and dev server

### Special Features
- **Multilingual Support** with Persian date handling (Jalali calendar)
- **SEO Tools** with comprehensive meta management
- **Media Management** with automatic image conversions
- **Role-based Permissions** with granular access control

## Common Development Commands

### Development Server
```bash
# Start all development services (server, queue, vite)
composer dev

# Individual services
php artisan serve              # Laravel development server
php artisan queue:listen       # Queue worker
npm run dev                   # Vite development server
```

### Asset Management
```bash
npm run build                 # Build production assets
npm run dev                   # Development build with watch
```

### Database Operations
```bash
php artisan migrate           # Run migrations
php artisan migrate:fresh --seed  # Fresh migration with seeding
php artisan db:seed           # Run seeders
```

### Code Quality
```bash
composer pint                 # Run Laravel Pint (code formatting)
composer pest                 # Run Pest tests (if available)
```

### Artisan Commands
```bash
php artisan key:generate      # Generate application key
php artisan storage:link      # Create storage symlink
php artisan optimize:clear    # Clear all caches
php artisan config:cache      # Cache configuration
php artisan route:cache       # Cache routes
```

### Queue Management
```bash
php artisan queue:work        # Process queue jobs
php artisan queue:failed      # List failed jobs
php artisan queue:retry all   # Retry failed jobs
```

## Project Structure

### Core Directories
```
app/
├── Actions/          # Single-action classes (CRUD operations)
│   ├── Banner/       # Banner management actions
│   ├── Blog/         # Blog content actions  
│   ├── Category/     # Category management
│   ├── Client/       # Client management
│   └── ...
├── Livewire/         # Reactive components
│   ├── Admin/        # Admin panel components
│   └── Web/          # Public website components
├── Models/           # Eloquent models with traits
├── Services/         # Business logic services
├── Enums/           # Type-safe enumerations
├── Traits/          # Reusable model traits
└── Policies/        # Authorization policies
```

### Key Features Areas
- **Content Management**: Blog, Portfolio, Pages, Categories, Tags
- **Media Management**: Banners, Sliders, Images with automatic conversions
- **User Management**: Users, Roles, Permissions  
- **System Settings**: SEO, General settings, Social media
- **Support System**: Tickets, Comments, FAQ management

## Development Patterns

### Model Conventions
- Use `HasTranslationAuto` trait for multilingual content
- Define `$translatable` array for translatable fields  
- Cast `published` to `BooleanEnum`
- Cast `languages` to `array`
- Use `InteractsWithMedia` trait when needed

### Action Pattern
- Create actions for each CRUD operation
- Handle validation within actions
- Use database transactions for data integrity
- Include proper error handling and logging

### Livewire Components  
- Follow component-based architecture
- Use real-time validation and updates
- Implement proper loading states
- Handle file uploads appropriately

### Translation Support
- Use `languages` JSON field for storing translations
- Support English and Farsi languages
- Handle RTL layout for Persian content
- Use Jalali calendar for Persian dates

## Configuration Files
- **Vite**: `vite.config.js` - Asset building configuration
- **Tailwind**: `tailwind.config.js` - CSS framework configuration  
- **Pint**: `pint.json` - Code formatting rules
- **PHP**: `phpunit.xml` - Testing configuration

## Environment Setup
1. Copy `.env.example` to `.env`
2. Configure database settings
3. Run `php artisan key:generate`
4. Install dependencies: `composer install` and `npm install`
5. Run migrations: `php artisan migrate`
6. Start development server: `composer dev`

## Important Development Rules
- Follow Laravel Actions pattern for business logic
- Use Livewire components for interactive features
- Implement proper translation support for content
- Utilize Spatie packages for media and permissions
- Follow existing architectural patterns and conventions
- Ensure proper error handling and logging in actions
- Use enum-based configuration for type safety
