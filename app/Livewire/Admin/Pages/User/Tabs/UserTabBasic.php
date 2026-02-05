<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User\Tabs;

use App\Actions\User\StoreUserBasicAction;
use App\Actions\User\UpdateUserBasicAction;
use App\Enums\GenderEnum;
use App\Enums\ReligionEnum;
use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Random\RandomException;

class UserTabBasic extends Component
{
    use CrudHelperTrait, Toast;

    public User $user;

    public string $detected_route_name = 'admin.user';

    public UserTypeEnum $detected_user_type;

    public ?string $name = '';

    public ?string $family = '';

    public ?string $email = '';

    public ?string $mobile = '';

    public bool $status = true;

    public ?string $gender = GenderEnum::MALE->value;

    public $birth_date = null;

    public ?string $national_code = '';

    public ?string $address = '';

    public ?string $biography = '';

    public ?string $sickness = '';

    public ?string $delivery_recipient = '';

    public ?string $phone = '';

    public ?string $religion = ReligionEnum::ISLAM->value;

    public function mount(?User $user, string $detected_route_name, UserTypeEnum $detected_user_type): void
    {
        $this->user = $user ?? new User;
        $this->detected_route_name = $detected_route_name;
        $this->detected_user_type = $detected_user_type;

        if ($this->user->id) {
            $this->name = $this->user->name;
            $this->family = $this->user->family;
            $this->email = $this->user->email;
            $this->mobile = $this->user->mobile;
            $this->status = (bool) $this->user->status->value;
            $this->gender = $this->user->profile?->gender?->value;
            $this->birth_date = $this->user->profile?->birth_date?->format('Y-m-d') ?? null;
            $this->national_code = $this->user->profile?->national_code;
            $this->address = $this->user->profile?->address;
            $this->biography = $this->user->profile?->extra_attributes->get('biography', '');
            $this->sickness = $this->user->profile?->extra_attributes->get('sickness', '');
            $this->delivery_recipient = $this->user->profile?->extra_attributes->get('delivery_recipient', '');
            $this->phone = $this->user->profile?->phone;
            $this->religion = $this->user->profile?->religion?->value;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $this->user->id,
            'mobile' => [
                'required',
                'regex:/^(0|\+98|98)9[0-9]{9}$/',
                'unique:users,mobile,' . $this->user->id,
            ],
            'status' => 'required|boolean',
            'birth_date' => 'nullable|date',
            'national_code' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:' . implode(',', GenderEnum::values()),
            'religion' => 'nullable|string|in:' . implode(',', ReligionEnum::values()),
            'address' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'sickness' => 'nullable|string',
            'delivery_recipient' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
        ];
    }

    /** @throws RandomException */
    public function submit(): void
    {
        $payload = $this->validate();
        $payload['type'] = $this->detected_user_type->value;

        if ( ! $this->user->id) {
            $payload['password'] = $this->mobile;
            $newUser = StoreUserBasicAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => $this->detected_user_type->title()]),
            );
        } else {
            UpdateUserBasicAction::run($this->user, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => $this->detected_user_type->title()]),
                redirectTo: route($this->detected_route_name . '.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.user.tabs.user-tab-basic');
    }
}
