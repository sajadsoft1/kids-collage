<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Profile;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

/**
 * Component for updating user password
 *
 * @property string $current_password
 * @property string $password
 * @property string $password_confirmation
 */
class UpdatePasswordSection extends Component
{
    use Toast;

    public User $user;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    /** Mount the component */
    public function mount(?User $user = null): void
    {
        if ($user?->id) {
            $this->user = $user;
        } else {
            $authUser = auth()->user();
            if ( ! $authUser instanceof User) {
                abort(401, 'Unauthorized');
            }
            $this->user = $authUser;
        }
    }

    /** Get validation rules */
    protected function rules(): array
    {
        return [
            'current_password'      => 'required|current_password',
            'password'              => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[@$!%*#?&])/',
            ],
            'password_confirmation' => 'required|string',
        ];
    }

    /** Get validation messages */
    protected function messages(): array
    {
        return [
            'current_password.required'         => 'وارد کردن رمز عبور فعلی الزامی است',
            'current_password.current_password' => 'رمز عبور فعلی صحیح نیست',
            'password.required'                 => 'وارد کردن رمز عبور جدید الزامی است',
            'password.min'                      => 'رمز عبور باید حداقل 8 کاراکتر باشد',
            'password.confirmed'                => 'تایید رمز عبور مطابقت ندارد',
            'password.regex'                    => 'رمز عبور باید شامل حروف، اعداد و حداقل یک کاراکتر خاص (@$!%*#?&) باشد',
            'password_confirmation.required'    => 'تایید رمز عبور الزامی است',
        ];
    }

    /** Update user password */
    public function save(): void
    {
        $this->validate();

        try {
            $this->user->update([
                'password' => Hash::make($this->password),
            ]);

            $this->success(
                title: 'رمز عبور با موفقیت به‌روزرسانی شد',
                description: 'رمز عبور شما با موفقیت تغییر کرد',
                timeout: 3000
            );

            // Reset form
            $this->reset(['current_password', 'password', 'password_confirmation']);
        } catch (Throwable $e) {
            $this->error(
                title: 'خطا در به‌روزرسانی رمز عبور',
                description: $e->getMessage(),
                timeout: 5000
            );

            logger()->error('Failed to update user password', [
                'user_id' => $this->user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /** Render the component */
    public function render(): View
    {
        return view('livewire.admin.pages.profile.update-password-section');
    }
}
