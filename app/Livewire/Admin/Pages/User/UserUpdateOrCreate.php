<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User;

use App\Actions\User\StoreUserAction;
use App\Actions\User\UpdateUserAction;
use App\Enums\GenderEnum;
use App\Enums\ReligionEnum;
use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Traits\CrudHelperTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Random\RandomException;
use Spatie\Permission\Models\Role;
use Throwable;

class UserUpdateOrCreate extends Component
{
    use CrudHelperTrait, Toast, WithFileUploads;

    public User $user;
    public $avatar;
    public $national_card;
    public $birth_certificate;
    public ?string $name                         = '';
    public ?string $family                       = '';
    public ?string $email                        = '';
    public ?string $mobile                       = '';
    public ?Carbon $birth_date                   = null;
    public ?string $national_code                = '';
    public ?string $gender                       = GenderEnum::MALE->value;
    public ?string $address                      = '';
    public ?string $phone                        = '';
    public ?string $father_name                  = '';
    public ?string $father_phone                 = '';
    public ?string $mother_name                  = '';
    public ?string $mother_phone                 = '';
    public ?int $father_id                       = null;
    public ?int $mother_id                       = null;
    public array $children_id                    = [];
    public ?float $salary                        = 0;
    public ?float $benefit                       = 0;
    public ?Carbon $cooperation_start_date       = null;
    public ?Carbon $cooperation_end_date         = null;
    public ?string $religion                     = ReligionEnum::ISLAM->value;
    public bool $status                          = true;
    public array $rules                          = [];
    public array $selected_rules                 = [];
    public UserTypeEnum $detected_user_type      = UserTypeEnum::USER;
    public string $detected_route_name           = 'admin.user';

    public function mount(User $user): void
    {
        $this->getCurrentUserType();
        $this->getRoutePrefix();

        $this->user  = $user;
        $this->rules = Role::all()->map(fn ($item) => ['name' => $item->name, 'id' => $item->id])->toArray();
        if ($this->user->id) {
            $this->name                   = $this->user->name;
            $this->family                 = $this->user->family;
            $this->email                  = $this->user->email;
            $this->mobile                 = $this->user->mobile;
            $this->status                 = (bool) $this->user->status->value;
            $this->gender                 = $this->user->profile?->gender?->value;
            $this->birth_date             = $this->user->profile?->birth_date;
            $this->national_code          = $this->user->profile?->national_code;
            $this->address                = $this->user->profile?->address;
            $this->phone                  = $this->user->profile?->phone;
            $this->father_name            = $this->user->profile?->father_name;
            $this->father_phone           = $this->user->profile?->father_phone;
            $this->mother_name            = $this->user->profile?->mother_name;
            $this->mother_phone           = $this->user->profile?->mother_phone;
            $this->religion               = $this->user->profile?->religion?->value;
            $this->salary                 = $this->user->profile?->salary;
            $this->benefit                = $this->user->profile?->benefit;
            $this->cooperation_start_date = $this->user->profile?->cooperation_start_date;
            $this->cooperation_end_date   = $this->user->profile?->cooperation_end_date;

            if ($this->detected_user_type === UserTypeEnum::USER) {
                $this->father_id = $this->user->father()?->id;
                $this->mother_id = $this->user->mother()?->id;
            } elseif ($this->detected_user_type === UserTypeEnum::PARENT) {
                $this->children_id = $this->user->children()->pluck('id')->toArray();
            }

            $this->selected_rules = $this->user->roles->pluck('id')->toArray();
        }
    }

    /**
     * Get the current user type based on the route
     *
     * Usage examples:
     * - /admin/parent/edit/1 → returns UserTypeEnum::PARENT.
     * — /admin/employee/create → returns UserTypeEnum::EMPLOYEE.
     * — /admin/user/create → returns null (regular user).
     */
    public function getCurrentUserType(): UserTypeEnum
    {
        $routeName                = request()->route()?->getName();
        $this->detected_user_type = match (true) {
            str_starts_with($routeName, 'admin.parent.')   => UserTypeEnum::PARENT,
            str_starts_with($routeName, 'admin.employee.') => UserTypeEnum::EMPLOYEE,
            str_starts_with($routeName, 'admin.teacher.')  => UserTypeEnum::TEACHER,
            default                                        => UserTypeEnum::USER, // Regular user or admin.user routes
        };

        return $this->detected_user_type;
    }

    /** Get the appropriate route name for the current user type */
    public function getRoutePrefix(): string
    {
        $routeName                 = request()->route()?->getName();
        $this->detected_route_name = match (true) {
            str_starts_with($routeName, 'admin.parent.')   => 'admin.parent',
            str_starts_with($routeName, 'admin.employee.') => 'admin.employee',
            str_starts_with($routeName, 'admin.teacher.')  => 'admin.teacher',
            default                                        => 'admin.user',
        };

        return $this->detected_route_name;
    }

    protected function rules(): array
    {
        return [
            'name'                   => 'required|string|max:255',
            'family'                 => 'required|string|max:255',
            'email'                  => 'required|email|unique:users,email,' . $this->user->id,
            'mobile'                 => [
                'required',
                'regex:/^(0|\+98|98)9[0-9]{9}$/',  // ✅ Now includes `/` delimiters
                'unique:users,mobile,' . $this->user->id,
            ],
            'status'                 => 'required',
            'birth_date'             => 'required|date',
            'selected_rules'         => 'nullable|array',
            'selected_rules.*'       => 'exists:roles,id',
            'avatar'                 => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048', // 2MB Max
            ],
            'national_card'          => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048', // 2MB Max
            ],
            'birth_certificate'      => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048', // 2MB Max
            ],
            'national_code'          => 'nullable|string|max:255',
            'gender'                 => 'nullable|string|in:' . implode(',', GenderEnum::values()),
            'religion'               => 'nullable|string|in:' . implode(',', ReligionEnum::values()),
            'address'                => 'nullable|string|max:255',
            'phone'                  => 'nullable|string|max:255',
            'father_name'            => 'nullable|string|max:255',
            'father_phone'           => 'nullable|string|max:255',
            'mother_name'            => 'nullable|string|max:255',
            'mother_phone'           => 'nullable|string|max:255',
            'salary'                 => 'nullable|numeric|min:0',
            'benefit'                => 'nullable|numeric|min:0',
            'cooperation_start_date' => 'nullable|date',
            'cooperation_end_date'   => 'nullable|date|after_or_equal:cooperation_start_date',
            'father_id'              => 'nullable|exists:users,id',
            'mother_id'              => 'nullable|exists:users,id',
            'children_id'            => 'nullable|array',
            'children_id.*'          => 'exists:users,id',
        ];
    }

    /** @throws RandomException */
    public function submit(): void
    {
        $payload          = $this->validate();
        $payload['rules'] = $payload['selected_rules'];
        $payload['type']  = $this->detected_user_type->value;

        if ($this->user->id) {
            try {
                UpdateUserAction::run($this->user, $payload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => $this->detected_user_type->title()]),
                    redirectTo: route($this->detected_route_name . '.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            $payload['id_number'] = now()->format('Y') . now()->format('m') . random_int(10000, 99999);
            $payload['password']  = Hash::make($this->mobile);

            try {
                StoreUserAction::run($payload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => $this->detected_user_type->title()]),
                    redirectTo: route($this->detected_route_name . '.index')
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.user.user-update-or-create', [
            'edit_mode'          => $this->user->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route($this->detected_route_name . '.index'), 'label' => trans('general.page.index.title', ['model' => $this->detected_user_type->title()])],
                ['label' => $this->user->id
                    ? trans('general.page.edit.title', ['model' => $this->detected_user_type->title(), 'name' => $this->user->full_name])
                    : trans('general.page.create.title', ['model' => $this->detected_user_type->title()]),
                ],
            ],
            'breadcrumbsActions' => [
                ['link' => route($this->detected_route_name . '.index'), 'icon' => 's-arrow-left'],
            ],
            'male_parents'       => User::query()->where('type', UserTypeEnum::PARENT->value)
                ->whereHas('profile', fn ($q) => $q->where('gender', GenderEnum::MALE->value))
                ->get()->map(function (User $user) {
                    return [
                        'label' => $user->full_name . ' (' . $user->mobile . ')',
                        'value' => $user->id,
                    ];
                }),
            'female_parents'     => User::query()->where('type', UserTypeEnum::PARENT->value)
                ->whereHas('profile', fn ($q) => $q->where('gender', GenderEnum::FEMALE->value))
                ->get()->map(function (User $user) {
                    return [
                        'label' => $user->full_name . ' (' . $user->mobile . ')',
                        'value' => $user->id,
                    ];
                }),
            'childrens'          => User::query()->where('type', UserTypeEnum::USER->value)->get()->map(function (User $user) {
                return [
                    'label' => $user->full_name . ' (' . $user->mobile . ')',
                    'value' => $user->id,
                ];
            })->toArray(),
        ]);
    }
}
