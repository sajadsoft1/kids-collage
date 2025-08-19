# Complete Authentication System

A comprehensive authentication system built with Laravel, Livewire, and Mary UI components. This system includes login, registration, password reset, email verification, and social authentication with Google.

## Features

### 🔐 Core Authentication
- **User Login**: Email/password authentication with remember me functionality
- **User Registration**: Complete registration with email verification
- **Password Reset**: Secure password reset via email
- **Email Verification**: Account activation with verification codes
- **Social Authentication**: Google OAuth integration

### 🎨 User Interface
- **Modern Design**: Beautiful, responsive UI with Tailwind CSS and DaisyUI
- **Dark Mode Support**: Full dark mode compatibility
- **Mobile Responsive**: Optimized for all device sizes
- **Livewire Navigation**: Seamless page transitions using wire:navigate
- **Toast Notifications**: User-friendly success/error messages

### 🌐 Internationalization
- **Multi-language Support**: English and Persian translations
- **RTL Support**: Right-to-left layout for Persian language
- **Localized Routes**: URL localization for different languages

## File Structure

```
app/
├── Livewire/Web/Auth/
│   ├── LoginPage.php
│   ├── RegisterPage.php
│   ├── ForgetPasswordPage.php
│   └── ConfirmPage.php
├── Http/Controllers/Auth/
│   └── GoogleController.php
└── Http/Middleware/
    └── RedirectIfAuthenticated.php

resources/views/
├── livewire/web/auth/
│   ├── login-page.blade.php
│   ├── register-page.blade.php
│   ├── forget-password-page.blade.php
│   └── confirm-page.blade.php
└── components/layouts/
    └── auth.blade.php

lang/
├── en/auth.php
└── fa/auth.php

routes/
└── web.php
```

## Routes

| Route | Method | Description |
|-------|--------|-------------|
| `/login` | GET | Login page |
| `/register` | GET | Registration page |
| `/forget-password` | GET | Password reset page |
| `/confirm` | GET | Email verification page |
| `/auth/google/redirect` | GET | Google OAuth redirect |
| `/auth/google/callback` | GET | Google OAuth callback |
| `/logout` | POST | User logout |

## Components

### LoginPage
- Email/password authentication
- Remember me functionality
- Google OAuth integration
- Navigation to register and forgot password pages
- Form validation with custom error messages

### RegisterPage
- Complete user registration form
- Password confirmation
- Terms and conditions acceptance
- Google OAuth registration
- Auto-login after successful registration

### ForgetPasswordPage
- Email-based password reset
- Success state management
- Navigation back to login
- Form validation

### ConfirmPage
- Email verification with 6-digit codes
- Demo code support (123456)
- Resend code functionality
- Account activation

## Usage

### Basic Authentication

1. **Login**: Navigate to `/login` and enter credentials
2. **Register**: Navigate to `/register` and fill the form
3. **Password Reset**: Navigate to `/forget-password` and enter email
4. **Email Verification**: Navigate to `/confirm` and enter verification code

### Social Authentication

1. Click "Continue with Google" on login or register pages
2. User will be redirected to Google OAuth
3. After authorization, user will be logged in automatically
4. New users will have accounts created automatically

### Navigation

All pages use Livewire's `wire:navigate` for seamless transitions:
- Login → Register
- Login → Forget Password
- Register → Login
- Forget Password → Login/Register
- Confirm → Login/Register

## Configuration

### Google OAuth Setup

1. Create a Google OAuth application in Google Cloud Console
2. Add credentials to `.env`:
```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://your-domain/auth/google/callback
```

3. Configure Socialite in `config/services.php`:
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

### Email Configuration

Configure your email settings in `.env` for password reset and verification emails:
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Customization

### Styling
- Modify Tailwind classes in view files
- Update color schemes in `resources/css/app.css`
- Customize DaisyUI theme in `tailwind.config.js`

### Translations
- Add new languages in `lang/` directory
- Update translation keys in `lang/{locale}/auth.php`
- Add new validation messages as needed

### Components
- Extend Livewire components for additional functionality
- Add new social providers in `GoogleController.php`
- Customize validation rules in component classes

## Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **Rate Limiting**: Built-in Laravel rate limiting
- **Password Hashing**: Secure password hashing with bcrypt
- **Session Management**: Secure session handling
- **Input Validation**: Comprehensive form validation
- **SQL Injection Protection**: Eloquent ORM protection

## Browser Support

- **Modern Browsers**: Chrome, Firefox, Safari, Edge (latest versions)
- **Mobile Browsers**: iOS Safari, Chrome Mobile, Samsung Internet
- **JavaScript Required**: Livewire requires JavaScript

## Dependencies

- **Laravel**: Latest stable version
- **Livewire**: For reactive components
- **Mary UI**: For UI components
- **Tailwind CSS**: For styling
- **DaisyUI**: For component library
- **Laravel Socialite**: For social authentication

## Installation

1. Ensure all dependencies are installed:
```bash
composer install
npm install
```

2. Run migrations:
```bash
php artisan migrate
```

3. Configure environment variables
4. Set up Google OAuth credentials
5. Configure email settings

## Testing

The system includes demo functionality for testing:
- **Demo Verification Code**: Use `123456` for email verification
- **Auto-login**: Users are automatically logged in after registration
- **Toast Notifications**: Success/error messages for all actions

## Support

For issues or questions:
1. Check the Laravel documentation
2. Review Livewire documentation
3. Check Mary UI documentation
4. Verify Google OAuth configuration

## License

This authentication system is part of the main project and follows the same license terms.
