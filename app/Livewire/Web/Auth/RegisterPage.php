<?php

declare(strict_types=1);

namespace App\Livewire\Web\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class RegisterPage extends Component
{
    use Toast;

    public string $name                  = '';
    public string $family                = '';
    public string $email                 = '';
    public string $password              = '';
    public string $password_confirmation = '';
    public bool $terms_accepted          = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'family' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
            'terms_accepted' => 'required|accepted',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => trans('auth.name_required'),
            'name.min' => trans('auth.name_min'),
            'family.required' => trans('auth.family_required'),
            'family.min' => trans('auth.family_min'),
            'email.required' => trans('auth.email_required'),
            'email.email' => trans('auth.email_invalid'),
            'email.unique' => trans('auth.email_already_exists'),
            'password.required' => trans('auth.password_required'),
            'password.min' => trans('auth.password_min'),
            'password.confirmed' => trans('auth.password_confirmation_mismatch'),
            'password_confirmation.required' => trans('auth.password_confirmation_required'),
            'terms_accepted.required' => trans('auth.terms_required'),
            'terms_accepted.accepted' => trans('auth.terms_must_be_accepted'),
        ];
    }

    /** Handle user registration */
    public function register(): void
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'family' => $this->family,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'status' => true,
        ]);

        // Auto login after registration
        Auth::login($user);

        $this->success(trans('auth.registration_successful'));
        $this->redirect(localized_route('home-page'), navigate: true);
    }

    /** Redirect to Google OAuth for registration */
    public function registerWithGoogle(): void
    {
        $this->redirect(localized_route('google.redirect'), navigate: true);
    }

    /** Navigate to login page */
    public function goToLogin(): void
    {
        $this->redirect(localized_route('login'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.web.auth.register-page')
            ->layout('livewire.web.auth.auth');
    }
}
