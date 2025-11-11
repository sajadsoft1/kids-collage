<?php

declare(strict_types=1);

namespace App\Livewire\Web\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class ForgetPasswordPage extends Component
{
    use Toast;

    public string $email   = '';
    public bool $emailSent = false;

    protected function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    protected function messages(): array
    {
        return [
            'email.required' => trans('auth.email_required'),
            'email.email' => trans('auth.email_invalid'),
            'email.exists' => trans('auth.email_not_found'),
        ];
    }

    /** Send password reset link */
    public function sendResetLink(): void
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->emailSent = true;
            $this->success(trans('auth.reset_link_sent'));
        } else {
            $this->addError('email', trans('auth.reset_link_error'));
        }
    }

    /** Navigate to login page */
    public function goToLogin(): void
    {
        $this->redirect(localized_route('login'), navigate: true);
    }

    /** Navigate to register page */
    public function goToRegister(): void
    {
        $this->redirect(localized_route('register'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.web.auth.forget-password-page')
            ->layout('livewire.web.auth.auth');
    }
}
