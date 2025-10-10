# Content Publishing System

---

* [Overview](#overview)
* [How It Works](#how-it-works)
* [Setup](#setup)
* [Usage](#usage)
* [Model Scopes](#scopes)
* [Configuration](#configuration)
* [Monitoring](#monitoring)
* [Troubleshooting](#troubleshooting)

---

<a name="overview"></a>
## Overview

This system automatically publishes content at scheduled times using Laravel's built-in scheduling and queue system.

> {success} Automatic publishing when `published` is `false` and `published_at` is in the past

> {primary} Uses Laravel queues for reliable background processing

> {info} Integrated with models via the `HasScheduledPublishing` trait

---

<a name="how-it-works"></a>
## How It Works

The publishing system consists of three main components:

### 1. Scheduled Command
Runs every minute via Laravel's scheduler to check for content ready to publish.

### 2. Job Processing
Uses Laravel queues for reliable and asynchronous publishing operations.

### 3. Model Integration
Models use the `HasScheduledPublishing` trait for easy querying and automatic publishing.

---

<a name="setup"></a>
## Setup

### Step 1: Cron Job Setup

Add this to your server's crontab to run Laravel's scheduler:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

> {warning} Without this cron job, scheduled publishing will not work!

### Step 2: Queue Worker

Start the queue worker to process publishing jobs:

```bash
php artisan queue:work
```

For production environments, use a process manager like **Supervisor**:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path-to-your-project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=1
user=www-data
redirect_stderr=true
stdout_logfile=/path-to-your-project/storage/logs/worker.log
```

### Step 3: Add Trait to Models

Add the `HasScheduledPublishing` trait to any model that supports scheduled publishing:

```php
use App\Traits\HasScheduledPublishing;

class Blog extends Model
{
    use HasScheduledPublishing;
    
    // ... rest of your model
}
```

---

<a name="usage"></a>
## Usage

### Automatic Publishing

Content is automatically published when:
- `published` field is `false`
- `published_at` field contains a past date/time

### Manual Commands

#### Publish All Scheduled Content
```bash
php artisan content:publish-now
```

#### Publish Specific Model
```bash
php artisan content:publish-now --model="App\Models\Blog"
```

#### Check Publishing Status
```bash
php artisan content:publish-scheduled
```

This will show you all content scheduled for publishing.

---

<a name="scopes"></a>
## Model Scopes

The `HasScheduledPublishing` trait provides useful query scopes:

### Published Items
```php
// Get all published items
$publishedBlogs = Blog::published()->get();
```

### Unpublished Items
```php
// Get all unpublished items
$draftBlogs = Blog::unpublished()->get();
```

### Scheduled for Publishing
```php
// Get items scheduled for future publishing
$scheduledBlogs = Blog::scheduledForPublishing()->get();
```

### Model Methods

```php
$blog = Blog::find(1);

// Check if published
if ($blog->isPublished()) {
    // ...
}

// Check if unpublished
if ($blog->isUnpublished()) {
    // ...
}

// Check if scheduled for publishing
if ($blog->isScheduledForPublishing()) {
    // ...
}

// Get time until publishing
$timeUntil = $blog->getTimeUntilPublishing();
// Returns: "2 hours" or "30 minutes"
```

---

<a name="configuration"></a>
## Configuration

### Scheduling Frequency

Modify `bootstrap/app.php` to change how often the scheduler runs:

```php
// Default: Run every minute
$schedule->command('content:publish-scheduled')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

// Run every 5 minutes instead
$schedule->command('content:publish-scheduled')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();
```

### Queue Configuration

Configure your queue driver in `.env`:

```env
# Use database queue (default)
QUEUE_CONNECTION=database

# Or use Redis for better performance
QUEUE_CONNECTION=redis
```

### Supported Models

The following models support scheduled publishing:
- Blog
- Page
- Bulletin
- Portfolio
- Opinion

---

<a name="monitoring"></a>
## Monitoring

### Logs

The system logs all publishing activities in `storage/logs/laravel.log`:

- ✅ Successful publications
- ❌ Failed publications
- 📅 Scheduling activities

Example log entries:
```
[2025-01-10 10:30:00] Content publishing schedule completed successfully
[2025-01-10 10:30:05] Published Blog #123: "My New Post"
```

### Queue Status

Monitor queue status with these commands:

```bash
# Monitor queue in real-time
php artisan queue:monitor

# View failed jobs
php artisan queue:failed

# Retry failed job
php artisan queue:retry {id}

# Retry all failed jobs
php artisan queue:retry all
```

### Check Scheduled Tasks

View all scheduled tasks:

```bash
php artisan schedule:list
```

---

<a name="troubleshooting"></a>
## Troubleshooting

### Content Not Publishing

> {danger} **Issue:** Content scheduled for publishing is not being published automatically

**Solutions:**

1. **Check if cron job is running**
   ```bash
   php artisan schedule:list
   php artisan schedule:test
   ```

2. **Verify queue worker is running**
   ```bash
   php artisan queue:work
   ```

3. **Check logs for errors**
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Verify `published_at` dates**
   - Make sure dates are in the past
   - Check timezone configuration in `config/app.php`

### Performance Issues

> {warning} **Issue:** Publishing is slow or causes timeouts

**Solutions:**

1. **Run scheduler less frequently**
   - Change from `everyMinute()` to `everyFiveMinutes()`

2. **Add database indexing**
   ```php
   $table->index(['published', 'published_at']);
   $table->index('published');
   $table->index('published_at');
   ```

3. **Use Redis for queues**
   ```env
   QUEUE_CONNECTION=redis
   ```

4. **Monitor queue performance**
   ```bash
   php artisan queue:monitor
   ```

### Database Indexes

For optimal performance, add these indexes to your migration files:

```php
Schema::table('blogs', function (Blueprint $table) {
    $table->index(['published', 'published_at']);
    $table->index('published');
    $table->index('published_at');
});
```

---

## Security

> {success} Only authenticated users can schedule content

> {primary} Publishing jobs are processed in the background

> {info} Failed jobs are logged and can be retried

> {success} No sensitive data is exposed in logs

### Best Practices

1. Always use queue workers with process managers (Supervisor)
2. Monitor failed jobs regularly
3. Set up proper logging and alerts
4. Test scheduling in staging before production
5. Use Redis for production queues

