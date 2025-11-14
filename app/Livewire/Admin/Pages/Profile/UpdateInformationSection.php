<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Profile;

use App\Actions\User\UpdateUserAction;
use App\Enums\GenderEnum;
use App\Enums\ReligionEnum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

/**
 * Component for updating user information section
 *
 * @property string      $name
 * @property string      $family
 * @property string|null $email
 * @property string|null $mobile
 * @property string|null $national_code
 * @property Carbon|null $birth_date
 * @property string|null $gender
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $father_name
 * @property string|null $father_phone
 * @property string|null $mother_name
 * @property string|null $mother_phone
 * @property string|null $religion
 */
class UpdateInformationSection extends Component
{
    use Toast;

    public User $user;

    public ?string $name = '';

    public ?string $family = '';

    public ?string $email = null;

    public ?string $mobile = null;

    public ?string $national_code = null;

    public ?Carbon $birth_date = null;

    public ?string $gender = null;

    public ?string $address = null;

    public ?string $phone = null;

    public ?string $religion = null;

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

        $this->loadUserData();
    }

    /** Load user data into form properties */
    public function loadUserData(): void
    {
        $this->name = $this->user->name;
        $this->family = $this->user->family;
        $this->email = $this->user->email;
        $this->mobile = $this->user->mobile;

        if ($this->user->profile) {
            $this->national_code = $this->user->profile->national_code;
            $this->birth_date = $this->user->profile->birth_date;
            $this->gender = $this->user->profile->gender?->value;
            $this->address = $this->user->profile->address;
            $this->phone = $this->user->profile->phone;
            $this->religion = $this->user->profile->religion?->value;
        }
    }

    /** Get validation rules */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $this->user->id,
            'mobile' => [
                'nullable',
                'regex:/^(0|\+98|98)9[0-9]{9}$/',
                'unique:users,mobile,' . $this->user->id,
            ],
            'national_code' => 'nullable|string|max:10|regex:/^[0-9]{10}$/',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:' . implode(',', GenderEnum::values()),
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:11|regex:/^0[0-9]{10}$/',
            'religion' => 'nullable|string|in:' . implode(',', ReligionEnum::values()),
        ];
    }

    /** Update user information */
    public function save(): void
    {
        $payload = $this->validate();

        try {
            UpdateUserAction::run($this->user, $payload);

            $this->success(
                title: __('general.update_success', ['model' => __('user.model')]),
                timeout: 3000
            );

            // Reload user data
            $this->user->refresh();
            $this->loadUserData();
        } catch (Throwable $e) {
            $this->error(
                title: __('general.update_failed', ['model' => __('user.model')]),
                description: $e->getMessage(),
                timeout: 5000
            );

            logger()->error('Failed to update user information', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /** Get gender options for select */
    public function getGenderOptionsProperty(): array
    {
        return collect(GenderEnum::cases())->map(fn (GenderEnum $gender) => [
            'value' => $gender->value,
            'label' => $gender->title(),
        ])->toArray();
    }

    /** Get religion options for select */
    public function getReligionOptionsProperty(): array
    {
        return collect(ReligionEnum::cases())->map(fn (ReligionEnum $religion) => [
            'value' => $religion->value,
            'label' => $religion->title(),
        ])->toArray();
    }

    /** Render the component */
    public function render(): View
    {
        return view('livewire.admin.pages.profile.update-information-section');
    }
}
