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

### Kavenegar Implementation Details

The `KavenegarDriver` is fully integrated with Kavenegar's REST API using Laravel's HTTP client. Here are the key features:

**API Endpoints Used:**
- `/sms/send.json` - Send single SMS
- `/sms/sendarray.json` - Send bulk SMS (array method)
- `/sms/select.json` - Check delivery status
- `/account/info.json` - Validate credentials (ping)

**Features:**
- ✅ Laravel HTTP client with 30s timeout for sends
- ✅ Automatic retry (2 attempts with 100ms delay)
- ✅ Proper error handling and logging
- ✅ Kavenegar status code mapping (1-10 range)
- ✅ Bulk sending optimization with `sendarray` API
- ✅ Real connectivity check via `account/info` endpoint

**Status Mapping:**
- `1, 2` → `pending` (در صف ارسال / زمان‌بندی شده)
- `4, 5` → `sent` (ارسال شده به مخابرات)
- `10` → `delivered` (رسیده به گیرنده)
- Other codes → `failed`

**Example Usage:**
```php
use App\Services\Sms\SmsManager;

// The KavenegarDriver handles everything automatically
SmsManager::instance()
    ->message('سلام، پیام تست')
    ->numbers(['09120000000', '09123334444'])
    ->send();
```

**API Response Format:**
```json
{
    "return": {
        "status": 200,
        "message": "تایید شد"
    },
    "entries": [
        {
            "messageid": 8792343,
            "message": "خدمات پیام کوتاه کاوه نگار",
            "status": 1,
            "statustext": "در صف ارسال",
            "sender": "10004346",
            "receptor": "09123456789",
            "date": 1356619709,
            "cost": 120
        }
    ]
}
```

---

### SMS.ir Implementation Details

The `SmsIrDriver` is fully integrated with SMS.ir's REST API using Laravel's HTTP client. Here are the key features:

**API Endpoints Used:**
- `/send/bulk` - Send single or bulk SMS
- `/send/verify` - Send verification/template SMS
- `/send/live/{messageId}` - Check delivery status
- `/credit/get` - Check account credit (ping)

**Authentication:**
- Uses `X-API-KEY` header for authentication
- No token in URL (unlike Kavenegar)

**Features:**
- ✅ Laravel HTTP client with 30s timeout for sends
- ✅ Automatic retry (2 attempts with 100ms delay)
- ✅ Proper error handling and logging
- ✅ SMS.ir status code mapping (1-7 range)
- ✅ Bulk sending support (up to 100 numbers per request)
- ✅ Real connectivity check via `credit/get` endpoint
- ✅ Complete error message translations (Persian)

**Status Mapping:**
- `1` → `delivered` (رسیده به گوشی)
- `3` → `pending` (پردازش در مخابرات)
- `5` → `sent` (رسیده به مخابرات)
- `2, 4, 6, 7` → `failed` (نرسیده/خطا/لیست سیاه)

**Error Codes:** (Full list of 25+ error codes with Persian messages)
- `1` - Success
- `102` - Insufficient credit
- `104` - Invalid mobile number(s)
- `105` - Too many mobiles (max 100)
- `115` - Blacklisted numbers
- `123` - Line needs activation
- And more...

**Example Usage:**
```php
use App\Services\Sms\SmsManager;

// The SmsIrDriver handles everything automatically
SmsManager::instance()
    ->message('پیام تستی از SMS.ir')
    ->numbers(['09120000000', '09123334444'])
    ->send();
```

**API Request Format:**
```json
{
    "lineNumber": 300000000000,
    "messageText": "Your Text",
    "mobiles": [
        "09120000000",
        "09123334444"
    ],
    "sendDateTime": null
}
```

**API Response Format:**
```json
{
    "status": 1,
    "message": "عملیات با موفقیت انجام شد",
    "data": {
        "messageId": 123456789,
        "cost": 150
    }
}
```

---

### Adding Custom Drivers

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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YourProviderDriver extends AbstractSmsDriver implements DeliveryReportFetcher, PingableSmsDriver, SmsDriver
{
    /** @var array{api_key?:string,api_secret?:string,sender?:string} */
    public array $config;

    protected string $baseUrl = 'https://api.yourprovider.com/v1/';

    public function send(string $phoneNumber, string $message): void
    {
        if (empty($this->config['api_key'])) {
            throw new DriverConnectionException('YourProvider API key not set.');
        }

        $url = $this->baseUrl . 'sms/send';

        try {
            // Use Laravel HTTP client with timeout and retry
            $response = Http::timeout(30)
                ->retry(2, 100)
                ->post($url, [
                    'api_key'  => $this->config['api_key'],
                    'phone'    => $phoneNumber,
                    'message'  => $message,
                    'sender'   => $this->config['sender'] ?? null,
                ]);

            if (!$response->successful()) {
                Log::error('YourProvider send failed', [
                    'phone'    => $phoneNumber,
                    'status'   => $response->status(),
                    'response' => $response->body(),
                ]);

                throw new DriverConnectionException('YourProvider API error: ' . $response->body());
            }

            $data = $response->json();

            // Check provider-specific success indicators
            if (!($data['success'] ?? false)) {
                throw new DriverConnectionException('YourProvider error: ' . ($data['message'] ?? 'Unknown error'));
            }
        } catch (DriverConnectionException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('YourProvider connection error', [
                'phone'     => $phoneNumber,
                'exception' => $e->getMessage(),
            ]);

            throw new DriverConnectionException('Failed to connect to YourProvider API: ' . $e->getMessage(), previous: $e);
        }
    }

    public function sendTo(string $phoneNumber, string $message): void
    {
        $this->send($phoneNumber, $message);
    }

    public function sendToGroup(array $phoneNumbers, string $message): void
    {
        // Option 1: Use provider's bulk API if available
        $url = $this->baseUrl . 'sms/bulk';
        
        $response = Http::timeout(30)->post($url, [
            'api_key'  => $this->config['api_key'],
            'phones'   => $phoneNumbers,
            'message'  => $message,
            'sender'   => $this->config['sender'] ?? null,
        ]);
        
        // Option 2: Loop through individual sends
        // foreach ($phoneNumbers as $number) {
        //     $this->send((string) $number, $message);
        // }
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
        if (empty($this->config['api_key'])) {
            return false;
        }

        try {
            $url = $this->baseUrl . 'account/info';

            $response = Http::timeout(10)->get($url, [
                'api_key' => $this->config['api_key'],
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::warning('YourProvider ping failed', ['exception' => $e->getMessage()]);

            return false;
        }
    }

    public function fetchDeliveryReport(string $providerMessageId): array
    {
        if (empty($this->config['api_key'])) {
            return ['status' => 'unknown', 'delivered_at' => null, 'raw' => null];
        }

        try {
            $url = $this->baseUrl . 'sms/status';

            $response = Http::timeout(10)->get($url, [
                'api_key'    => $this->config['api_key'],
                'message_id' => $providerMessageId,
            ]);

            if (!$response->successful()) {
                return ['status' => 'unknown', 'delivered_at' => null, 'raw' => null];
            }

            $data = $response->json();

            // Map provider status to standard statuses
            $status = match ($data['status'] ?? '') {
                'sent' => 'sent',
                'delivered' => 'delivered',
                'failed' => 'failed',
                default => 'pending',
            };

            return [
                'status'       => $status,
                'delivered_at' => $status === 'delivered' ? now()->toIso8601String() : null,
                'raw'          => $data,
            ];
        } catch (\Exception $e) {
            Log::warning('YourProvider delivery report exception', [
                'message_id' => $providerMessageId,
                'exception'  => $e->getMessage(),
            ]);

            return ['status' => 'unknown', 'delivered_at' => null, 'raw' => null];
        }
    }
}
```

**Key Features:**
- Uses Laravel's `Http` facade for API requests
- Implements timeout (30s for sends, 10s for pings)
- Auto-retries failed requests (2 retries with 100ms delay)
- Comprehensive error logging with context
- Proper exception handling and re-throwing

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
        'smsir'        => ['api_key'],
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

