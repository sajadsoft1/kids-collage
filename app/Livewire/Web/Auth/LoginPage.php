<?php

declare(strict_types=1);

namespace App\Livewire\Web\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class LoginPage extends Component
{
    use Toast;

    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
        ];
    }

    protected function messages(): array
    {
        return [
            'email.required' => trans('auth.email_required'),
            'email.email' => trans('auth.email_invalid'),
            'email.exists' => trans('auth.email_not_found'),
            'password.required' => trans('auth.password_required'),
            'password.min' => trans('auth.password_min'),
        ];
    }

    /** Handle user login with email and password */
    public function login(): void
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->success(trans('auth.login_successful'));
            $this->redirect(localized_route('home-page'), navigate: true);
        } else {
            $this->addError('email', trans('auth.invalid_credentials'));
        }
    }

    /** Redirect to Google OAuth */
    public function loginWithGoogle(): void
    {
        $this->redirect(localized_route('google.redirect'), navigate: true);
    }

    /** Navigate to register page */
    public function goToRegister(): void
    {
        $this->redirect(localized_route('register'), navigate: true);
    }

    /** Navigate to forget password page */
    public function goToForgetPassword(): void
    {
        $this->redirect(localized_route('password.request'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.web.auth.login-page')
            ->layout('livewire.web.auth.auth');
    }
}
