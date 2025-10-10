# Payment Management

---

* [Overview](#overview)
* [Features](#features)
* [Payment Gateways](#payment-gateways)
* [Processing Payments](#processing-payments)
* [Payment Status](#payment-status)
* [Refunds](#refunds)
* [Payment Methods](#payment-methods)
* [Examples](#examples)
* [Troubleshooting](#troubleshooting)

---

<a name="overview"></a>
## Overview

The Payment Management module handles all payment transactions, integrating with multiple payment gateways to process online payments securely.

> {success} Secure payment processing with PCI compliance

> {primary} Support for multiple payment gateways and methods

---

<a name="features"></a>
## Features

The Payment Management module includes:

- **Multiple Payment Gateways** - Stripe, PayPal, local gateways
- **Payment Methods** - Credit card, bank transfer, wallet
- **Secure Processing** - PCI-compliant payment handling
- **Transaction Tracking** - Complete payment history
- **Automatic Reconciliation** - Match payments to orders
- **Refund Processing** - Full and partial refunds
- **Payment Retry** - Automatic retry for failed payments
- **Webhook Handling** - Real-time payment notifications
- **Payment Reports** - Financial analytics and reporting

> {info} All payment data is encrypted and stored securely

---

<a name="payment-gateways"></a>
## Payment Gateways

### Supported Gateways

- **Zarinpal** - Iranian payment gateway
- **Stripe** - International credit card processing
- **PayPal** - International payment processor
- **Bank Transfer** - Direct bank transfers
- **Cash** - In-person cash payments

### Gateway Configuration

```php
// config/services.php

return [
    'zarinpal' => [
        'merchant_id' => env('ZARINPAL_MERCHANT_ID'),
        'callback_url' => env('ZARINPAL_CALLBACK_URL'),
        'sandbox' => env('ZARINPAL_SANDBOX', false),
    ],
    
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
    
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
        'mode' => env('PAYPAL_MODE', 'sandbox'),
    ],
];
```

> {warning} Never commit gateway credentials to version control

---

<a name="processing-payments"></a>
## Processing Payments

### Creating a Payment

```php
use App\Actions\Payment\CreatePaymentAction;
use App\Models\Order;

$order = Order::find($orderId);

$paymentData = [
    'order_id' => $order->id,
    'user_id' => $order->user_id,
    'amount' => $order->total,
    'currency' => 'IRR',
    'gateway' => 'zarinpal',
    'status' => 'pending',
];

$payment = app(CreatePaymentAction::class)->execute($paymentData);
```

### Initiating Payment with Gateway

```php
use App\Services\Payment\PaymentGatewayFactory;

$payment = Payment::find($paymentId);

$gateway = PaymentGatewayFactory::make($payment->gateway);

// Request payment
$response = $gateway->request([
    'amount' => $payment->amount,
    'description' => "Order #{$payment->order->order_number}",
    'callback_url' => route('payment.callback', ['payment' => $payment->id]),
]);

if ($response['success']) {
    $payment->update([
        'transaction_id' => $response['authority'],
        'gateway_response' => $response,
    ]);
    
    // Redirect user to gateway
    return redirect($response['payment_url']);
}
```

### Verifying Payment

```php
use App\Services\Payment\PaymentGatewayFactory;

public function callback(Request $request, Payment $payment)
{
    $gateway = PaymentGatewayFactory::make($payment->gateway);
    
    $result = $gateway->verify([
        'authority' => $request->Authority,
        'amount' => $payment->amount,
    ]);
    
    if ($result['success']) {
        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
            'reference_id' => $result['reference_id'],
            'gateway_response' => $result,
        ]);
        
        // Update order status
        $payment->order->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
        
        // Create enrollments
        $payment->order->createEnrollments();
        
        return redirect()->route('payment.success', ['payment' => $payment->id]);
    } else {
        $payment->update([
            'status' => 'failed',
            'gateway_response' => $result,
        ]);
        
        return redirect()->route('payment.failed', ['payment' => $payment->id]);
    }
}
```

---

<a name="payment-status"></a>
## Payment Status

Available payment statuses:

- **pending** - Payment initiated, awaiting confirmation
- **processing** - Payment being processed by gateway
- **completed** - Payment successful
- **failed** - Payment failed
- **cancelled** - Payment cancelled by user
- **refunded** - Payment refunded
- **partial_refund** - Partially refunded

### Status Checking

```php
use App\Models\Payment;

$payment = Payment::find($paymentId);

// Check payment status
if ($payment->isCompleted()) {
    // Payment successful
}

if ($payment->isPending()) {
    // Still processing
}

if ($payment->isFailed()) {
    // Payment failed
}
```

### Status Updates

```php
use App\Actions\Payment\UpdatePaymentAction;

$payment = Payment::find($paymentId);

// Mark as completed
app(UpdatePaymentAction::class)->execute($payment, [
    'status' => 'completed',
    'paid_at' => now(),
    'reference_id' => $referenceId,
]);

// Mark as failed
app(UpdatePaymentAction::class)->execute($payment, [
    'status' => 'failed',
    'failure_reason' => 'Insufficient funds',
]);
```

---

<a name="refunds"></a>
## Refunds

### Processing Full Refund

```php
use App\Services\Payment\PaymentGatewayFactory;

$payment = Payment::find($paymentId);

if ($payment->canBeRefunded()) {
    $gateway = PaymentGatewayFactory::make($payment->gateway);
    
    $result = $gateway->refund([
        'transaction_id' => $payment->transaction_id,
        'amount' => $payment->amount,
    ]);
    
    if ($result['success']) {
        $payment->update([
            'status' => 'refunded',
            'refunded_at' => now(),
            'refund_amount' => $payment->amount,
            'refund_transaction_id' => $result['refund_id'],
        ]);
        
        // Update order
        $payment->order->update([
            'status' => 'refunded',
            'refunded_at' => now(),
        ]);
    }
}
```

### Processing Partial Refund

```php
$payment = Payment::find($paymentId);
$refundAmount = 50000; // Partial amount

if ($payment->canBeRefunded()) {
    $gateway = PaymentGatewayFactory::make($payment->gateway);
    
    $result = $gateway->refund([
        'transaction_id' => $payment->transaction_id,
        'amount' => $refundAmount,
    ]);
    
    if ($result['success']) {
        $payment->update([
            'status' => 'partial_refund',
            'refunded_at' => now(),
            'refund_amount' => $refundAmount,
        ]);
    }
}
```

---

<a name="payment-methods"></a>
## Payment Methods

### Credit Card Payment

```php
use App\Services\Payment\StripeGateway;

$gateway = new StripeGateway();

$result = $gateway->charge([
    'amount' => 10000,
    'currency' => 'usd',
    'source' => $request->stripeToken,
    'description' => "Order #{$order->order_number}",
]);
```

### Bank Transfer

```php
$payment = Payment::create([
    'order_id' => $order->id,
    'user_id' => $order->user_id,
    'amount' => $order->total,
    'gateway' => 'bank_transfer',
    'status' => 'pending',
    'payment_method' => 'bank_transfer',
    'bank_details' => [
        'account_number' => 'IR123456789',
        'account_holder' => 'Company Name',
        'bank_name' => 'Melli Bank',
    ],
]);

// Manual verification required
```

### Cash Payment

```php
$payment = Payment::create([
    'order_id' => $order->id,
    'user_id' => $order->user_id,
    'amount' => $order->total,
    'gateway' => 'cash',
    'status' => 'pending',
    'payment_method' => 'cash',
]);

// Admin manually confirms payment
```

---

<a name="examples"></a>
## Examples

### Example 1: Complete Payment Flow

```php
use App\Actions\Payment\CreatePaymentAction;
use App\Services\Payment\PaymentGatewayFactory;

// 1. Create payment
$payment = app(CreatePaymentAction::class)->execute([
    'order_id' => $order->id,
    'user_id' => $user->id,
    'amount' => $order->total,
    'currency' => 'IRR',
    'gateway' => 'zarinpal',
    'status' => 'pending',
]);

// 2. Request payment from gateway
$gateway = PaymentGatewayFactory::make('zarinpal');
$response = $gateway->request([
    'amount' => $payment->amount,
    'description' => "Order #{$order->order_number}",
    'callback_url' => route('payment.callback', ['payment' => $payment->id]),
    'email' => $user->email,
    'mobile' => $user->phone,
]);

// 3. Redirect to gateway
if ($response['success']) {
    $payment->update([
        'transaction_id' => $response['authority'],
    ]);
    
    return redirect($response['payment_url']);
}

// 4. Handle callback (in separate method)
public function callback(Request $request, Payment $payment)
{
    $gateway = PaymentGatewayFactory::make($payment->gateway);
    
    $result = $gateway->verify([
        'authority' => $request->Authority,
        'amount' => $payment->amount,
    ]);
    
    if ($result['success']) {
        // Payment successful
        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
            'reference_id' => $result['reference_id'],
        ]);
        
        $payment->order->update(['status' => 'paid']);
        
        return view('payment.success', compact('payment'));
    }
}
```

### Example 2: Webhook Handling

```php
use App\Models\Payment;
use Illuminate\Http\Request;

public function webhook(Request $request)
{
    // Verify webhook signature
    $signature = $request->header('Stripe-Signature');
    
    try {
        $event = \Stripe\Webhook::constructEvent(
            $request->getContent(),
            $signature,
            config('services.stripe.webhook_secret')
        );
    } catch (\Exception $e) {
        return response()->json(['error' => 'Invalid signature'], 400);
    }
    
    // Handle event
    if ($event->type === 'payment_intent.succeeded') {
        $paymentIntent = $event->data->object;
        
        $payment = Payment::where('transaction_id', $paymentIntent->id)->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'reference_id' => $paymentIntent->id,
            ]);
            
            $payment->order->update(['status' => 'paid']);
        }
    }
    
    return response()->json(['success' => true]);
}
```

### Example 3: Payment Report

```php
use App\Models\Payment;
use Carbon\Carbon;

$startDate = Carbon::now()->startOfMonth();
$endDate = Carbon::now()->endOfMonth();

$report = [
    'total_payments' => Payment::where('status', 'completed')
        ->whereBetween('paid_at', [$startDate, $endDate])
        ->count(),
    
    'total_revenue' => Payment::where('status', 'completed')
        ->whereBetween('paid_at', [$startDate, $endDate])
        ->sum('amount'),
    
    'failed_payments' => Payment::where('status', 'failed')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count(),
    
    'refunded_amount' => Payment::whereIn('status', ['refunded', 'partial_refund'])
        ->whereBetween('refunded_at', [$startDate, $endDate])
        ->sum('refund_amount'),
    
    'by_gateway' => Payment::where('status', 'completed')
        ->whereBetween('paid_at', [$startDate, $endDate])
        ->groupBy('gateway')
        ->selectRaw('gateway, COUNT(*) as count, SUM(amount) as total')
        ->get(),
];
```

### Example 4: Failed Payment Retry

```php
use App\Models\Payment;
use App\Services\Payment\PaymentGatewayFactory;

// Find failed payments to retry
$failedPayments = Payment::where('status', 'failed')
    ->where('created_at', '>', now()->subDays(7))
    ->where('retry_count', '<', 3)
    ->get();

foreach ($failedPayments as $payment) {
    $gateway = PaymentGatewayFactory::make($payment->gateway);
    
    $response = $gateway->request([
        'amount' => $payment->amount,
        'description' => "Retry - Order #{$payment->order->order_number}",
        'callback_url' => route('payment.callback', ['payment' => $payment->id]),
    ]);
    
    if ($response['success']) {
        $payment->update([
            'status' => 'pending',
            'transaction_id' => $response['authority'],
            'retry_count' => $payment->retry_count + 1,
        ]);
        
        // Notify user
        $payment->user->notify(new PaymentRetryNotification($payment, $response['payment_url']));
    }
}
```

---

<a name="troubleshooting"></a>
## Troubleshooting

### Payment Verification Failed

**Problem:** Payment shows as completed on gateway but failed in system.

**Solution:**
```php
$payment = Payment::find($paymentId);

// Manually verify with gateway
$gateway = PaymentGatewayFactory::make($payment->gateway);

$result = $gateway->verify([
    'authority' => $payment->transaction_id,
    'amount' => $payment->amount,
]);

if ($result['success']) {
    $payment->update([
        'status' => 'completed',
        'paid_at' => now(),
        'reference_id' => $result['reference_id'],
    ]);
}
```

### Duplicate Payments

**Problem:** Multiple payment records for same order.

**Solution:**
```php
use Illuminate\Support\Facades\DB;

// Find duplicates
$duplicates = Payment::select('order_id')
    ->where('status', 'completed')
    ->groupBy('order_id')
    ->havingRaw('COUNT(*) > 1')
    ->get();

foreach ($duplicates as $duplicate) {
    $payments = Payment::where('order_id', $duplicate->order_id)
        ->where('status', 'completed')
        ->orderBy('paid_at')
        ->get();
    
    // Keep first payment, refund others
    $payments->slice(1)->each(function ($payment) {
        // Process refund
        $payment->refund();
    });
}
```

> {warning} Add unique constraint on `(order_id, status)` where status = 'completed'

### Gateway Timeout

**Problem:** Payment gateway not responding.

**Solution:**
```php
use Illuminate\Support\Facades\Http;

try {
    $response = Http::timeout(30)
        ->post($gatewayUrl, $paymentData);
    
    if ($response->successful()) {
        // Process response
    }
} catch (\Illuminate\Http\Client\ConnectionException $e) {
    // Gateway timeout
    $payment->update([
        'status' => 'failed',
        'failure_reason' => 'Gateway timeout',
    ]);
    
    // Queue for retry
    ProcessPaymentJob::dispatch($payment)->delay(now()->addMinutes(5));
}
```

### Refund Not Processing

**Problem:** Refund request failing on gateway.

**Solution:**
```php
$payment = Payment::find($paymentId);

// Check if payment can be refunded
if (!$payment->canBeRefunded()) {
    throw new \Exception('Payment cannot be refunded');
}

// Check gateway refund policy
$daysSincePayment = now()->diffInDays($payment->paid_at);

if ($daysSincePayment > 90) {
    throw new \Exception('Refund period expired');
}

// Manual refund if automated fails
$payment->update([
    'status' => 'refunded',
    'refunded_at' => now(),
    'refund_amount' => $payment->amount,
    'refund_method' => 'manual',
]);
```

> {danger} Always validate payment status before processing refunds

---

## Security Best Practices

### PCI Compliance

- Never store credit card numbers
- Use tokenization for card data
- Implement SSL/TLS encryption
- Regular security audits

### Fraud Prevention

```php
// Check for suspicious activity
$recentPayments = Payment::where('user_id', $userId)
    ->where('created_at', '>', now()->subHour())
    ->count();

if ($recentPayments > 3) {
    // Possible fraud - block payment
    throw new \Exception('Too many payment attempts');
}

// Verify payment amount
if ($payment->amount !== $order->total) {
    throw new \Exception('Payment amount mismatch');
}
```

---

## Permissions

Payment management requires appropriate permissions:

- `payment.index` - View payments list
- `payment.create` - Process payments
- `payment.verify` - Verify payments manually
- `payment.refund` - Process refunds
- `payment.report` - View financial reports

> {info} Users can only view their own payment history

