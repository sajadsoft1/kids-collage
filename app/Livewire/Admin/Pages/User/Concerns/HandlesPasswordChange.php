<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\On;
use Mary\Traits\Toast;

trait HandlesPasswordChange
{
    use Toast;

    public bool $showChangePasswordModal = false;

    public ?User $passwordUser = null;

    public string $password = '';

    public string $password_confirmation = '';

    #[On('change-password-modal')]
    public function showChangePasswordModal(int $rowId): void
    {
        $user = $this->datasource()->getModel()::query()->find($rowId);

        if ($user === null) {
            $this->error('کاربر مورد نظر یافت نشد.');

            return;
        }

        $this->passwordUser            = $user;
        $this->password                = '';
        $this->password_confirmation   = '';
        $this->showChangePasswordModal = true;

        $this->resetValidation();
    }

    public function closeChangePasswordModal(): void
    {
        $this->reset([
            'showChangePasswordModal',
            'passwordUser',
            'password',
            'password_confirmation',
        ]);

        $this->resetValidation();
    }

    public function updatePassword(): void
    {
        if ($this->passwordUser === null) {
            $this->error('کاربری برای بروزرسانی انتخاب نشده است.');

            return;
        }

        $this->validate(
            $this->passwordRules(),
            [],
            $this->validationAttributes()
        );

        $this->passwordUser->forceFill([
            'password' => Hash::make($this->password),
        ])->save();

        $this->success(
            title: 'رمز عبور بروزرسانی شد',
            description: 'رمز عبور جدید با موفقیت برای کاربر ذخیره شد.'
        );

        $this->closeChangePasswordModal();
    }

    protected function passwordRules(): array
    {
        return [
            'password'              => ['required', 'confirmed', Password::min(8)],
            'password_confirmation' => ['required'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'password'              => trans('validation.attributes.password'),
            'password_confirmation' => trans('validation.attributes.password_confirmation'),
        ];
    }

    public function getPasswordUserNameProperty(): string
    {
        if ($this->passwordUser === null) {
            return '';
        }

        $fullName = trim(($this->passwordUser->name ?? '') . ' ' . ($this->passwordUser->family ?? ''));

        return $fullName !== ''
            ? $fullName
            : ($this->passwordUser->email ?? trans('user.model'));
    }
}
