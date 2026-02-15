# ماژول نوتیفیکیشن (Notification Module)

هدف این ماژول ساخت یک **پکیج کامل لایه بیزینس** برای نوتیفیکیشن است؛ به‌صورت چندکاناله، قابل تنظیم از طرف ادمین و کاربر، مبتنی بر قالب‌ها و رویدادها، با لاگ و صف برای کانال‌های سنگین.

---

## ۱. نمای کلی معماری

```
┌─────────────────────────────────────────────────────────────────────────────┐
│  نقطه ورود: Event یا Pipeline یا مستقیم                                       │
│  NotificationRequested(notifiable, BaseNotification, profile?, context)       │
└─────────────────────────────────────────────────────────────────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│  DispatchNotification (Listener)                                             │
│  → notification->deliver(notifiable, profile, context)                       │
└─────────────────────────────────────────────────────────────────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│  BaseNotification::deliver()                                                 │
│  → buildMessage() → NotificationDispatcher::dispatch()                       │
└─────────────────────────────────────────────────────────────────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│  NotificationDispatcher                                                      │
│  → PreferenceResolver (کانال‌های فعال) → برای هر کانال: Log + Send/Queue      │
└─────────────────────────────────────────────────────────────────────────────┘
                                        │
                    ┌───────────────────┼───────────────────┐
                    ▼                   ▼                   ▼
            ┌──────────────┐   ┌──────────────┐   ┌──────────────┐
            │  Database    │   │  Email       │   │  SMS         │
            │  (همین‌جا)    │   │  (Job)       │   │  (Job)       │
            └──────────────┘   └──────────────┘   └──────────────┘
```

جریان اصلی:

1. **ورود**: ارسال نوتیفیکیشن با `NotificationRequested` یا مستقیم با `BaseNotification::deliver()`.
2. **Listener**: `DispatchNotification` فقط `deliver()` را صدا می‌زند.
3. **BaseNotification**: پیام را با `buildMessage()` می‌سازد (از قالب‌ها و context) و به `NotificationDispatcher` می‌دهد.
4. **Dispatcher**: با `NotificationPreferenceResolver` کانال‌های مجاز را می‌گیرد، برای هر کانال یک `NotificationLog` می‌سازد، سپس یا مستقیم درایور را صدا می‌زند (مثلاً Database) یا Job مربوطه را dispatch می‌کند (Email/SMS).
5. **درایورها**: هر کانال یک Driver دارد که از Contract `NotificationChannelDriver` پیروی می‌کند و ارسال واقعی را انجام می‌دهد.

---

## ۲. لایه‌های اصلی در `app/Support/Notifications`

این بخش **قلب لایه بیزینس** نوتیفیکیشن است و وابستگی به فریمورک را در حد لازم نگه می‌دارد.

### ۲.۱. NotificationMessage (`Messages/NotificationMessage.php`)

- **نقش**: شیء حامل محتوای نوتیفیکیشن برای ارسال.
- **ویژگی‌ها**:
  - `event`: نوع رویداد (`NotificationEventEnum`).
  - به ازای هر کانال یک **payload** (مثلاً `title`, `body`, `subject`, `meta`).
  - **context** مشترک (مثل `user_name`, `action_url`, `locale`, `notification_class`).
- **ساخت**:
  - `NotificationMessage::make($event)->withContext($context)->withChannel($channel, $payload)`.
- **استفاده در Dispatcher**: برای هر کانال فعال، `channelPayload($channel)` گرفته می‌شود؛ اگر خالی بود آن کانال رد می‌شود.

### ۲.۲. NotificationChannelRegistry

- **نقش**: منبع حقیقت برای **پیکربندی کانال‌ها به‌ازای هر رویداد** (فعال/غیرفعال، قابل تغییر توسط کاربر، مقدار پیش‌فرض).
- **ورودی**: آرایهٔ config با کلید `notification_channels` (در صورت نبودن، آرایه خالی).
- **متدهای مهم**:
  - `channelConfig(NotificationEventEnum $event)`: تنظیمات کانال‌ها برای آن event.
  - `enabledChannels(NotificationEventEnum $event)`: لیست کانال‌های فعال فقط بر اساس config.
  - `isUserToggleable($event, $channel)`: آیا کاربر می‌تواند این کانال را برای این event روشن/خاموش کند.
  - `defaultState($event, $channel)`: حالت پیش‌فرض (روشن/خاموش).
  - `resolveDriver(NotificationChannelEnum $channel)`: برگرداندن نمونهٔ Driver از container (با `driverBinding()` روی enum).
- **ساختار config پیشنهادی** (در صورت استفاده):

  ```php
  'notification_channels' => [
      'defaults' => [
          'channels' => [
              'database' => ['enabled' => true, 'togglable' => false],
              'email'    => ['enabled' => true, 'togglable' => true],
              'sms'      => ['enabled' => false, 'togglable' => true],
          ],
      ],
      'events' => [
          'auth_register' => [
              'channels' => [
                  'email' => ['enabled' => true, 'togglable' => true],
                  'sms'   => ['enabled' => true, 'togglable' => true],
              ],
          ],
      ],
  ],
  ```

### ۲.۳. NotificationPreferenceResolver

- **نقش**: تعیین **نهایی** کانال‌های فعال برای یک رویداد با ترکیب:
  - تنظیمات پیش‌فرض هر event (از Registry),
  - override سراسری (مثلاً از `SettingService` با کلید `SettingEnum::NOTIFICATION` و زیرکلید `event`),
  - override کاربر (از `Profile::getNotificationSettings()`)، فقط برای کانال‌هایی که `isUserToggleable` هستند.
- **متدهای مهم**:
  - `enabledChannels(?Profile $profile, NotificationEventEnum $event)`: مجموعه کانال‌های فعال برای آن event و آن پروفایل (یا بدون پروفایل).
  - `shouldSend(?Profile $profile, $event, $channel)`: آیا این کانال برای این ترکیب باید ارسال شود.

بدین ترتیب لایه بیزینس «چه کانال‌هایی برای چه کسی و چه رویدادی فعال است» را یک‌جا در این کلاس حل می‌کند.

### ۲.۴. NotificationDispatcher

- **نقش**: هماهنگ‌کننده ارسال؛ برای هر کانال مجاز، یک لاگ می‌سازد و ارسال را یا هم‌زمان انجام می‌دهد یا به صف می‌سپارد.
- **ورودی**: `notifiable`, `NotificationMessage`, `?Profile`, `context`.
- **جریان**:
  1. کانال‌های فعال: `$this->preferenceResolver->enabledChannels($profile, $message->event)`.
  2. برای هر کانال، `$message->channelPayload($channel)`؛ اگر خالی بود، `continue`.
  3. برای هر کانال: `createLog()` → سپس یا `sendThroughChannel()` هم‌زمان یا `queueChannel()`.
- **قوانین صف**:
  - کانال‌های `EMAIL` و `SMS` از طریق Job (مثلاً `SendEmailNotificationJob`, `SendSmsNotificationJob`) ارسال می‌شوند.
  - کانال `DATABASE` معمولاً هم‌زمان و با Driver مستقیم.
- **لاگ**: هر ارسال (یا هر قرارگیری در صف) یک رکورد در `NotificationLog` با وضعیت‌های `queued` → `processing` → `sent` یا `failed` و فیلدهای `payload`, `response`, `error_message`, `attempts`.

این کلاس جایی است که «سیاست ارسال» (لاگ، صف، خطا) با «ترجیحات کاربر و ادمین» (PreferenceResolver) و «اجرای واقعی» (Driverها) به هم وصل می‌شوند.

### ۲.۵. Contract و Driverها

- **`NotificationChannelDriver`** (در `Contracts/`):
  - `channel(): NotificationChannelEnum`
  - `send(object $notifiable, NotificationEventEnum $event, array $payload, array $context = []): array`
  - خروجی آرایه‌ای از اطلاعات پاسخ (مثلاً `status`, `recipient`, `error`) برای ذخیره در `NotificationLog.response`.

- **Driverهای فعلی** (در `Drivers/`):
  - **DatabaseChannelDriver**: ذخیره در جدول `notifications` (Laravel Database Notifications) با `type` و `data`؛ مناسب نوتیفیکیشن درون‌برنامه.
  - **EmailChannelDriver**: ارسال با `NotificationMail` و `Mail::to($recipient)`؛ گیرنده از `context['email']` یا `routeNotificationFor('mail')` یا `$notifiable->email`.
  - **SmsChannelDriver**: استفاده از `SmsManager`؛ گیرنده از `context['mobile']` یا `routeNotificationFor('sms')` یا `mobile`/`phone`؛ پشتیبانی از قالب OTP و پیام متنی از طریق `payload['body']` و `payload['meta']`.

برای تبدیل این لایه به پکیج، کافی است این Contract و Driverها را در پکیج قرار دهید و از طریق config و Service Provider ثبت کنید.

---

## ۳. لایه اپلیکیشن (خارج از Support)

این بخش وابسته به اپلیکیشن و فریمورک است؛ در پکیج می‌توان فقط قراردادها و نمونه‌های پیشنهادی را گذاشت.

### ۳.۱. Event و Listener

- **`NotificationRequested`**:  
  `notifiable`, `BaseNotification`, `?Profile`, `context`.
- **`DispatchNotification`**: فقط `$event->notification->deliver($event->notifiable, $event->profile, $event->context)`.

هر جایی که بخواهید نوتیفیکیشن را از بیزینس جدا نگه دارید، فقط `NotificationRequested` را fire می‌کنید و بقیه را ماژول انجام می‌دهد.

### ۳.۲. BaseNotification

- **نقش**: کلاس پایه همهٔ نوتیفیکیشن‌های اپلیکیشن؛ یک رویداد (`event()`) و محتوای هر کانال را از **قالب‌ها** و **context** می‌سازد و به Dispatcher می‌سپارد.
- **متدهای کلیدی**:
  - `event(): NotificationEventEnum`
  - `context(object $notifiable): array` — داده برای جایگذاری در قالب‌ها.
  - `buildMessage(object $notifiable): NotificationMessage` — ساخت پیام با `NotificationTemplate` برای هر کانال پشتیبانی‌شده.
  - `buildPayload(NotificationChannelEnum $channel, array $context): array` — پر کردن subject, title, subtitle, body, cta, meta از قالب و جایگذاری placeholderها.
  - `deliver(object $notifiable, ?Profile $profile = null, array $context = []): void` — نقطه ورود نهایی برای ارسال (ساخت Message و فراخوانی Dispatcher).
- **قالب‌ها**: از مدل `NotificationTemplate` با فیلتر `event`, `channel`, `locale` و فقط قالب‌های فعال (`active()`). فیلدهای قالب: `subject`, `title`, `subtitle`, `body`, `cta`, `placeholders`.
- **کانال‌های پشتیبانی‌شده پیش‌فرض**: Database, Email, SMS (قابل override در کلاس فرزند با `supportedChannels()`).

با این طراحی، هر نوتیفیکیشن جدید فقط یک کلاس فرزند از `BaseNotification` است که `event()` و `context()` را مشخص می‌کند و در صورت نیاز `channelMeta()` یا `supportedChannels()` را override می‌کند.

### ۳.۳. NotificationService (سرویس خواندن/نمایش)

- **نقش**: سرویس کمکی برای **نمایش** نوتیفیکیشن‌های کاربر (تعداد نخوانده، لیست اخیر).
- **متدها**: `getUnreadCount(User)`, `getRecentNotifications(User, $limit)`, `clearCache(User)`.
- وابسته به مدل User و جدول `notifications` (Laravel) و در صورت تمایل کش؛ برای پکیج می‌توان به صورت اختیاری یا با interface جدا ارائه شود.

### ۳.۴. مدل‌ها و دیتابیس

- **NotificationLog**: هر درخواست ارسال (یا صف) یک رکورد؛ فیلدهای event, channel, notifiable_type/id, notification_class, status, attempts, queued_at, sent_at, failed_at, payload, response, error_message. برای گزارش و عیب‌یابی.
- **NotificationTemplate**: قالب متنی به ازای event + channel + locale؛ فیلدهای subject, title, subtitle, body, cta, placeholders, is_active.
- **notifications** (Laravel): ذخیره نوتیفیکیشن‌های درون‌برنامه برای هر notifiable (مثلاً User).

### ۳.۵. Jobs و Provider

- **SendChannelNotificationJob** (abstract): با استفاده از `NotificationChannelRegistry::resolveDriver()` ارسال را انجام می‌دهد و وضعیت `NotificationLog` را به‌روز می‌کند.
- **SendEmailNotificationJob** / **SendSmsNotificationJob**: پیاده‌سازی برای کانال Email و SMS.
- **NotificationServiceProvider**: ثبت Driverها به‌صورت singleton با کلیدهای `notifications.channels.*` و ثبت Listener برای `NotificationRequested`.

---

## ۴. Enums

- **NotificationChannelEnum**: مقادیر کانال (sms, email, database, firebase, telegram, whatsapp, push) به‌همراه متدهای کمکی مثل `title()`, `icon()`, `description()`, `driverBinding()`, `isFutureChannel()`.
- **NotificationEventEnum**: رویدادهای بیزینسی (ثبت‌نام، تأیید، سفارش، پرداخت، جلسه، اعلان و …) با `title()`, `description()`, `category()`, `categoryForUsers()` و متدهای گروه‌بندی برای UI تنظیمات.

این enumها مرز بین «نام کانال/رویداد» و «نمایش و پیکربندی» را مشخص می‌کنند و برای پکیج قابل استخراج یا تعریف در پکیج هستند.

---

## ۵. تنظیمات کاربر (Profile)

- **ذخیره**: در `Profile` داخل `extra_attributes` با کلید `notification_settings` به صورت `[ event => [ channel => bool ] ]`.
- **متدها**: `getNotificationSettings()`, `updateNotificationSettings()`, `enableNotification()`, `disableNotification()`, `getEnabledChannelsForEvent()`.
- **اعتبار**: فقط کانال‌هایی که `NotificationChannelRegistry::isUserToggleable($event, $channel)` آن‌ها true است در به‌روزرسانی کاربر لحاظ می‌شوند.

این لایه ترجیحات کاربر را به `NotificationPreferenceResolver` می‌دهد و در Dispatcher تعیین نهایی «چه کانال‌هایی ارسال شوند» انجام می‌شود.

---

## ۶. جمع‌بندی برای پکیج لایه بیزینس

برای رسیدن به یک **پکیج کامل لایه بیزینس نوتیفیکیشن** می‌توان این‌ها را در پکیج متمرکز کرد:

| بخش | در پکیج | توضیح کوتاه |
|-----|---------|-------------|
| **NotificationMessage** | ✅ | شیء حامل پیام و payload هر کانال |
| **NotificationChannelRegistry** | ✅ | پیکربندی کانال‌ها به‌ازای event (با config) |
| **NotificationPreferenceResolver** | ✅ | ترکیب پیش‌فرض + ادمین + کاربر و تعیین کانال‌های فعال |
| **NotificationDispatcher** | ✅ | ایجاد Log، تصمیم صف/هم‌زمان، فراخوانی Driver |
| **NotificationChannelDriver** (Contract) | ✅ | قرارداد ارسال برای هر کانال |
| **Driverهای نمونه** (Database, Email, SMS) | ✅ | پیاده‌سازی اولیه؛ اپلیکیشن می‌تواند override یا گسترش دهد |
| **BaseNotification** | اختیاری / نمونه | منطق buildMessage + deliver و اتصال به قالب؛ می‌توان در پکیج به صورت abstract/interface باشد |
| **Event/Listener** | اختیاری | فقط یک نقطه ورود استاندارد؛ اپلیکیشن هم می‌تواند مستقیم `deliver()` صدا بزند |
| **NotificationLog / NotificationTemplate** | در پکیج یا migration پکیج | مدل و مایگریشن در پکیج تا لاگ و قالب یکپارچه باشند |
| **Enums** | ✅ | NotificationChannelEnum و NotificationEventEnum (یا نسخهٔ پایه در پکیج و extend در اپلیکیشن) |
| **Config** | ✅ | ساختار `notification_channels` و ثبت Driverها در ServiceProvider پکیج |

با این ساختار، ماژول فعلی شما **همین الان** یک لایه بیزینس کامل برای نوتیفیکیشن است: چندکاناله، مبتنی بر رویداد و قالب، با ترجیحات سراسری و کاربر، لاگ و صف و Driverهای توسعه‌پذیر.

---

## پکیج استخراج‌شده

ماژول به صورت پکیج در مسیر زیر قرار گرفته و در اپلیکیشن استفاده می‌شود:

- **مسیر**: `packages/karnoweb/laravel-notification`
- **نام فضای نام**: `Karnoweb\LaravelNotification`
- **ریپازیتوری**: `git@github.com:karnoweb/laravel-notification.git`

نصب در پروژه از طریق path repository در `composer.json` انجام شده است. برای پوش به گیت‌هاب، از داخل پوشه پکیج: `git init`، اضافه کردن ریموت و سپس `git push`.
