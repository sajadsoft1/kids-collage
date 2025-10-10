# 📚 Kids Collage - Documentation

Welcome to the Kids Collage project documentation!

## 🌐 Online Documentation

All project documentation is now available through **LaRecipe** documentation system.

### Access Documentation

Visit the documentation at:
```
http://your-domain.test/web/documentation
```

Or in production:
```
https://your-domain.com/web/documentation
```

## 📖 Available Documentation

### Getting Started
- **Overview** - Introduction to Kids Collage
- **Project Architecture** - Detailed architecture and component analysis

### Core Features
- **SmartCache** - Intelligent caching system
- **Content Publishing** - Scheduled content publishing
- **Error Handling** - Livewire error handling system
- **Discount System** - Discount codes and promotions
- **SMS Integration** - SMS notification system

### Modules
- **Course Management** - Complete LMS features including:
  - Course templates and instances
  - Session scheduling
  - Student enrollment
  - Attendance tracking
  - Certificate generation
  - Progress monitoring

## 🚀 Quick Start

### View Documentation Locally

1. Make sure your Laravel application is running:
   ```bash
   php artisan serve
   ```

2. Visit the documentation:
   ```
   http://localhost:8000/web/documentation
   ```

### Documentation Structure

All documentation files are located in:
```
resources/docs/1.0/
```

Files:
- `index.md` - Navigation sidebar
- `overview.md` - Project overview
- `project-architecture.md` - Architecture details
- `smart-cache.md` - SmartCache documentation
- `content-publishing.md` - Publishing system
- `error-handling.md` - Error handling
- `course-management.md` - Course management
- `discount.md` - Discount system
- `sms.md` - SMS integration

## ✏️ Editing Documentation

To edit documentation:

1. Navigate to `resources/docs/1.0/`
2. Edit the relevant `.md` file
3. Changes are reflected immediately (no build required)
4. Follow LaRecipe markdown format

### LaRecipe Format

```markdown
# Page Title

---

* [Section 1](#section-1)
* [Section 2](#section-2)

---

<a name="section-1"></a>
## Section 1

Content here...

> {success} Success message
> {warning} Warning message
> {danger} Danger message
> {info} Info message
```

## 🎨 Features

- **Syntax Highlighting** - Beautiful code blocks
- **Search** - Full-text search (configurable)
- **Responsive** - Mobile-friendly design
- **Dark/Light Mode** - Theme switcher
- **Versioning** - Multiple version support
- **Multilingual** - English and Persian support

## 📝 Adding New Documentation

1. Create a new `.md` file in `resources/docs/1.0/`
2. Add link to `index.md` navigation
3. Follow the existing format
4. Test locally before committing

Example:
```markdown
# My New Feature

---

* [Introduction](#intro)
* [Usage](#usage)

---

<a name="intro"></a>
## Introduction

Description here...
```

## 🔧 Configuration

Documentation settings in `config/larecipe.php`:

```php
'docs' => [
    'route' => 'web/documentation',
    'path' => '/resources/docs',
    'landing' => 'overview',
],

'versions' => [
    'default' => '1.0',
    'published' => ['1.0'],
],
```

## 🎯 Best Practices

1. **Keep It Updated** - Update docs when code changes
2. **Use Examples** - Include code examples
3. **Be Clear** - Write for developers of all levels
4. **Use Callouts** - Highlight important information
5. **Link Between Pages** - Create a connected documentation

## 🤝 Contributing

When adding features:

1. Update relevant documentation pages
2. Add new pages if needed
3. Update `index.md` navigation
4. Include code examples
5. Add callouts for important notes
6. Follow the documentation guidelines in `.cursor/rules/documentation.mdc`

### Cursor Rules

Documentation guidelines and best practices are defined in:
```
.cursor/rules/documentation.mdc
```

This file contains:
- LaRecipe format guidelines
- Documentation structure requirements
- When and how to write documentation
- Code example standards
- Callout usage guidelines

## 📦 LaRecipe Package

This project uses [BinaryTorch LaRecipe](https://github.com/binarytorch/larecipe) for documentation.

Features:
- ✅ Markdown-based documentation
- ✅ Syntax highlighting
- ✅ Search functionality
- ✅ Responsive design
- ✅ Version control
- ✅ SEO friendly

## 🔗 Useful Links

- [LaRecipe Documentation](https://larecipe.binarytorch.com.my/)
- [Markdown Guide](https://www.markdownguide.org/)
- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://livewire.laravel.com/docs)

---

**Happy Documenting! 📝**

