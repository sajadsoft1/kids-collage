<?php

declare(strict_types=1);

namespace App\Livewire\Web\Auth;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class ConfirmPage extends Component
{
    use Toast;

    public string $email      = '';
    public string $code       = '';
    public bool $codeVerified = false;
    public ?User $user        = null;

    protected function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'code'  => 'required|string|size:6',
        ];
    }

    protected function messages(): array
    {
        return [
            'email.required' => trans('auth.email_required'),
            'email.email'    => trans('auth.email_invalid'),
            'email.exists'   => trans('auth.email_not_found'),
            'code.required'  => trans('auth.code_required'),
            'code.size'      => trans('auth.code_size'),
        ];
    }

    /** Verify confirmation code */
    public function verifyCode(): void
    {
        $this->validate();

        $this->user = User::where('email', $this->email)->first();

        if ( ! $this->user) {
            $this->addError('email', trans('auth.user_not_found'));

            return;
        }

        // For demo purposes, we'll use a simple code verification
        // In a real application, you would verify against a stored code
        if ($this->code === '123456') {
            $this->user->update(['email_verified_at' => now()]);
            $this->codeVerified = true;
            $this->success(trans('auth.email_verified_successfully'));
        } else {
            $this->addError('code', trans('auth.invalid_code'));
        }
    }

    /** Resend confirmation code */
    public function resendCode(): void
    {
        $this->validate(['email' => 'required|email|exists:users,email']);

        // In a real application, you would generate and send a new code
        $this->success(trans('auth.code_resent'));
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
        return view('livewire.web.auth.confirm-page')
            ->layout('livewire.web.auth.auth');
    }
}
