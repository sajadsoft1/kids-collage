# Livewire Error Handling System

---

* [How It Works](#how-it-works)
* [Server Side](#server-side)
* [Client Side](#client-side)
* [Handled Error Types](#error-types)
* [Benefits](#benefits)
* [Usage](#usage)
* [Customization](#customization)
* [Debug Mode](#debug-mode)
* [Troubleshooting](#troubleshooting)
* [Important Notes](#important-notes)

---

<a name="how-it-works"></a>
## How It Works

This system automatically detects all 500 errors and other HTTP errors in Livewire and displays them to users as toast notifications.

The system consists of two main parts:
- **Exception Handler** on the server side (`bootstrap/app.php`)
- **Livewire Hook** on the client side (`resources/js/app.js`)

---

<a name="server-side"></a>
## Server Side (bootstrap/app.php)

Exception handling for Livewire requests is configured in `bootstrap/app.php` using `withExceptions`. The exception handler:

- ✅ Detects all Livewire errors (by checking the `X-Livewire` header)
- ✅ Determines the appropriate HTTP status code based on exception type
- ✅ Logs the error with detailed information for debugging
- ✅ Returns a properly formatted JSON response (not HTML)
- ✅ Handles custom error messages from `abort()` calls

**Required imports in bootstrap/app.php:**
```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
```

**Exception Handler in `bootstrap/app.php`:**
```php
->withExceptions(function (Exceptions $exceptions) {
    // Handle Livewire exceptions
    $exceptions->render(function (\Throwable $e, Request $request) {
        // Check if this is a Livewire request
        if ($request->is('livewire/*') && $e instanceof \Exception) {
            // Determine the appropriate status code
            $statusCode = 500;
            
            if ($e instanceof ValidationException) {
                $statusCode = 422;
            } elseif ($e instanceof HttpException) {
                $statusCode = $e->getStatusCode();
            } elseif ($e instanceof \InvalidArgumentException) {
                $statusCode = 400;
            }

            // Log the error for debugging
            Log::error('Livewire Error: '.$e->getMessage(), [
                'exception' => get_class($e),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'status'    => $statusCode,
                'url'       => $request->fullUrl(),
            ]);

            // Determine the message to show
            $message = $e->getMessage();
            
            if (!config('app.debug') && (empty($message) || $message === 'Server Error')) {
                $message = match ($statusCode) {
                    400     => __('درخواست نامعتبر است.'),
                    403     => __('شما اجازه انجام این عملیات را ندارید.'),
                    419     => __('نشست شما منقضی شده است.'),
                    422     => __('اطلاعات ارسالی نامعتبر است.'),
                    default => __('خطایی رخ داده است. لطفاً دوباره تلاش کنید.'),
                };
            }
            
            return response()->json([
                'message'   => $message,
                'exception' => config('app.debug') ? get_class($e) : null,
            ], $statusCode);
        }
    });
})
```

---

<a name="client-side"></a>
## Client Side (resources/js/app.js)

A Livewire Hook on the client side that:

- ✅ Hooks into Livewire's request lifecycle
- ✅ Detects and handles various errors
- ✅ Displays appropriate toast notifications for each error type

```javascript
document.addEventListener('livewire:init', () => {
    Livewire.hook('request', ({ fail }) => {
        fail(({ status, content, preventDefault }) => {
            if (status === 500) {
                preventDefault();
                
                // Parse error message
                let errorMessage = 'خطایی رخ داده است. لطفاً دوباره تلاش کنید.';
                try {
                    const data = JSON.parse(content);
                    if (data.message) {
                        errorMessage = data.message;
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }

                // Dispatch toast with correct MaryUI structure
                window.dispatchEvent(new CustomEvent('mary-toast', {
                    detail: {
                        toast: {
                            type: 'error',
                            title: 'خطا',
                            description: errorMessage,
                            css: 'alert-error',
                            icon: '...',
                            position: 'toast-top toast-center',
                            timeout: 5000,
                            redirectTo: null
                        }
                    }
                }));
            }
        });
    });
});
```

---

<a name="error-types"></a>
## Handled Error Types

### Error 500 (Internal Server Error)
- **Message:** Error text (in debug mode) or generic message
- **Toast Type:** `error`
- **Color:** Red

### Error 419 (CSRF Token Expired)
- **Message:** "Your session has expired. Please refresh the page."
- **Toast Type:** `warning`
- **Color:** Orange

### Error 403 (Forbidden)
- **Message:** "You don't have permission to perform this action."
- **Toast Type:** `warning`
- **Color:** Orange

### Error 400 (Bad Request)
- **Message:** Custom error message or "Invalid request"
- **Toast Type:** `error`
- **Color:** Red

**Note:** Custom messages from `abort()` are always displayed, even in production.

---

<a name="benefits"></a>
## Benefits

> {success} **Automatic:** No need for additional code in each component

> {primary} **Consistent:** All errors are displayed uniformly

> {info} **Customizable:** You can change messages and styles

> {success} **Detailed Logging:** All errors are logged for debugging

> {primary} **Better UX:** Toast notifications instead of error pages

---

<a name="usage"></a>
## Usage

This system is **automatically active** and requires no additional code. Just ensure:

1. You have run `npm run build` or `npm run dev`
2. Use regular code in your Livewire components
3. Errors will automatically display as toast notifications

### Practical Example

```php
// In a Livewire component
public function save(): void
{
    // If an error occurs, toast will be displayed automatically
    $this->validate();
    
    // If this line throws an exception, toast will be shown
    $this->model->save();
    
    $this->success('Operation completed successfully.');
}
```

### Using Custom Error Messages

You can use `abort()` or `abort_unless()` with custom messages:

```php
// Example 1: Using abort()
public function delete(): void
{
    abort(400, trans('errors.cannot_delete_active_item'));
}

// Example 2: Using abort_unless()
public function finish(): void
{
    abort_unless(
        $this->canFinish(), 
        400, 
        trans('course.exceptions.only_active_courses_can_be_finished')
    );
    
    $this->update(['status' => 'finished']);
}

// Example 3: Using abort_if()
public function archive(): void
{
    abort_if(
        $this->isArchived(), 
        400, 
        'این مورد قبلاً بایگانی شده است'
    );
}
```

> {success} **Custom messages from `abort()` are always displayed in toasts, even in production mode!**

---

<a name="customization"></a>
## Customization

### Change Error Messages

You can modify default messages in `resources/js/app.js`:

```javascript
let errorMessage = 'Your custom message';
```

### Change Toast Position

Modify the toast position in `resources/js/app.js`:

```javascript
toast: {
    position: 'toast-top toast-center', // or 'toast-bottom toast-end'
}
```

### Change Display Duration

Change the toast display duration:

```javascript
toast: {
    timeout: 5000, // in milliseconds (5 seconds)
}
```

### Important: MaryUI Toast Structure

The toast event **must** have the correct structure for MaryUI:

```javascript
window.dispatchEvent(new CustomEvent('mary-toast', {
    detail: {
        toast: {                    // Note: must be nested in 'toast' object
            type: 'error',          // error, warning, success, info
            title: 'Title',
            description: 'Message',
            css: 'alert-error',     // alert-error, alert-warning, etc
            icon: '<svg>...</svg>', // SVG icon
            position: 'toast-top toast-center',
            timeout: 5000,
            redirectTo: null
        }
    }
}));
```

---

<a name="debug-mode"></a>
## Debug Mode

### Development Environment (`APP_DEBUG=true`)
- ✅ All error messages are displayed (including stack traces)
- ✅ Exception class name is shown
- ✅ Error is logged to console

### Production Environment (`APP_DEBUG=false`)
- ✅ **Custom messages from `abort()` are displayed** (e.g., "Only active courses can be finished")
- ✅ Generic messages for system errors
- ✅ Error details are logged to file (not shown to users)

> {success} **Custom Messages:** Messages passed to `abort()`, `abort_if()`, or `abort_unless()` are **always displayed** to users, even in production mode.

> {warning} **System Errors:** Stack traces and internal error details are hidden in production for security.

---

<a name="troubleshooting"></a>
## Troubleshooting

### If toast is not displaying:

1. **Build Assets**
   ```bash
   npm run build
   # or
   npm run dev
   ```

2. **Check Console**
   - Check for JavaScript errors in the browser Console

3. **Check Logs**
   - Check server errors in `storage/logs/laravel.log`

4. **Check MaryUI**
   - Ensure the `<x-toast>` component exists in your layout

### If you see "Unexpected token '<', "<!DOCTYPE "... is not valid JSON"

This error means the server is returning HTML instead of JSON. This usually happens when `app/Exceptions/Handler.php` is rendering an HTML view for Livewire requests.

**Common Cause:**
The `Handler.php` might have code like this that returns HTML:
```php
if ($request->is('livewire/*') && $e instanceof Exception) {
    return response()->view('errors.error', ...);  // ❌ Returns HTML
}
```

**Solution:**
Replace it with JSON response in `app/Exceptions/Handler.php`:
```php
if ($request->is('livewire/*') && $e instanceof Exception) {
    $statusCode = 500;
    
    if ($e instanceof ValidationException) {
        $statusCode = 422;
    } elseif ($e instanceof HttpException) {
        $statusCode = $e->getStatusCode();
    } elseif ($e instanceof InvalidArgumentException) {
        $statusCode = 400;
    }

    $message = $e->getMessage();
    
    if (!config('app.debug') && (empty($message) || $message === 'Server Error')) {
        $message = match ($statusCode) {
            400     => __('درخواست نامعتبر است.'),
            403     => __('شما اجازه انجام این عملیات را ندارید.'),
            419     => __('نشست شما منقضی شده است.'),
            422     => __('اطلاعات ارسالی نامعتبر است.'),
            default => __('خطایی رخ داده است. لطفاً دوباره تلاش کنید.'),
        };
    }

    return response()->json([
        'message'   => $message,
        'exception' => config('app.debug') ? get_class($e) : null,
    ], $statusCode);  // ✅ Returns JSON
}
```

**Verify:**
1. Check Network tab in browser
2. Status should be 400/403/419/422/500 with JSON body
3. NOT a 200 status with HTML error page

### If custom error messages aren't showing:

If messages from `abort()` aren't displaying:

1. **Check the message condition:**
   ```php
   // In bootstrap/app.php
   if (!config('app.debug') && (empty($message) || $message === 'Server Error')) {
       // This should only run for generic errors
   }
   ```

2. **Verify abort usage:**
   ```php
   // Correct
   abort(400, 'Your custom message here');
   
   // Also correct
   abort_unless($condition, 400, trans('errors.your_key'));
   ```

---

<a name="important-notes"></a>
## Important Notes

> {danger} This system only works for **Livewire** requests

> {info} Regular HTTP requests will still show Laravel's error page

> {success} Errors are logged for debugging purposes

> {primary} You can add custom conditions in `bootstrap/app.php`

### Test Page

To test the system, visit this URL (only in debug mode):

```
http://your-domain.test/admin/test/error-handling
```

On this page you can:
- Simulate various 500 errors
- Test success, warning, and info messages
- See how the system works

