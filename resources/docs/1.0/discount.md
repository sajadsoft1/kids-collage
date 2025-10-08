# Discount Management System

- [Introduction](#introduction)
- [Database Schema](#database-schema)
- [Model Features](#model-features)
- [Discount Types](#discount-types)
- [Validation Rules](#validation-rules)
- [Usage Examples](#usage-examples)
- [Actions](#actions)
- [Admin Panel Integration](#admin-panel-integration)
- [API Reference](#api-reference)

<a name="introduction"></a>
## Introduction

The Discount Management System provides a comprehensive solution for creating and managing discount codes with advanced features including percentage/fixed discounts, user-specific targeting, usage limits, date-based activation, and minimum order requirements.

**Key Features:**
- Percentage and fixed amount discount types
- Global or user-specific discounts
- Usage limits (total and per user)
- Minimum order amount requirements
- Maximum discount cap for percentage types
- Date-based activation and expiration
- Automatic validation and calculation
- Complete audit trail with activity logs

<a name="database-schema"></a>
## Database Schema

### Discounts Table

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `code` | string | Unique discount code (uppercase) |
| `type` | enum | 'percentage' or 'amount' |
| `value` | decimal | Discount value (% or fixed amount) |
| `user_id` | bigint (nullable) | Specific user ID or null for global |
| `min_order_amount` | decimal | Minimum order amount required |
| `max_discount_amount` | decimal (nullable) | Maximum discount cap |
| `usage_limit` | integer (nullable) | Total usage limit or null for unlimited |
| `usage_per_user` | integer | Maximum uses per user |
| `used_count` | integer | Current usage count |
| `starts_at` | timestamp (nullable) | Activation date |
| `expires_at` | timestamp (nullable) | Expiration date |
| `is_active` | boolean | Active status |
| `description` | text (nullable) | Internal description |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

**Relationships:**
- `belongsTo` User (optional, for user-specific discounts)
- `hasMany` Orders (tracks discount usage)

<a name="model-features"></a>
## Model Features

### Query Scopes

```php
// Get active discounts
Discount::active()->get();

// Get valid discounts (active and within date range)
Discount::valid()->get();

// Get discounts for specific user
Discount::forUser($userId)->get();

// Get discount by code
Discount::byCode('SAVE10')->first();

// Get percentage discounts only
Discount::percentage()->get();

// Get fixed amount discounts only
Discount::fixedAmount()->get();
```

### Helper Methods

```php
$discount = Discount::find(1);

// Check discount properties
$discount->isUserSpecific();    // bool
$discount->isGlobal();           // bool
$discount->isValid();            // bool
$discount->hasReachedLimit();    // bool
$discount->isExpired();          // bool
$discount->isPercentage();       // bool
$discount->isFixedAmount();      // bool

// Calculate discount for order
$amount = $discount->calculateDiscount($orderAmount);

// Check if discount can be applied
$canApply = $discount->canBeApplied($orderAmount, $userId);

// Validate and calculate in one step
$result = $discount->validateAndCalculate($orderAmount, $userId);
// Returns: ['success' => bool, 'message' => string, 'discount_amount' => float, 'final_amount' => float]

// Usage tracking
$discount->incrementUsage();
$discount->decrementUsage();

// Display helpers
$discount->getFormattedValue();  // "10%" or "$50,000"
$discount->getStatusText();      // "Active", "Expired", "Limit Reached", etc.
```

<a name="discount-types"></a>
## Discount Types

### 1. Percentage Discount

Applies a percentage reduction to the order amount.

```php
[
    'code' => 'SAVE20',
    'type' => DiscountTypeEnum::PERCENTAGE->value,
    'value' => 20,  // 20%
    'max_discount_amount' => 100000,  // Optional cap
]
```

**Calculation:**
- Discount = (Order Amount × Percentage) / 100
- If `max_discount_amount` is set, the discount is capped at that value
- Final discount cannot exceed the order amount

**Example:**
- Order Amount: 500,000
- Discount: 20%
- Calculated: 100,000
- With max cap of 50,000: Final discount = 50,000

### 2. Fixed Amount Discount

Applies a fixed monetary reduction to the order amount.

```php
[
    'code' => 'CASH50K',
    'type' => DiscountTypeEnum::AMOUNT->value,
    'value' => 50000,  // Fixed 50,000
]
```

**Calculation:**
- Discount = Fixed value
- Final discount cannot exceed the order amount

**Example:**
- Order Amount: 500,000
- Discount: 50,000
- Final Amount: 450,000

<a name="validation-rules"></a>
## Validation Rules

When applying a discount, the system validates ALL the following conditions:

### 1. Active Status
```php
$discount->is_active === true
```
**Error:** "This discount is currently inactive"

### 2. Date Validity

**Start Date:**
```php
// Discount must have started
$discount->starts_at === null || $discount->starts_at <= now()
```
**Error:** "This discount is not yet active"

**Expiration Date:**
```php
// Discount must not be expired
$discount->expires_at === null || $discount->expires_at >= now()
```
**Error:** "This discount has expired"

### 3. Usage Limits

**Global Usage:**
```php
// Total uses must not exceed limit
$discount->usage_limit === null || $discount->used_count < $discount->usage_limit
```
**Error:** "This discount has reached its usage limit"

**Per-User Usage:**
```php
// User's usage must not exceed per-user limit
$userUsageCount < $discount->usage_per_user
```
**Error:** "You have reached the usage limit for this discount"

### 4. Minimum Order Amount
```php
$orderAmount >= $discount->min_order_amount
```
**Error:** "Order amount must be at least $X"

### 5. User Eligibility
```php
// If user-specific, must match the assigned user
$discount->user_id === null || $discount->user_id === $userId
```
**Error:** "This discount is not available for your account"

<a name="usage-examples"></a>
## Usage Examples

### Creating a Discount

#### Using the Action (Recommended)
```php
use App\Actions\Discount\StoreDiscountAction;

$discount = StoreDiscountAction::run([
    'code' => 'SUMMER25',
    'type' => DiscountTypeEnum::PERCENTAGE->value,
    'value' => 25,
    'min_order_amount' => 100000,
    'max_discount_amount' => 50000,
    'usage_limit' => 100,
    'usage_per_user' => 3,
    'starts_at' => now(),
    'expires_at' => now()->addDays(30),
    'is_active' => true,
    'description' => 'Summer sale 25% off',
]);
```

#### Direct Model Creation
```php
use App\Models\Discount;

$discount = Discount::create([
    'code' => 'WELCOME10',
    'type' => DiscountTypeEnum::PERCENTAGE->value,
    'value' => 10,
    'min_order_amount' => 0,
    'usage_per_user' => 1,
    'is_active' => true,
]);
```

### Applying a Discount

#### Simple Application
```php
$discount = Discount::byCode('SAVE10')->first();
$orderAmount = 500000;
$userId = 1;

if ($discount && $discount->canBeApplied($orderAmount, $userId)) {
    $discountAmount = $discount->calculateDiscount($orderAmount);
    $finalAmount = $orderAmount - $discountAmount;
}
```

#### With Full Validation
```php
$discount = Discount::byCode('SAVE20')->first();

if (!$discount) {
    throw new Exception('Invalid discount code');
}

$result = $discount->validateAndCalculate($orderAmount, $userId);

if ($result['success']) {
    // Apply discount
    $discountAmount = $result['discount_amount'];
    $finalAmount = $result['final_amount'];
    
    // Store in order
    $order->discount_id = $discount->id;
    $order->discount_amount = $discountAmount;
    $order->save();
    
    // Increment usage count
    $discount->incrementUsage();
} else {
    // Show error message
    return $result['message'];
}
```

### User-Specific Discounts

```php
// Create a discount for specific user
$discount = StoreDiscountAction::run([
    'code' => 'VIP50',
    'type' => DiscountTypeEnum::PERCENTAGE->value,
    'value' => 50,
    'user_id' => 1,  // Only for user #1
    'usage_per_user' => 1,
    'is_active' => true,
]);

// Check if user can use it
$canUse = $discount->canBeApplied($orderAmount, $userId);
```

### Querying Discounts

```php
// Get all valid discounts for a user
$discounts = Discount::valid()
    ->forUser($userId)
    ->get();

// Get active percentage discounts
$discounts = Discount::active()
    ->percentage()
    ->get();

// Find valid discount by code
$discount = Discount::valid()
    ->byCode('SAVE10')
    ->first();
```

<a name="actions"></a>
## Actions

### StoreDiscountAction

Creates a new discount with automatic code normalization and validation.

```php
use App\Actions\Discount\StoreDiscountAction;

$discount = StoreDiscountAction::run([
    'code' => 'save10',  // Automatically converted to 'SAVE10'
    'type' => 'percentage',
    'value' => 10,
    'is_active' => true,
]);
```

**Features:**
- Automatic code uppercase conversion
- Initializes `used_count` to 0
- Transaction-wrapped for data integrity
- Returns refreshed model instance

### UpdateDiscountAction

Updates an existing discount.

```php
use App\Actions\Discount\UpdateDiscountAction;

$discount = UpdateDiscountAction::run($discount, [
    'value' => 15,  // Change from 10% to 15%
    'expires_at' => now()->addDays(60),
]);
```

### DeleteDiscountAction

Soft or hard deletes a discount.

```php
use App\Actions\Discount\DeleteDiscountAction;

DeleteDiscountAction::run($discount);
```

<a name="admin-panel-integration"></a>
## Admin Panel Integration

### Order Creation with Discount

The `OrderUpdateOrCreate` Livewire component provides discount application:

```php
// In your Livewire component
public string $discount_code = '';
public float $discount_amount = 0;
public ?int $discount_id = null;

public function applyDiscount(): void
{
    $discount = Discount::where('code', trim($this->discount_code))->first();
    
    if (!$discount) {
        $this->error('Invalid discount code');
        return;
    }
    
    $result = $discount->validateAndCalculate($this->course->price, $this->user_id);
    
    if ($result['success']) {
        $this->discount_amount = $result['discount_amount'];
        $this->discount_id = $discount->id;
        $this->success("Discount applied: " . number_format($result['discount_amount']));
    } else {
        $this->error($result['message']);
    }
}
```

### Blade Template

```blade
<x-input 
    label="Discount Code" 
    wire:model="discount_code"
>
    <x-slot:append>
        <x-button 
            label="Apply" 
            wire:click="applyDiscount" 
            class="btn-primary"
        />
    </x-slot:append>
</x-input>

<div>
    Discount Amount: {{ StringHelper::toCurrency($discount_amount) }}
</div>
```

<a name="api-reference"></a>
## API Reference

### Model Methods

#### `calculateDiscount(float $orderAmount): float`
Calculates the discount amount for a given order amount.

**Parameters:**
- `$orderAmount` - The order total amount

**Returns:** Calculated discount amount (never exceeds order amount)

**Example:**
```php
$discount = Discount::find(1);
$amount = $discount->calculateDiscount(500000);
```

---

#### `canBeApplied(float $orderAmount, int $userId): bool`
Validates if the discount can be applied to an order.

**Parameters:**
- `$orderAmount` - The order total amount
- `$userId` - The user attempting to use the discount

**Returns:** `true` if all validation rules pass, `false` otherwise

**Example:**
```php
if ($discount->canBeApplied(500000, 1)) {
    // Apply discount
}
```

---

#### `validateAndCalculate(float $orderAmount, int $userId): array`
Validates and calculates discount in one operation.

**Parameters:**
- `$orderAmount` - The order total amount
- `$userId` - The user ID

**Returns:**
```php
[
    'success' => bool,           // Whether discount can be applied
    'message' => string,         // Success or error message
    'discount_amount' => float,  // Calculated discount amount
    'final_amount' => float,     // Order amount after discount
]
```

**Example:**
```php
$result = $discount->validateAndCalculate(500000, 1);

if ($result['success']) {
    echo "Discount: {$result['discount_amount']}";
    echo "Final: {$result['final_amount']}";
} else {
    echo "Error: {$result['message']}";
}
```

---

#### `incrementUsage(): void`
Increments the usage count by 1.

**Example:**
```php
// After applying discount to order
$discount->incrementUsage();
```

---

#### `decrementUsage(): void`
Decrements the usage count by 1 (never goes below 0).

**Example:**
```php
// If order is cancelled
$discount->decrementUsage();
```

---

### Query Scopes

#### `active()`
Filters to active discounts only.

```php
Discount::active()->get();
```

---

#### `valid()`
Filters to valid discounts (active and within date range).

```php
Discount::valid()->get();
```

---

#### `forUser(int $userId)`
Filters to discounts available for a specific user.

```php
Discount::forUser(1)->get();
```

---

#### `byCode(string $code)`
Finds discount by code.

```php
Discount::byCode('SAVE10')->first();
```

---

#### `percentage()`
Filters to percentage discounts only.

```php
Discount::percentage()->get();
```

---

#### `fixedAmount()`
Filters to fixed amount discounts only.

```php
Discount::fixedAmount()->get();
```

---

## Common Patterns

### Pattern 1: Apply Discount to Order

```php
use App\Models\{Discount, Order};

$order = Order::find(1);
$discount = Discount::byCode('SAVE10')->first();

if ($discount) {
    $result = $discount->validateAndCalculate($order->total_amount, $order->user_id);
    
    if ($result['success']) {
        $order->discount_id = $discount->id;
        $order->discount_amount = $result['discount_amount'];
        $order->save();
        
        $discount->incrementUsage();
    }
}
```

### Pattern 2: List Available Discounts for User

```php
$userId = auth()->id();
$orderAmount = 500000;

$availableDiscounts = Discount::valid()
    ->forUser($userId)
    ->get()
    ->filter(fn($d) => $d->canBeApplied($orderAmount, $userId));
```

### Pattern 3: Create Limited-Time Sale

```php
use App\Actions\Discount\StoreDiscountAction;

$discount = StoreDiscountAction::run([
    'code' => 'FLASH50',
    'type' => DiscountTypeEnum::PERCENTAGE->value,
    'value' => 50,
    'usage_limit' => 100,
    'usage_per_user' => 1,
    'starts_at' => now(),
    'expires_at' => now()->addHours(24),
    'is_active' => true,
    'description' => '24-hour flash sale',
]);
```

### Pattern 4: First-Time User Discount

```php
$discount = StoreDiscountAction::run([
    'code' => 'FIRST10',
    'type' => DiscountTypeEnum::PERCENTAGE->value,
    'value' => 10,
    'usage_per_user' => 1,
    'is_active' => true,
    'description' => 'First purchase discount',
]);

// Check if user has never used this discount
$hasUsed = $discount->orders()
    ->where('user_id', $userId)
    ->exists();

if (!$hasUsed) {
    // Apply discount
}
```

### Pattern 5: VIP User Discount

```php
// Create user-specific VIP discount
$discount = StoreDiscountAction::run([
    'code' => 'VIP-' . $user->id,
    'type' => DiscountTypeEnum::PERCENTAGE->value,
    'value' => 25,
    'user_id' => $user->id,
    'usage_per_user' => 999,
    'expires_at' => now()->addYear(),
    'is_active' => true,
    'description' => 'VIP member exclusive discount',
]);
```

---

## Testing

### Seeding Test Data

Run the comprehensive discount seeder:

```bash
php artisan db:seed --class=DiscountSeeder
```

This creates 18 test discounts covering all scenarios:
- 9 percentage discounts
- 9 fixed amount discounts
- 4 user-specific (User #1)
- Various states: active, expired, scheduled, inactive, limit reached

### Testing Validation

```php
use Tests\TestCase;
use App\Models\{Discount, User};

class DiscountTest extends TestCase
{
    public function test_discount_validates_minimum_order(): void
    {
        $discount = Discount::factory()->create([
            'min_order_amount' => 100000,
        ]);
        
        $canApply = $discount->canBeApplied(50000, 1);
        
        $this->assertFalse($canApply);
    }
    
    public function test_discount_applies_correctly(): void
    {
        $discount = Discount::factory()->create([
            'type' => 'percentage',
            'value' => 10,
        ]);
        
        $amount = $discount->calculateDiscount(100000);
        
        $this->assertEquals(10000, $amount);
    }
}
```

---

## Error Messages

All error messages are translatable and stored in `lang/fa/order.php`:

| Key | Message (Persian) |
|-----|-------------------|
| `discount_code_required` | لطفا کد تخفیف را وارد کنید |
| `please_select_user_and_course` | لطفا ابتدا کاربر و دوره را انتخاب کنید |
| `discount_not_found` | کد تخفیف وارد شده معتبر نیست |
| `course_has_no_price` | دوره انتخابی قیمت ندارد |
| `discount_applied_successfully` | کد تخفیف با موفقیت اعمال شد. مبلغ تخفیف: :amount تومان |

---

## Best Practices

1. **Always validate before applying**: Use `validateAndCalculate()` instead of just `calculateDiscount()`

2. **Track usage**: Call `incrementUsage()` after successful order creation

3. **Handle cancellations**: Call `decrementUsage()` if order is cancelled

4. **Use uppercase codes**: Codes are automatically converted to uppercase for consistency

5. **Set reasonable limits**: Always set `usage_per_user` to prevent abuse

6. **Test thoroughly**: Use the seeder to test all validation scenarios

7. **Log activity**: The model automatically logs all changes via activity log

8. **Handle edge cases**: Check for null values and edge conditions

---

## Troubleshooting

### Discount not applying

1. Check if discount is active: `$discount->is_active`
2. Verify dates: `$discount->starts_at` and `$discount->expires_at`
3. Check usage limits: `$discount->hasReachedLimit()`
4. Verify minimum order: `$orderAmount >= $discount->min_order_amount`
5. Check user eligibility: `$discount->user_id === null || $discount->user_id === $userId`

### Discount amount incorrect

1. For percentage: Check if `max_discount_amount` is set
2. Verify order amount is correct
3. Check discount type matches expected type

### Usage count not updating

1. Ensure you're calling `incrementUsage()` after order creation
2. Check database connection
3. Verify no transaction rollbacks

---

## Migration Reference

```php
Schema::create('discounts', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->enum('type', ['percentage', 'amount']);
    $table->decimal('value', 10, 2);
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->decimal('min_order_amount', 10, 2)->default(0);
    $table->decimal('max_discount_amount', 10, 2)->nullable();
    $table->integer('usage_limit')->nullable();
    $table->integer('usage_per_user')->default(1);
    $table->integer('used_count')->default(0);
    $table->timestamp('starts_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->boolean('is_active')->default(true);
    $table->text('description')->nullable();
    $table->timestamps();
});
```
