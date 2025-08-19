# Project architecture and component analysis
_Exported on 8/6/2025 at 10:52:07 GMT+3:30 from Cursor (1.3.5)_

---

**User**

Please analyze the entire project and give me a high-level summary. Focus on:
- The overall architecture and main components
- Key technologies, libraries, and frameworks used
- Folder and file structure
- Main responsibilities of each module
- Any special patterns (e.g., MVC, services, repositories)
Summarize your findings clearly and concisely.

---

**Cursor**

I'll analyze the entire project structure to provide you with a comprehensive high-level summary. Let me start by examining the key files and directories.


Based on my comprehensive analysis of the project structure, here's a high-level summary of the **Karno** Laravel application:

## 🏗️ **Overall Architecture & Main Components**

This is a **modern Laravel 12 CMS/Portfolio application** built with a **Livewire-first approach** and follows a **modular, action-based architecture**. The application serves both as a content management system and a public-facing portfolio website.

## 🛠️ **Key Technologies & Libraries**

### **Backend Stack:**
- **Laravel 12** (PHP 8.2+) - Core framework
- **Livewire 3** - Full-stack reactive components
- **Laravel Actions** - Single-action classes for business logic
- **Spatie Packages** - Media library, permissions, activity logging, tags, sitemap
- **PowerGrid** - Advanced data tables
- **Mary UI** - Component library for Livewire

### **Frontend Stack:**
- **Tailwind CSS 4** - Utility-first CSS framework
- **DaisyUI** - Component library for Tailwind
- **Alpine.js** - Lightweight JavaScript framework
- **Vite** - Build tool and dev server

### **Special Features:**
- **Multilingual Support** (English/Farsi) with Persian date handling
- **SEO Tools** - Comprehensive SEO management
- **Media Management** - Advanced file handling with conversions
- **Role-based Permissions** - Granular access control

## 📁 **Folder & File Structure**

### **Core Architecture:**
```
app/
├── Actions/          # Single-action classes (CRUD operations)
├── Livewire/         # Reactive components (Admin & Web)
├── Models/           # Eloquent models with traits
├── Services/         # Business logic services
├── Enums/           # Type-safe enumerations
├── Traits/          # Reusable model traits
└── Policies/        # Authorization policies
```

### **Key Patterns:**
- **Action Pattern**: Each CRUD operation is a single-action class
- **Livewire Components**: Both admin panel and public website use Livewire
- **Service Layer**: Business logic separated into service classes
- **Enum-based Configuration**: Type-safe configuration using PHP 8.1+ enums

## 🎯 **Main Responsibilities**

### **Admin Panel (`/admin`):**
- **Content Management**: Blog, Portfolio, Pages, Categories, Tags
- **Media Management**: Banners, Sliders, Images with automatic conversions
- **User Management**: Users, Roles, Permissions
- **System Settings**: SEO, General settings, Social media
- **Support System**: Tickets, Comments, FAQ management

### **Public Website (`/`):**
- **Portfolio Showcase**: Project displays with filtering
- **Blog System**: Article publishing with categories and tags
- **Service Pages**: SEO, Web development, Application services
- **Contact & Support**: Contact forms, FAQ, Opinion system

## �� **Special Patterns & Architecture**

### **1. Action-Based Architecture**
- Each CRUD operation is encapsulated in a single-action class
- Uses `Laravel Actions` package for clean, testable business logic
- Actions handle validation, database transactions, and file operations

### **2. Livewire-First Approach**
- Both admin and public interfaces built with Livewire
- Real-time updates without page refreshes
- Component-based architecture for reusability

### **3. Multilingual Support**
- Built-in English/Farsi language support
- Persian date handling with Jalali calendar
- Route-based language switching

### **4. Advanced Media Management**
- Spatie Media Library integration
- Automatic image conversions (multiple resolutions)
- File organization and optimization

### **5. SEO-First Design**
- Comprehensive SEO management system
- Dynamic meta tags, Open Graph, JSON-LD
- Sitemap generation for multiple content types

### **6. Role-Based Security**
- Granular permissions system
- Policy-based authorization
- Activity logging for audit trails

## 🎨 **UI/UX Framework**
- **DaisyUI** components for consistent design
- **Tailwind CSS** for custom styling
- **Responsive design** with mobile-first approach
- **Dark/Light theme** support
- **Persian RTL** layout support

This is a **production-ready, enterprise-grade CMS** that demonstrates modern Laravel development practices with a focus on maintainability, scalability, and user experience.

```plaintext
app/
├── Actions/          # Single-action classes (CRUD operations)
├── Livewire/         # Reactive components (Admin & Web)
├── Models/           # Eloquent models with traits
├── Services/         # Business logic services
├── Enums/           # Type-safe enumerations
├── Traits/          # Reusable model traits
└── Policies/        # Authorization policies
```

