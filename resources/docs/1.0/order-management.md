# Order Management

---

* [Overview](#overview)
* [Features](#features)
* [Order Lifecycle](#order-lifecycle)
* [Creating Orders](#creating-orders)
* [Managing Orders](#managing-orders)
* [Order Status](#order-status)
* [Order Items](#order-items)
* [Examples](#examples)
* [Troubleshooting](#troubleshooting)

---

<a name="overview"></a>
## Overview

The Order Management module handles all purchase transactions for course enrollments, providing complete order tracking, payment processing, and financial reporting.

> {success} Complete order lifecycle management from cart to fulfillment

> {primary} Integration with payment gateways and enrollment system

---

<a name="features"></a>
## Features

The Order Management module includes:

- **Shopping Cart** - Flexible cart management
- **Order Creation** - Automated order generation
- **Payment Integration** - Support for multiple payment gateways
- **Order Tracking** - Real-time order status updates
- **Discount Application** - Automatic discount calculation
- **Invoicing** - Generate invoices and receipts
- **Refund Management** - Handle refunds and cancellations
- **Order History** - Complete purchase history
- **Financial Reports** - Revenue and sales analytics

> {info} Orders are automatically created when students enroll in paid courses

---

<a name="order-lifecycle"></a>
## Order Lifecycle

The order workflow:

1. **Cart Creation** - User adds courses to cart
2. **Discount Application** - Apply discount codes
3. **Order Generation** - Create order from cart
4. **Payment Processing** - Process payment through gateway
5. **Order Confirmation** - Confirm successful payment
6. **Enrollment Creation** - Automatically enroll student in courses
7. **Invoice Generation** - Generate invoice/receipt
8. **Order Completion** - Mark order as completed

> {primary} Failed payments keep orders in pending status for retry

---

<a name="creating-orders"></a>
## Creating Orders

### Basic Order Creation

```php
use App\Actions\Order\CreateOrderAction;
use App\Models\User;
use App\Models\Course;

$user = User::find($userId);
$courses = Course::whereIn('id', $courseIds)->get();

$orderData = [
    'user_id' => $user->id,
    'items' => $courses->map(function ($course) {
        return [
            'orderable_type' => Course::class,
            'orderable_id' => $course->id,
            'quantity' => 1,
            'price' => $course->price,
        ];
    })->toArray(),
    'subtotal' => $courses->sum('price'),
    'tax' => 0,
    'total' => $courses->sum('price'),
    'status' => 'pending',
];

$order = app(CreateOrderAction::class)->execute($orderData);
```

### Order with Discount

```php
use App\Actions\Order\CreateOrderAction;
use App\Models\Discount;

$discount = Discount::where('code', $discountCode)
    ->where('is_active', true)
    ->first();

if ($discount && $discount->isValid()) {
    $subtotal = $courses->sum('price');
    $discountAmount = $discount->calculateDiscount($subtotal);
    
    $orderData = [
        'user_id' => $user->id,
        'discount_id' => $discount->id,
        'discount_code' => $discount->code,
        'discount_amount' => $discountAmount,
        'subtotal' => $subtotal,
        'tax' => 0,
        'total' => $subtotal - $discountAmount,
        'status' => 'pending',
    ];
    
    $order = app(CreateOrderAction::class)->execute($orderData);
}
```

### Bulk Order Creation

```php
use App\Actions\Order\CreateOrderAction;

$students = User::whereIn('id', $studentIds)->get();
$course = Course::find($courseId);

foreach ($students as $student) {
    $orderData = [
        'user_id' => $student->id,
        'items' => [[
            'orderable_type' => Course::class,
            'orderable_id' => $course->id,
            'quantity' => 1,
            'price' => $course->price,
        ]],
        'subtotal' => $course->price,
        'total' => $course->price,
        'status' => 'pending',
    ];
    
    app(CreateOrderAction::class)->execute($orderData);
}
```

> {success} Orders generate unique order numbers automatically

---

<a name="managing-orders"></a>
## Managing Orders

### Updating Order Status

```php
use App\Actions\Order\UpdateOrderAction;
use App\Models\Order;

$order = Order::find($orderId);

// Mark as paid
app(UpdateOrderAction::class)->execute($order, [
    'status' => 'paid',
    'paid_at' => now(),
]);

// Cancel order
app(UpdateOrderAction::class)->execute($order, [
    'status' => 'cancelled',
    'cancelled_at' => now(),
    'cancellation_reason' => 'Customer request',
]);

// Refund order
app(UpdateOrderAction::class)->execute($order, [
    'status' => 'refunded',
    'refunded_at' => now(),
    'refund_amount' => $order->total,
]);
```

### Adding Items to Order

```php
use App\Models\Order;
use App\Models\Course;

$order = Order::find($orderId);
$course = Course::find($courseId);

// Only for pending orders
if ($order->status === 'pending') {
    $order->items()->create([
        'orderable_type' => Course::class,
        'orderable_id' => $course->id,
        'quantity' => 1,
        'price' => $course->price,
    ]);
    
    // Recalculate totals
    $order->update([
        'subtotal' => $order->items()->sum('price'),
        'total' => $order->items()->sum('price'),
    ]);
}
```

### Removing Items from Order

```php
$order = Order::find($orderId);

if ($order->status === 'pending') {
    $order->items()
        ->where('orderable_id', $courseId)
        ->where('orderable_type', Course::class)
        ->delete();
    
    // Recalculate totals
    $order->recalculateTotals();
}
```

---

<a name="order-status"></a>
## Order Status

Available order statuses:

- **pending** - Order created, awaiting payment
- **processing** - Payment received, processing order
- **paid** - Payment confirmed, order completed
- **failed** - Payment failed
- **cancelled** - Order cancelled by user or admin
- **refunded** - Payment refunded
- **partial_refund** - Partially refunded

### Status Transitions

```php
use App\Models\Order;

$order = Order::find($orderId);

// Check if status transition is valid
$canCancel = $order->canBeCancelled();  // Only pending/processing orders
$canRefund = $order->canBeRefunded();   // Only paid orders
$isPaid = $order->isPaid();             // Status is 'paid'
$isPending = $order->isPending();       // Status is 'pending'
```

### Status Change Notifications

```php
use App\Notifications\OrderStatusChanged;

$order = Order::find($orderId);

// Update status
$order->update(['status' => 'paid']);

// Notify user
$order->user->notify(new OrderStatusChanged($order));
```

> {warning} Status changes may trigger enrollment creation or cancellation

---

<a name="order-items"></a>
## Order Items

### Retrieving Order Items

```php
use App\Models\Order;

$order = Order::with('items.orderable')->find($orderId);

foreach ($order->items as $item) {
    echo "Item: {$item->orderable->name}\n";
    echo "Price: {$item->price}\n";
    echo "Quantity: {$item->quantity}\n";
}
```

### Order Item Calculations

```php
$order = Order::find($orderId);

// Get order summary
$summary = [
    'items_count' => $order->items()->count(),
    'subtotal' => $order->subtotal,
    'discount' => $order->discount_amount,
    'tax' => $order->tax,
    'total' => $order->total,
];

// Calculate average item price
$averagePrice = $order->items()->avg('price');
```

---

<a name="examples"></a>
## Examples

### Example 1: Complete Order Flow

```php
use App\Actions\Order\CreateOrderAction;
use App\Models\User;
use App\Models\Course;
use App\Models\Payment;

// 1. User selects courses
$user = User::find($userId);
$courses = Course::whereIn('id', [1, 2, 3])->get();

// 2. Create order
$orderData = [
    'user_id' => $user->id,
    'items' => $courses->map(fn($course) => [
        'orderable_type' => Course::class,
        'orderable_id' => $course->id,
        'quantity' => 1,
        'price' => $course->price,
    ])->toArray(),
    'subtotal' => $courses->sum('price'),
    'total' => $courses->sum('price'),
    'status' => 'pending',
];

$order = app(CreateOrderAction::class)->execute($orderData);

// 3. Process payment
$payment = Payment::create([
    'order_id' => $order->id,
    'user_id' => $user->id,
    'amount' => $order->total,
    'status' => 'pending',
    'gateway' => 'stripe',
]);

// 4. After successful payment
$payment->update(['status' => 'completed']);
$order->update([
    'status' => 'paid',
    'paid_at' => now(),
]);

// 5. Create enrollments
foreach ($order->items as $item) {
    if ($item->orderable_type === Course::class) {
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $item->orderable_id,
            'order_id' => $order->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);
    }
}

// 6. Send confirmation
$user->notify(new OrderConfirmedNotification($order));
```

### Example 2: Cart to Order Conversion

```php
use App\Models\Cart;
use App\Actions\Order\CreateOrderAction;

$cart = Cart::where('user_id', $userId)->first();

if ($cart && $cart->items()->count() > 0) {
    $orderData = [
        'user_id' => $cart->user_id,
        'items' => $cart->items->map(function ($cartItem) {
            return [
                'orderable_type' => $cartItem->cartable_type,
                'orderable_id' => $cartItem->cartable_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
            ];
        })->toArray(),
        'subtotal' => $cart->subtotal,
        'total' => $cart->total,
        'discount_id' => $cart->discount_id,
        'discount_amount' => $cart->discount_amount,
        'status' => 'pending',
    ];
    
    $order = app(CreateOrderAction::class)->execute($orderData);
    
    // Clear cart
    $cart->items()->delete();
    $cart->delete();
}
```

### Example 3: Order Report

```php
use App\Models\Order;
use Carbon\Carbon;

$startDate = Carbon::now()->startOfMonth();
$endDate = Carbon::now()->endOfMonth();

$report = [
    'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
    'total_revenue' => Order::where('status', 'paid')
        ->whereBetween('paid_at', [$startDate, $endDate])
        ->sum('total'),
    'pending_orders' => Order::where('status', 'pending')->count(),
    'cancelled_orders' => Order::where('status', 'cancelled')
        ->whereBetween('cancelled_at', [$startDate, $endDate])
        ->count(),
    'refunded_amount' => Order::whereIn('status', ['refunded', 'partial_refund'])
        ->whereBetween('refunded_at', [$startDate, $endDate])
        ->sum('refund_amount'),
];

// Best selling courses
$bestSelling = Order::where('status', 'paid')
    ->with('items.orderable')
    ->whereBetween('paid_at', [$startDate, $endDate])
    ->get()
    ->pluck('items')
    ->flatten()
    ->groupBy('orderable_id')
    ->map(function ($items) {
        return [
            'course' => $items->first()->orderable->name,
            'quantity' => $items->sum('quantity'),
            'revenue' => $items->sum(function ($item) {
                return $item->price * $item->quantity;
            }),
        ];
    })
    ->sortByDesc('revenue')
    ->take(10);
```

### Example 4: Refund Processing

```php
use App\Models\Order;
use App\Models\Payment;

$order = Order::with('payments', 'enrollments')->find($orderId);

if ($order->canBeRefunded()) {
    // Calculate refund amount (can be partial)
    $refundAmount = $order->total;
    $refundPercentage = 100;
    
    // Check refund policy based on enrollment start date
    foreach ($order->enrollments as $enrollment) {
        $daysUntilStart = now()->diffInDays($enrollment->course->start_date, false);
        
        if ($daysUntilStart < 0) {
            // Course already started - no refund
            $refundPercentage = 0;
        } elseif ($daysUntilStart < 7) {
            // Less than 7 days - 50% refund
            $refundPercentage = min($refundPercentage, 50);
        }
    }
    
    $refundAmount = ($order->total * $refundPercentage) / 100;
    
    // Process refund through payment gateway
    $payment = $order->payments()->where('status', 'completed')->first();
    
    if ($payment) {
        // Refund through gateway
        $payment->refund($refundAmount);
    }
    
    // Update order
    $order->update([
        'status' => $refundPercentage === 100 ? 'refunded' : 'partial_refund',
        'refund_amount' => $refundAmount,
        'refunded_at' => now(),
    ]);
    
    // Cancel enrollments
    foreach ($order->enrollments as $enrollment) {
        $enrollment->update(['status' => 'cancelled']);
    }
    
    // Notify user
    $order->user->notify(new OrderRefundedNotification($order, $refundAmount));
}
```

---

<a name="troubleshooting"></a>
## Troubleshooting

### Order Stuck in Pending

**Problem:** Order remains in pending status after payment.

**Solution:**
```php
$order = Order::with('payments')->find($orderId);

// Check payment status
foreach ($order->payments as $payment) {
    echo "Payment #{$payment->id}: {$payment->status}\n";
}

// If payment is completed but order is pending
if ($order->payments()->where('status', 'completed')->exists()) {
    $order->update([
        'status' => 'paid',
        'paid_at' => now(),
    ]);
}
```

### Duplicate Order Creation

**Problem:** Multiple orders created for same cart.

**Solution:**
```php
use Illuminate\Support\Facades\DB;

// Use database transaction
DB::transaction(function () use ($orderData) {
    // Check for existing pending order
    $existing = Order::where('user_id', $orderData['user_id'])
        ->where('status', 'pending')
        ->where('created_at', '>', now()->subMinutes(10))
        ->first();
    
    if ($existing) {
        return $existing;
    }
    
    return app(CreateOrderAction::class)->execute($orderData);
});
```

### Order Total Mismatch

**Problem:** Order total doesn't match item prices.

**Solution:**
```php
$order = Order::with('items')->find($orderId);

// Recalculate totals
$subtotal = $order->items->sum(function ($item) {
    return $item->price * $item->quantity;
});

$total = $subtotal - ($order->discount_amount ?? 0) + ($order->tax ?? 0);

if ($order->total !== $total) {
    $order->update([
        'subtotal' => $subtotal,
        'total' => $total,
    ]);
}
```

### Missing Order Invoice

**Problem:** Invoice not generated for completed order.

**Solution:**
```php
use App\Services\InvoiceService;

$order = Order::find($orderId);

if ($order->isPaid() && !$order->invoice_number) {
    // Generate invoice
    $invoiceNumber = InvoiceService::generateInvoiceNumber();
    
    $order->update([
        'invoice_number' => $invoiceNumber,
        'invoice_generated_at' => now(),
    ]);
    
    // Generate PDF
    $pdf = InvoiceService::generatePDF($order);
    
    // Send to user
    $order->user->notify(new InvoiceGeneratedNotification($order, $pdf));
}
```

> {danger} Always use database transactions for order operations

---

## Integration with Other Modules

### Payment Management
Orders are linked to payments:

```php
$order = Order::with('payments')->find($orderId);
$totalPaid = $order->payments()->where('status', 'completed')->sum('amount');
```

### Enrollment Management
Paid orders automatically create enrollments:

```php
$order = Order::with('enrollments')->find($orderId);

foreach ($order->enrollments as $enrollment) {
    echo "Enrolled in: {$enrollment->course->name}\n";
}
```

### Discount Management
Apply discounts to orders:

```php
$discount = Discount::where('code', $code)->first();

if ($discount && $discount->isValidForOrder($order)) {
    $discountAmount = $discount->calculateDiscount($order->subtotal);
    $order->applyDiscount($discount, $discountAmount);
}
```

---

## Permissions

Order management requires appropriate permissions:

- `order.index` - View orders list
- `order.create` - Create new orders
- `order.edit` - Edit pending orders
- `order.delete` - Cancel orders
- `order.refund` - Process refunds
- `order.report` - View financial reports

> {info} Users can view and manage their own orders without special permissions

