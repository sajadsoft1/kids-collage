# Karnoweb Laravel Notification

Multi-channel notification business layer for Laravel: configurable channels per event, user and global overrides, templates, logging and queuing.

## Installation

```bash
composer require karnoweb/laravel-notification
```

### Publish config (optional)

```bash
php artisan vendor:publish --tag=karnoweb-notification-config
```

Migrations run automatically from the package; run `php artisan migrate` to create package tables (with prefix).

## Configuration

In `config/karnoweb-notification.php`:

- **table_prefix**: Prefix for package tables (default: `karnoweb_`). Tables: `{prefix}notification_logs`, `{prefix}notification_templates`, `{prefix}notification_preferences`.
- **channels**: Default and per-event channel config (`enabled`, `togglable`).
- **queued_channels**: Channels that are sent via queue (e.g. `['email', 'sms']`).
- **queue_jobs**: Job class per channel for queued sending.
- **log_model**: Model used for notification logs (default: package model).
- **mailable**: Mailable class for email channel (receives payload array).
- **template_model**: Model for notification templates (default: package `NotificationTemplate`).

## Usage

### Sending a notification

Build a `NotificationMessage` with event (string), context and per-channel payloads, then dispatch:

```php
use Karnoweb\LaravelNotification\NotificationChannelEnum;
use Karnoweb\LaravelNotification\NotificationDispatcher;
use Karnoweb\LaravelNotification\Messages\NotificationMessage;

$message = NotificationMessage::make('auth_register')
    ->withContext(['user_name' => $user->name, 'action_url' => url('/login')])
    ->withChannel(NotificationChannelEnum::DATABASE, ['title' => '...', 'body' => '...'])
    ->withChannel(NotificationChannelEnum::EMAIL, ['subject' => '...', 'body' => '...']);

app(NotificationDispatcher::class)->dispatch($user, $message, $profile, [
    'notification_class' => RegisterNotification::class,
]);
```

### Custom drivers

Implement `Karnoweb\LaravelNotification\Contracts\NotificationChannelDriver` and register in your app service provider:

```php
$this->app->singleton(
    \Karnoweb\LaravelNotification\NotificationChannelEnum::SMS->driverBinding(),
    \App\Notifications\Drivers\SmsChannelDriver::class
);
```

### User and global overrides

Implement and bind:

- `Karnoweb\LaravelNotification\Contracts\GlobalChannelOverridesResolver` (admin settings per event).
- `Karnoweb\LaravelNotification\Contracts\UserChannelOverridesResolver` (user/profile settings per event).

Then `NotificationPreferenceResolver` will use them when resolving enabled channels.

### Queued jobs

Extend `Karnoweb\LaravelNotification\Jobs\SendChannelNotificationJob` and define `channel()`:

```php
namespace App\Jobs\Notifications;

use Karnoweb\LaravelNotification\Jobs\SendChannelNotificationJob;
use Karnoweb\LaravelNotification\NotificationChannelEnum;

class SendEmailNotificationJob extends SendChannelNotificationJob
{
    protected function channel(): NotificationChannelEnum
    {
        return NotificationChannelEnum::EMAIL;
    }
}
```

Set `queue_jobs.email` (and `queue_jobs.sms`) in config to your job classes.

### Direct send (no Notification class)

Send from anywhere without defining a notification class:

```php
use Karnoweb\LaravelNotification\NotificationService;

$service = app(NotificationService::class);

// Synchronous: builds message and dispatches (email/sms still queued by channel)
$service->sendDirect($user, 'order_ready', [
    'database' => ['title' => 'Order ready', 'body' => 'Your order #123 is ready.'],
    'email' => ['subject' => 'Order ready', 'body' => '...'],
], $profile, ['notification_class' => null]);

// Entire send queued (dispatcher runs in a job)
$service->queueSendDirect($user, 'order_ready', [
    'database' => ['title' => 'Order ready', 'body' => '...'],
], $profile);
```

### User preferences (table)

User channel preferences are stored in `{prefix}notification_preferences`. The package provides `DatabaseUserChannelOverridesResolver` by default. Use `NotificationPreference::setChannels($notifiable, $event, $channels)` and `NotificationPreference::getAllForNotifiable($notifiable)` to read/write from your app.

## Repository

- **Git**: `git@github.com:karnoweb/laravel-notification.git`
- **Namespace**: `Karnoweb\LaravelNotification`

### Push to GitHub

From the package directory:

```bash
cd packages/karnoweb/laravel-notification
git init
git remote add origin git@github.com:karnoweb/laravel-notification.git
git add .
git commit -m "Initial package"
git branch -M main
git push -u origin main
```

## License

MIT
