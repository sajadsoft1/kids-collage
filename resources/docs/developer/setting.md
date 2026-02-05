# Settings System

- [Introduction](#introduction)
- [Architecture Overview](#architecture-overview)
- [BaseTemplate](#basetemplate)
- [SettingService](#settingservice)
- [Creating a New Setting Group](#creating-a-new-setting-group)
- [Usage Examples](#usage-examples)
- [Best Practices](#best-practices)

<a name="introduction"></a>
## Introduction

The Settings System provides a flexible, template‑driven way to manage application configuration.
All settings are stored in a single `settings` table using a schemaless JSON column (`extra_attributes`),
and are organized by logical keys defined in [`SettingEnum`](file:///app/Enums/SettingEnum.php).

Each setting group (for example `general`, `security`, `product`, `seo_pages`, `site_data`, ...) has:

- a **single row** in the `settings` table identified by `key` (enum value),
- a **template class** that defines available fields, validation rules, and default values,
- optional **seeding logic** that initializes defaults on first run.

This document explains how templates work, how `SettingService` orchestrates them, and how to use
settings in your code.

<a name="architecture-overview"></a>
## Architecture Overview

### Database

The [`Setting`](file:///app/Models/Setting.php) model stores configuration in a schemaless way:

- **Columns** (simplified):
  - `key` (`string`): logical identifier, typically `SettingEnum` value.
  - `permissions` (`json`): which roles/permissions can manage this setting.
  - `extra_attributes` (`json`, nullable): actual configuration values.
  - `show` (`boolean`): flag used in admin UI.

The `extra_attributes` field is cast to `SchemalessAttributes` using Spatie's package, so you can
use convenient `get`/`set` methods with dot notation.

### Enums

[`SettingEnum`](file:///app/Enums/SettingEnum.php) defines all supported setting groups.
Examples:

- `SettingEnum::GENERAL` → key `general`
- `SettingEnum::SECURITY` → key `security`
- `SettingEnum::PRODUCT` → key `product`
- `SettingEnum::SEO_PAGES` → key `seo_pages`

Each enum case can provide metadata like `title()` and `help()` that are surfaced in the admin UI.

### Templates

Each setting group has a template class under
`App\Services\Setting\Templates` that extends
[`BaseTemplate`](file:///app/Services/Setting/Templates/BaseTemplate.php).
Examples:

- [`GeneralTemplate`](file:///app/Services/Setting/Templates/GeneralTemplate.php)
- [`SecurityTemplate`](file:///app/Services/Setting/Templates/SecurityTemplate.php)
- [`ProductTemplate`](file:///app/Services/Setting/Templates/ProductTemplate.php)
- [`SeoPagesTemplate`](file:///app/Services/Setting/Templates/SeoPagesTemplate.php)

Templates are responsible for:

- describing fields and groups (for UI rendering),
- providing validation rules,
- defining default values for seeding,
- performing any post‑update side effects.

### Service Layer

[`SettingService`](file:///app/Services/Setting/SettingService.php) is a small facade that:

- resolves the correct template class for a given setting key,
- exposes `get()` and `set()` helpers for reading/writing values,
- builds UI‑ready payloads for the admin panel via `show()`,
- triggers seeding via `seed()`.

<a name="basetemplate"></a>
## BaseTemplate

All templates extend `BaseTemplate` and must implement two methods:

```php
abstract class BaseTemplate
{
    abstract public function template(Setting $setting): array;

    abstract public function validate(Setting $setting, array $payload = []): array;
}
```

### `template(Setting $setting): array`

Returns a structured definition of all fields for a setting group. This structure is consumed by
the admin UI and typically looks like:

```php
return [
    $this->record('captcha_handling', self::SELECT, default_value: false, options: $booleanOptions),

    $this->recordComplex('company', [
        $this->record('name', self::TEXT, default_value: ''),
        $this->record('email', self::TEXT, default_value: ''),
    ]),
];
```

- **`record()`** defines a single leaf field (type, default, label, value, etc.).
- **`recordComplex()`** defines a group of related fields under one key.

Each record includes metadata like label, hint, help, layout ratio and optional permissions, which
the UI can use to render the form correctly.

### `validate(Setting $setting, array $payload): array`

Each template defines custom validation logic for its own payload shape. Internally it usually
relies on `makeValidator()` from `BaseTemplate`:

```php
public function validate(Setting $setting, array $payload = []): array
{
    return $this->makeValidator($payload, [
        'company' => ['required', 'array'],
        'company.name' => ['nullable', 'string', 'max:255'],
        'company.email' => ['nullable', 'email'],
    ], customAttributes: [
        'company.name' => trans('setting.configs.general.items.name.label'),
        'company.email' => trans('setting.configs.general.items.email.label'),
    ]);
}
```

The validated array returned from `validate()` is then flattened and saved into
`extra_attributes` by `BaseTemplate::update()`.

### `update()` and `seed()`

- `update(Setting $setting, array $payload)`
  - calls `validate()`
  - writes values into `extra_attributes` (using dot notation)
  - saves the model and calls `afterUpdate()` hook.

- `seed(SettingEnum $enum)`
  - creates or finds the `Setting` row for that enum,
  - iterates over `template()` records and writes `default` values **only when no value exists yet**, 
  - never overwrites existing values.

<a name="settingservice"></a>
## SettingService

The [`SettingService`](file:///app/Services/Setting/SettingService.php) provides a simple API on top
of templates.

### Resolving Template Classes

```php
public static function getSettingTemplateClass($key)
{
    return app('App\\Services\\Setting\\Templates\\' .
        StringHelper::convertToClassName($key . '_template'));
}
```

- For key `general` → `GeneralTemplate` is resolved from the container.
- For key `seo_pages` → `SeoPagesTemplate` is resolved.

This makes adding a new setting group as simple as:

1. Add a case to `SettingEnum` with value `my_new_setting`.
2. Create `App\Services\Setting\Templates\MyNewSettingTemplate`.

### Reading Settings

```php
use App\Services\Setting\\SettingService;
use App\\\Enums\\SettingEnum;

// Get all values for a group as array
$all = SettingService::get(SettingEnum::GENERAL);

// Get a single value using dot notation
$companyName = SettingService::get(SettingEnum::GENERAL, 'company.name', 'Kids Collage');

// Using string key instead of enum
$captchaEnabled = SettingService::get('security', 'captcha_handling', false);
```

### Writing Settings

`SettingService::set()` is a generic helper that delegates to the appropriate template:

```php
// Signature
public static function set(string|SettingEnum $enum, array $payload): Setting
```

Usage examples:

```php
use App\Services\\Setting\\SettingService;
use App\\\Enums\\SettingEnum;

// Update general company information
SettingService::set(SettingEnum::GENERAL, [
    'company' => [
        'name' => 'Kids Collage Academy',
        'email' => 'info@kids-collage.test',
    ],
]);

// Update security settings
SettingService::set('security', [
    'captcha_handling' => true,
]);
```

The payload shape must match what the template `validate()` method expects. If validation fails,
`ValidationException` is thrown with error messages ready for your controllers or Livewire
components.

### Showing Settings for Admin UI

```php
public function show(Setting $setting): array
```

This method:

- resolves the correct template,
- builds the full `rows` structure from `template()`,
- applies optional `filter_key` query parameter (e.g. `company`, `company.name`),
- returns a payload like:

```php
[
    'id' => $setting->id,
    'uuid' => $setting->uuid,
    'key' => $setting->key,
    'label' => $settingEnum?->title(),
    'help' => $settingEnum?->help(),
    'rows' => [...], // record/recordComplex definitions
]
```

You typically use this in API/Controller actions that power the admin settings pages
(Larecipe-style documentation or custom SPA).

### Seeding Defaults

```php
use App\Services\\Setting\\SettingService;
use App\\\Enums\\SettingEnum;

// In a seeder or installation command
app(SettingService::class)->seed(SettingEnum::GENERAL);
app(SettingService::class)->seed(SettingEnum::SECURITY);
app(SettingService::class)->seed(SettingEnum::PRODUCT);
```

Each template decides how to seed its own defaults. For complex cases (like SEO localized
fields) templates can override `seed()` completely.

<a name="creating-a-new-setting-group"></a>
## Creating a New Setting Group

This section shows a typical workflow to add a new setting group, similar in spirit to how the
Discount System is documented in `discount.md`.

### 1. Add Enum Case

In [`SettingEnum`](file:///app/Enums/SettingEnum.php):

```php
case ATTENDANCE = 'attendance';
```

Add any helper methods (e.g. `title()`, `help()`) so the admin UI can display a proper label.

### 2. Create Template Class

Create `App\Services\Setting\Templates\AttendanceTemplate`:

```php
namespace App\Services\Setting\Templates;

use App\Enums\SettingEnum;
use App\Exceptions\ValidationException;
use App\Models\Setting;

class AttendanceTemplate extends BaseTemplate
{
    public function __construct()
    {
        $this->settingEnum = SettingEnum::ATTENDANCE;
    }

    public function template(Setting $setting): array
    {
        $this->setting = $setting;

        return [
            $this->record('grace_minutes', self::NUMBER, default_value: 5),
            $this->record('max_absences', self::NUMBER, default_value: 3),
        ];
    }

    /** @throws ValidationException */
    public function validate(Setting $setting, array $payload = []): array
    {
        return $this->makeValidator($payload, [
            'grace_minutes' => ['required', 'integer', 'min:0', 'max:60'],
            'max_absences' => ['required', 'integer', 'min:1', 'max:365'],
        ]);
    }
}
```

You can now seed and use this setting group through `SettingService`.

### 3. Seed Defaults (Optional)

You can either rely on `BaseTemplate::seed()` or override `seed()` in your template for more
control. For simple non‑complex records, the base implementation is usually enough.

<a name="usage-examples"></a>
## Usage Examples

### Example 1: Reading Settings in a Controller

```php
use App\Enums\SettingEnum;
use App\Services\Setting\SettingService;

class ContactController
{
    public function show()
    {
        $company = SettingService::get(SettingEnum::GENERAL, 'company', []);

        return view('contact', [
            'companyName' => $company['name'] ?? null,
            'companyEmail' => $company['email'] ?? null,
        ]);
    }
}
```

### Example 2: Using Helper Functions

```php
// Get setting value
$companyName = setting('general', 'company.name');
$captchaEnabled = setting('security', 'captcha_handling', false);

// Check if setting is enabled
if (settingEnabled('security', 'captcha_handling')) {
    // Show captcha
}

// Check if setting is disabled
if (settingDisabled('notification', 'order_create.email')) {
    // Skip email notification
}

// Check specific value
if (settingIs('general', 'company.name', 'Kids Collage')) {
    // Do something
}
```

### Example 3: Using Blade Directives

```blade
{{-- Output setting value --}}
<p>Company: @setting('general', 'company.name', 'N/A')</p>

{{-- Conditional rendering based on setting --}}
@setting('security', 'captcha_handling')
    <x-captcha />
@endsetting

{{-- Alternative: check if enabled --}}
@settingEnabled('notification', 'order_create.email')
    <p>Email notifications are enabled</p>
@endsettingEnabled

{{-- Check if disabled --}}
@settingDisabled('product', 'show_out_of_stock_products_price')
    <p>Out of stock prices are hidden</p>
@endsettingDisabled

{{-- Check specific value --}}
@setting('general', 'company.name', 'Kids Collage')
    <p>Welcome to Kids Collage!</p>
@endsetting
```

### Example 4: Updating Settings from a Form (Controller)

```php
use App\Enums\SettingEnum;
use App\Services\Setting\SettingService;
use Illuminate\Http\Request;

class GeneralSettingController
{
    public function update(Request $request)
    {
        $payload = $request->only([
            'company',
        ]);

        // Throws ValidationException on invalid data
        $setting = SettingService::set(SettingEnum::GENERAL, $payload);

        return redirect()->back()->with('status', 'Settings updated.');
    }
}
```

### Example 5: Livewire Component Using Settings

```php
use App\Enums\SettingEnum;
use App\Services\Setting\SettingService;
use Livewire\Component;

class SecuritySettingsForm extends Component
{
    public bool $captcha_handling = false;

    public function mount(): void
    {
        $this->captcha_handling = settingEnabled(SettingEnum::SECURITY, 'captcha_handling');
    }

    public function save(): void
    {
        SettingService::set(SettingEnum::SECURITY, [
            'captcha_handling' => $this->captcha_handling,
        ]);

        $this->dispatchBrowserEvent('notify', 'Security settings updated.');
    }
}
```

<a name="best-practices"></a>
## Best Practices

1. **Always validate through templates**  
   Do not write directly into `extra_attributes` from controllers. Always use
   `SettingService::set()` (which delegates to template `validate()`), so validation,
   translations, and defaults stay centralized.

2. **Use enums instead of raw strings**  
   Prefer `SettingEnum::GENERAL` over `'general'` to avoid typos and to benefit from IDE support.

3. **Use helper functions for cleaner code**  
   Instead of `SettingService::get()`, use the shorter `setting()` helper for better readability:
   ```php
   // Instead of this
   $value = SettingService::get(SettingEnum::GENERAL, 'company.name');
   
   // Use this
   $value = setting('general', 'company.name');
   ```

4. **Use Blade directives in views**  
   Blade directives make your templates cleaner and more readable:
   ```blade
   @settingEnabled('security', 'captcha_handling')
       <x-captcha />
   @endsettingEnabled
   ```

5. **Group related fields with `recordComplex()`**  
   For better UI and clearer schemas, group related fields (like `company.*`, `payment.*`) into
   complex records.

6. **Leverage translations**  
   Labels, hints, and help texts are resolved from translation files under `setting.configs.*`.
   Keep these texts up to date so the settings UI stays self‑documenting.

7. **Keep seeding idempotent**  
   Seed methods should never overwrite existing values. They should only fill in missing defaults.

8. **Avoid business logic in controllers**  
   Use `afterUpdate()` hooks in templates when settings changes need to trigger side effects
   (e.g. cache invalidation, background jobs).

9. **Cache setting values when appropriate**  
   For frequently accessed settings, consider caching the values at the application level
   to reduce database queries.

10. **Document custom templates**  
    If you create custom setting templates, document the expected payload structure and
    validation rules in your team's internal documentation.
