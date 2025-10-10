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

A custom Exception Handler is defined in `bootstrap/app.php` that:

- ✅ Detects all Livewire errors (by checking the `X-Livewire` header)
- ✅ Logs the error for debugging
- ✅ Returns a standard JSON response

```php
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->renderable(function (Throwable $e, $request) {
        if ($request->header('X-Livewire')) {
            Log::error('Livewire Error: ' . $e->getMessage());
            
            return response()->json([
                'message' => config('app.debug') 
                    ? $e->getMessage() 
                    : __('An error occurred. Please try again.'),
            ], 500);
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
                // Display error toast
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
position: 'toast-top toast-center', // or 'toast-bottom toast-end'
```

### Change Display Duration

Change the toast display duration:

```javascript
timeout: 5000, // in milliseconds (5 seconds)
```

---

<a name="debug-mode"></a>
## Debug Mode

### Development Environment (`APP_DEBUG=true`)
- ✅ Detailed error message is displayed
- ✅ Exception class name is shown
- ✅ Error is logged to console

### Production Environment (`APP_DEBUG=false`)
- ✅ Generic message "An error occurred" is displayed
- ✅ Error details are logged to file

> {warning} In production, error details are not displayed to users.

---

<a name="troubleshooting"></a>
## Troubleshooting

If toast is not displaying:

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

