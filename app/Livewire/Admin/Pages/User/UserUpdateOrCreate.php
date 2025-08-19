<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User;

use App\Actions\User\StoreUserAction;
use App\Actions\User\UpdateUserAction;
use App\Models\User;
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
    public ?string $name                  = '';
    public ?string $family                = '';
    public ?string $email                 = '';
    public ?string $mobile                = '';
    public ?string $password              = '';
    public ?string $password_confirmation = '';
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
            $this->selected_rules = $this->user->roles->pluck('id')->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'name'             => 'required|string|max:255',
            'family'           => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $this->user->id,
            'mobile'           => [
                'required',
                'regex:/^(0|\+98|98)9[0-9]{9}$/',  // ✅ Now includes `/` delimiters
                'unique:users,mobile,' . $this->user->id,
            ],
            'status'           => 'required',
            'password'         => [
                $this->user->id ? 'nullable' : 'required',
                'min:8',
                'confirmed',
            ],
            'selected_rules'   => 'nullable|array',
            'selected_rules.*' => 'exists:roles,id',
            'avatar'           => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048', // 2MB Max
            ],
        ];
    }

    public function submit(): void
    {
        $payload          = $this->validate();
        $payload['rules'] = $payload['selected_rules'];

        if ($this->user->id) {
            UpdateUserAction::run($this->user, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('user.model')]),
                redirectTo: route('admin.user.index')
            );
        } else {
            StoreUserAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('user.model')]),
                redirectTo: route('admin.user.index')
            );
        }
    }

    public function render(): View
    {
        return view('livewire.admin.pages.user.user-update-or-create', [
            'edit_mode'          => $this->user->id,
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.user.index'), 'label' => trans('general.page.index.title', ['model' => trans('user.model')])],
                ['label' => trans('general.page.create.title', ['model' => trans('user.model')])],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.user.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
