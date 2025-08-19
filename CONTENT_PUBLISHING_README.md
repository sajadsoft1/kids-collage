# Content Publishing System

This system automatically publishes content at scheduled times using Laravel's built-in scheduling and queue system.

## How It Works

1. **Scheduled Command**: Runs every minute via Laravel's scheduler
2. **Job Processing**: Uses Laravel queues for reliable publishing
3. **Model Integration**: Models use the `HasScheduledPublishing` trait for easy querying

## Setup

### 1. Cron Job Setup

Add this to your server's crontab to run Laravel's scheduler:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 2. Queue Worker

Start the queue worker to process publishing jobs:

```bash
php artisan queue:work
```

For production, use a process manager like Supervisor.

### 3. Add Trait to Models

Add the `HasScheduledPublishing` trait to any model that supports scheduled publishing:

```php
use App\Traits\HasScheduledPublishing;

class YourModel extends Model
{
    use HasScheduledPublishing;
    
    // ... rest of your model
}
```

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

### Model Scopes

The trait provides useful query scopes:

```php
// Get all published items
Model::published()->get();

// Get all unpublished items
Model::unpublished()->get();

// Get items scheduled for publishing
Model::scheduledForPublishing()->get();
```

### Model Methods

```php
$item = Model::find(1);

// Check if published
$item->isPublished();

// Check if unpublished
$item->isUnpublished();

// Check if scheduled for publishing
$item->isScheduledForPublishing();

// Get time until publishing
$item->getTimeUntilPublishing();
```

## Configuration

### Scheduling Frequency

Modify `app/Console/Kernel.php` to change how often the scheduler runs:

```php
// Run every 5 minutes instead of every minute
$schedule->command('content:publish-scheduled')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();
```

### Queue Configuration

Configure your queue driver in `.env`:

```env
QUEUE_CONNECTION=database
# or
QUEUE_CONNECTION=redis
```

## Monitoring

### Logs

The system logs all publishing activities:
- Successful publications
- Failed publications
- Scheduling activities

Check `storage/logs/laravel.log` for detailed information.

### Queue Status

Monitor queue status:

```bash
php artisan queue:monitor
php artisan queue:failed
```

## Troubleshooting

### Content Not Publishing

1. Check if cron job is running: `php artisan schedule:list`
2. Verify queue worker is running: `php artisan queue:work`
3. Check logs for errors
4. Verify `published_at` dates are in the past

### Performance Issues

1. Consider running the scheduler less frequently
2. Use database indexing on `published` and `published_at` fields
3. Monitor queue performance

## Database Indexes

For optimal performance, add these indexes to your migration files:

```php
$table->index(['published', 'published_at']);
$table->index('published');
$table->index('published_at');
```

## Security

- Only authenticated users can schedule content
- Publishing jobs are processed in the background
- Failed jobs are logged and can be retried
- No sensitive data is exposed in logs 
