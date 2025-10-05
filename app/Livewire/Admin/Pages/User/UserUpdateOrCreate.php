<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User;

use App\Actions\User\StoreUserAction;
use App\Actions\User\UpdateUserAction;
use App\Enums\GenderEnum;
use App\Enums\ReligionEnum;
use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

class UserUpdateOrCreate extends Component
{
    use Toast,WithFileUploads;

    public User $user;
    public $avatar;
    public $national_card;
    public $birth_certificate;
    public ?string $name                  = '';
    public ?string $family                = '';
    public ?string $email                 = '';
    public ?string $mobile                = '';
    public ?string $birth_date            = '';
    public ?string $national_code         = '';
    public ?string $gender                = GenderEnum::MALE->value;
    public ?string $address               = '';
    public ?string $phone                 = '';
    public ?string $father_name           = '';
    public ?string $father_phone          = '';
    public ?string $mother_name           = '';
    public ?string $mother_phone          = '';
    public ?string $religion              = ReligionEnum::ISLAM->value;
    public bool $status                   = true;
    public array $rules                   = [];
    public array $selected_rules          = [];

    public function mount(User $user): void
    {
        $this->user  = $user;
        $this->rules = Role::all()->map(fn ($item) => ['name' => $item->name, 'id' => $item->id])->toArray();
        if ($this->user->id) {
            $this->name           = $this->user->name;
            $this->family         = $this->user->family;
            $this->email          = $this->user->email;
            $this->mobile         = $this->user->mobile;
            $this->status         = (bool) $this->user->status->value;
            $this->gender         = $this->user->profile->gender->value;
            $this->birth_date     = $this->user->profile->birth_date;
            $this->national_code  = $this->user->profile->national_code;
            $this->address        = $this->user->profile->address;
            $this->phone          = $this->user->profile->phone;
            $this->father_name    = $this->user->profile->father_name;
            $this->father_phone   = $this->user->profile->father_phone;
            $this->mother_name    = $this->user->profile->mother_name;
            $this->mother_phone   = $this->user->profile->mother_phone;
            $this->religion       = $this->user->profile->religion->value;
            $this->selected_rules = $this->user->roles->pluck('id')->toArray();
        }
    }

    /**
     * Get the current user type based on the route
     *
     * Usage examples:
     * - /admin/parent/edit/1 -> returns UserTypeEnum::PARENT
     * - /admin/employee/create -> returns UserTypeEnum::EMPLOYEE
     * - /admin/user/create -> returns null (regular user)
     */
    public function getCurrentUserType(): UserTypeEnum
    {
        $routeName = request()->route()->getName();

        return match ($routeName) {
            str_starts_with($routeName, 'admin.parent.')   => UserTypeEnum::PARENT,
            str_starts_with($routeName, 'admin.employee.') => UserTypeEnum::EMPLOYEE,
            default                                        => UserTypeEnum::USER, // Regular user or admin.user routes
        };
    }

    /** Get the appropriate route name for the current user type */
    public function getRoutePrefix(): string
    {
        $routeName = request()->route()->getName();

        return match ($routeName) {
            str_starts_with($routeName, 'admin.parent.')   => 'admin.parent',
            str_starts_with($routeName, 'admin.employee.') => 'admin.employee',
            default                                        => 'admin.user',
        };
    }

    protected function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'family'            => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $this->user->id,
            'mobile'            => [
                'required',
                'regex:/^(0|\+98|98)9[0-9]{9}$/',  // ✅ Now includes `/` delimiters
                'unique:users,mobile,' . $this->user->id,
            ],
            'status'            => 'required',
            'birth_date'        => [
                $this->user->id ? 'nullable' : 'required',
                'min:8',
                'confirmed',
            ],
            'selected_rules'    => 'nullable|array',
            'selected_rules.*'  => 'exists:roles,id',
            'avatar'            => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048', // 2MB Max
            ],
            'national_card'     => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048', // 2MB Max
            ],
            'birth_certificate' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048', // 2MB Max
            ],
            'national_code'     => 'nullable|string|max:255',
            'gender'            => 'nullable|string|in:' . implode(',', GenderEnum::values()),
            'religion'          => 'nullable|string|in:' . implode(',', ReligionEnum::values()),
            'address'           => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:255',
            'father_name'       => 'nullable|string|max:255',
            'father_phone'      => 'nullable|string|max:255',
            'mother_name'       => 'nullable|string|max:255',
            'mother_phone'      => 'nullable|string|max:255',
        ];
    }

    public function submit(): void
    {
        $payload          = $this->validate();
        $payload['rules'] = $payload['selected_rules'];

        // Set user type based on current route
        $userType = $this->getCurrentUserType();
        if ($userType) {
            $payload['type'] = $userType->value;
        }

        $routePrefix   = $this->getRoutePrefix();
        $userTypeLabel = $this->getCurrentUserType()->title();

        if ($this->user->id) {
            UpdateUserAction::run($this->user, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => $userTypeLabel]),
                redirectTo: route($routePrefix . '.index')
            );
        } else {
            $payload['profile']['id_number'] = now()->format('Y') . now()->format('m') . rand(10000, 99999);
            $payload['profile']['password']  = Hash::make($this->mobile);
            StoreUserAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => $userTypeLabel]),
                redirectTo: route($routePrefix . '.index')
            );
        }
    }

    public function render(): View
    {
        $routePrefix   = $this->getRoutePrefix();
        $userTypeLabel = $this->getCurrentUserType()->title();
        $isEditMode    = $this->user->id;

        return view('livewire.admin.pages.user.user-update-or-create', [
            'edit_mode'          => $isEditMode,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route($routePrefix . '.index'), 'label' => trans('general.page.index.title', ['model' => $userTypeLabel])],
                ['label' => $isEditMode
                    ? trans('general.page.edit.title', ['model' => $userTypeLabel])
                    : trans('general.page.create.title', ['model' => $userTypeLabel]),
                ],
            ],
            'breadcrumbsActions' => [
                ['link' => route($routePrefix . '.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
