# SMS Manager

- [Introduction](#introduction)
- [Configuration](#configuration)
- [Basic Usage](#basic-usage)
- [Advanced Usage](#advanced-usage)
- [Adding New Drivers](#adding-new-drivers)
- [Database Schema](#database-schema)
- [API Reference](#api-reference)

<a name="introduction"></a>
## Introduction

The SMS Manager provides a fluent, builder-style interface for sending SMS messages with automatic failover across multiple providers (Kavenegar, SmsIR, MelliPayamak). It supports templating, OTP codes, group messaging, and delivery tracking.

**Key Features:**
- Fluent API for readable code
- Multi-provider failover
- Template support with placeholder replacement
- OTP code handling
- User and phone number targeting
- Delivery status tracking
- Database persistence for audit trails

<a name="configuration"></a>
## Configuration

### Environment Variables

Add these variables to your `.env` file:

```env
# Default driver and failover order
SMS_DEFAULT=kavenegar
SMS_FAILOVER=smsir,mellipayamak

# Kavenegar Configuration
KAVENEGAR_TOKEN=your_kavenegar_token_here
KAVENEGAR_SENDER=3000xxxx

# SmsIR Configuration
SMSIR_API_KEY=your_smsir_api_key
SMSIR_SECRET_KEY=your_smsir_secret_key
SMSIR_SENDER=3000yyyy

# MelliPayamak Configuration
MELLIPAYAMAK_USERNAME=your_username
MELLIPAYAMAK_PASSWORD=your_password
MELLIPAYAMAK_SENDER=3000zzzz
```

### Configuration File

The SMS configuration is located at `config/sms.php`:

```php
return [
    'default'  => env('SMS_DEFAULT', 'kavenegar'),
    'failover' => array_filter(explode(',', (string) env('SMS_FAILOVER', 'smsir,mellipayamak'))),

    'drivers'  => [
        'kavenegar'    => [
            'class'       => App\Services\Sms\Drivers\KavenegarDriver::class,
            'credentials' => [
                'token'  => env('KAVENEGAR_TOKEN'),
                'sender' => env('KAVENEGAR_SENDER'),
            ],
        ],
        // ... other drivers
    ],
];
```

<a name="basic-usage"></a>
## Basic Usage

### Simple Text Message

Send a plain text message to one or multiple recipients:

```php
use App\Services\Sms\SmsManager;

// Single recipient
SmsManager::instance()
    ->message('Hello, this is a test message!')
    ->number('09120000000')
    ->send();

// Multiple recipients
SmsManager::instance()
    ->message('Hello everyone!')
    ->numbers(['09120000000', '09123334444', '09125556666'])
    ->send();
```

### Sending to Users

Send messages to users by their database IDs:

```php
// Single user
SmsManager::instance()
    ->message('Welcome to our system!')
    ->user(1)
    ->send();

// Multiple users
SmsManager::instance()
    ->message('System maintenance scheduled for tonight.')
    ->users([1, 2, 3, 4, 5])
    ->send();
```

### OTP Codes

Send one-time password codes using predefined templates:

```php
use App\Enums\SmsTemplateEnum;

SmsManager::instance()
    ->otp(SmsTemplateEnum::LOGIN_OTP)
    ->input('code', '123456')
    ->input('expires', '5 minutes')
    ->number('09120000000')
    ->send();
```

### Template Messages

Use `NotificationTemplate` models for complex messages:

```php
use App\Models\NotificationTemplate;

// By model instance
$template = NotificationTemplate::find(1);

SmsManager::instance()
    ->template($template)
    ->input('user_name', 'Ali')
    ->input('order_id', '12345')
    ->numbers(['09120000000', '09123334444'])
    ->send();

// By template ID
SmsManager::instance()
    ->templateId(1)
    ->inputs([
        'user_name' => 'Ali',
        'price'     => '120,000 Toman',
        'url'       => 'https://example.com/order/12345',
    ])
    ->user($userId)
    ->send();
```

<a name="advanced-usage"></a>
## Advanced Usage

### Checking Delivery Status

Query delivery reports from the provider:

```php
$results = SmsManager::instance()
    ->number('09120000000')
    ->checkStatus();

// Returns:
// [
//     [
//         'phone' => '09120000000',
//         'provider_message_id' => 'abc123',
//         'status' => 'delivered',
//     ],
//     // ... more results
// ]
```

### Chaining Multiple Recipients

Combine different recipient types:

```php
SmsManager::instance()
    ->message('Important announcement!')
    ->number('09120000000')
    ->numbers(['09123334444', '09125556666'])
    ->user(1)
    ->users([2, 3, 4])
    ->send();
```

### Custom Template Placeholders

Templates support `{placeholder}` syntax:

```php
// In NotificationTemplate:
// message_template: "Hello {name}, your order #{order_id} totaling {price} is ready!"

SmsManager::instance()
    ->template($template)
    ->inputs([
        'name'     => 'Ahmad',
        'order_id' => '54321',
        'price'    => '250,000 Toman',
    ])
    ->number('09120000000')
    ->send();
```

<a name="adding-new-drivers"></a>
## Adding New Drivers

Follow these steps to integrate a new SMS provider:

### 1. Create Driver Class

Create a new driver in `app/Services/Sms/Drivers/`:

```php
<?php

declare(strict_types=1);

namespace App\Services\Sms\Drivers;

use App\Services\Sms\Contracts\AbstractSmsDriver;
use App\Services\Sms\Contracts\DeliveryReportFetcher;
use App\Services\Sms\Contracts\PingableSmsDriver;
use App\Services\Sms\Contracts\SmsDriver;
use App\Services\Sms\Exceptions\DriverConnectionException;

class YourProviderDriver extends AbstractSmsDriver implements DeliveryReportFetcher, PingableSmsDriver, SmsDriver
{
    /** @var array{api_key?:string,api_secret?:string,sender?:string} */
    public array $config;

    public function send(string $phoneNumber, string $message): void
    {
        if (empty($this->config['api_key'])) {
            throw new DriverConnectionException('YourProvider API key not set.');
        }

        // Integrate your provider's SDK here
        // Example:
        // $client = new YourProviderClient($this->config['api_key']);
        // $client->sendSms($phoneNumber, $message, $this->config['sender']);
    }

    public function sendTo(string $phoneNumber, string $message): void
    {
        $this->send($phoneNumber, $message);
    }

    public function sendToGroup(array $phoneNumbers, string $message): void
    {
        foreach ($phoneNumbers as $number) {
            $this->send((string) $number, $message);
        }
    }

    public function sendTemplate(string $phoneNumber, string $template, array $inputs = []): void
    {
        $message = $this->compileTemplate($template, $inputs);
        $this->send($phoneNumber, $message);
    }

    public function sendTemplateToGroup(array $phoneNumbers, string $template, array $inputs = []): void
    {
        $message = $this->compileTemplate($template, $inputs);
        $this->sendToGroup($phoneNumbers, $message);
    }

    public function ping(): bool
    {
        return !empty($this->config['api_key']);
        
        // Or implement actual connectivity check:
        // try {
        //     $client = new YourProviderClient($this->config['api_key']);
        //     return $client->checkConnection();
        // } catch (\Exception $e) {
        //     return false;
        // }
    }

    public function fetchDeliveryReport(string $providerMessageId): array
    {
        // Integrate delivery report API
        // Example:
        // $client = new YourProviderClient($this->config['api_key']);
        // $report = $client->getDeliveryStatus($providerMessageId);
        
        return [
            'status'       => 'delivered', // or 'pending', 'failed'
            'delivered_at' => now()->toIso8601String(),
            'raw'          => null, // raw provider response
        ];
    }
}
```

### 2. Register Driver in Configuration

Add your driver to `config/sms.php`:

```php
'drivers' => [
    // ... existing drivers

    'yourprovider' => [
        'class'       => App\Services\Sms\Drivers\YourProviderDriver::class,
        'credentials' => [
            'api_key'    => env('YOURPROVIDER_API_KEY'),
            'api_secret' => env('YOURPROVIDER_API_SECRET'),
            'sender'     => env('YOURPROVIDER_SENDER'),
        ],
    ],
],
```

### 3. Add Environment Variables

Update your `.env` file:

```env
YOURPROVIDER_API_KEY=your_api_key
YOURPROVIDER_API_SECRET=your_api_secret
YOURPROVIDER_SENDER=your_sender_number
```

### 4. Update Usage Handler (Optional)

If your driver requires specific credentials validation, update `app/Services/Sms/Usage/SmsUsageHandler.php`:

```php
public function ensureUsable(string $driverName, SmsDriver $driver): void
{
    $requiredByDriver = match ($driverName) {
        'kavenegar'    => ['token'],
        'smsir'        => ['api_key', 'secret_key'],
        'mellipayamac' => ['username', 'password'],
        'yourprovider' => ['api_key', 'api_secret'], // Add this
        default        => [],
    };

    // ... rest of validation logic
}
```

### 5. Set as Default or Failover

Update your `.env` to use the new driver:

```env
# As default
SMS_DEFAULT=yourprovider

# Or as failover
SMS_FAILOVER=kavenegar,yourprovider,smsir
```

<a name="database-schema"></a>
## Database Schema

### SMS Tracking Table

The `sms` table stores all outgoing SMS records:

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `driver` | string | Driver name used |
| `template` | string | Template identifier |
| `inputs` | json | Template inputs |
| `phone` | string | Recipient phone |
| `message` | text | Final message |
| `provider_message_id` | string | Provider's message ID |
| `notification_template_id` | int | FK to notification_templates |
| `status` | string | pending/sent/delivered/failed |
| `error` | text | Error message if failed |
| `published` | boolean | Visibility flag |
| `languages` | json | Supported languages |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update |

### Notification Templates Table

The `notification_templates` table stores reusable message templates:

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `name` | string | Template name |
| `channel` | string | Channel (sms, email, etc.) |
| `message_template` | text | Message with `{placeholders}` |
| `inputs` | json | Default input values |
| `published` | boolean | Active status |
| `languages` | json | Translations |
| `created_at` | timestamp | Creation time |
| `updated_at` | timestamp | Last update |

<a name="api-reference"></a>
## API Reference

### SmsManager Methods

#### Factory Method
```php
SmsManager::instance(): self
```
Create a new fluent SMS builder instance.

---

#### Message Methods
```php
->message(string $message): self
```
Set plain text message.

```php
->otp(SmsTemplateEnum $template): self
```
Set OTP template from enum.

```php
->template(NotificationTemplate $template): self
```
Load template from model (includes inputs).

```php
->templateId(int $id): self
```
Load template by ID.

---

#### Input Methods
```php
->input(string $key, string $value): self
```
Add single placeholder value.

```php
->inputs(array $inputs): self
```
Merge multiple placeholder values.

---

#### Recipient Methods
```php
->number(string $phone): self
```
Add single phone number.

```php
->numbers(array $phones): self
```
Add multiple phone numbers.

```php
->user(int $userId): self
```
Add user by ID (resolves phone from User model).

```php
->users(array $userIds): self
```
Add multiple users by IDs.

---

#### Execution Methods
```php
->send(): void
```
Send SMS to all recipients with failover.

```php
->checkStatus(): array
```
Query delivery status for configured recipients.

---

### SmsTemplateEnum

Predefined OTP templates:

```php
SmsTemplateEnum::LOGIN_OTP
SmsTemplateEnum::REGISTER_OTP
SmsTemplateEnum::FORGOT_PASSWORD_OTP
SmsTemplateEnum::VERIFY_PHONE_OTP
```

---

### SmsSendStatusEnum

Message statuses:

```php
SmsSendStatusEnum::PENDING    // Created, not yet sent
SmsSendStatusEnum::SENT       // Successfully sent to provider
SmsSendStatusEnum::DELIVERED  // Confirmed delivered to recipient
SmsSendStatusEnum::FAILED     // Failed to send
```

---

## Examples in Context

### Controller Example

```php
namespace App\Http\Controllers;

use App\Enums\SmsTemplateEnum;
use App\Services\Sms\SmsManager;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $code = random_int(100000, 999999);
        
        // Store code in cache/session
        cache()->put("otp:{$request->phone}", $code, now()->addMinutes(5));
        
        // Send OTP
        SmsManager::instance()
            ->otp(SmsTemplateEnum::LOGIN_OTP)
            ->input('code', (string) $code)
            ->input('expires', '5')
            ->number($request->phone)
            ->send();
            
        return response()->json(['message' => 'OTP sent successfully']);
    }
}
```

### Job Example

```php
namespace App\Jobs;

use App\Models\NotificationTemplate;
use App\Services\Sms\SmsManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmationSms implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $orderId,
        public int $userId
    ) {}

    public function handle(): void
    {
        $template = NotificationTemplate::where('name', 'order_confirmation')
            ->where('channel', 'sms')
            ->firstOrFail();
            
        SmsManager::instance()
            ->template($template)
            ->input('order_id', (string) $this->orderId)
            ->input('tracking_url', route('orders.track', $this->orderId))
            ->user($this->userId)
            ->send();
    }
}
```

---

## Troubleshooting

### No Recipients Error
```
InvalidDriverConfigurationException: No recipients provided.
```
**Solution:** Add at least one recipient using `->number()`, `->numbers()`, `->user()`, or `->users()`.

### No Message Error
```
InvalidDriverConfigurationException: No message or template provided.
```
**Solution:** Set a message with `->message()`, `->otp()`, `->template()`, or `->templateId()`.

### Driver Not Available
```
DriverNotAvailableException: No SMS drivers are available to send messages.
```
**Solution:** Check your `.env` credentials and ensure at least one driver is properly configured.

### Missing Credentials
```
InvalidDriverConfigurationException: [kavenegar] missing required credential: token
```
**Solution:** Ensure all required environment variables are set for your configured drivers.

---

## Best Practices

1. **Use Templates for Reusable Messages**: Store frequently used messages in `NotificationTemplate` for easy management.

2. **Queue SMS Jobs**: For bulk sending, dispatch jobs to avoid blocking requests:
   ```php
   dispatch(new SendBulkSmsJob($userIds, $template));
   ```

3. **Monitor Delivery Status**: Periodically run `checkStatus()` for critical messages (OTP, transactions).

4. **Configure Failover**: Always set multiple providers in `SMS_FAILOVER` for reliability.

5. **Log Errors**: Implement proper error handling and logging for production:
   ```php
   try {
       SmsManager::instance()->message('...')->number('...')->send();
   } catch (\Exception $e) {
       Log::error('SMS failed: ' . $e->getMessage());
   }
   ```

---

For more information, see the [Laravel Documentation](https://laravel.com/docs) and provider-specific API docs.

