<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User;

use App\Enums\GenderEnum;
use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserUpdateOrCreate extends Component
{
    use CrudHelperTrait;

    public User $user;

    public string $activeTab = 'basic';

    public bool $edit_mode = false;

    public UserTypeEnum $detected_user_type;

    public string $detected_route_name = 'admin.user';

    /** @param  User|null  $user  Avoid type-hint to prevent Laravel implicit route binding on create (no route param). */
    public function mount(mixed $user = null): void
    {
        $this->getCurrentUserType();
        $this->getRoutePrefix();

        $this->user = $user instanceof User ? $user : new User;
        $this->edit_mode = (bool) $this->user->id;
    }

    public function getCurrentUserType(): UserTypeEnum
    {
        $routeName = request()->route()?->getName();
        $this->detected_user_type = match (true) {
            str_starts_with($routeName ?? '', 'admin.parent.') => UserTypeEnum::PARENT,
            str_starts_with($routeName ?? '', 'admin.employee.') => UserTypeEnum::EMPLOYEE,
            str_starts_with($routeName ?? '', 'admin.teacher.') => UserTypeEnum::TEACHER,
            default => UserTypeEnum::USER,
        };

        return $this->detected_user_type;
    }

    public function getRoutePrefix(): string
    {
        $routeName = request()->route()?->getName();
        $this->detected_route_name = match (true) {
            str_starts_with($routeName ?? '', 'admin.parent.') => 'admin.parent',
            str_starts_with($routeName ?? '', 'admin.employee.') => 'admin.employee',
            str_starts_with($routeName ?? '', 'admin.teacher.') => 'admin.teacher',
            default => 'admin.user',
        };

        return $this->detected_route_name;
    }

    public function render(): View
    {
        return view('livewire.admin.pages.user.user-update-or-create', [
            'breadcrumbs' => [
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
            'male_parents' => User::query()->where('type', UserTypeEnum::PARENT->value)
                ->whereHas('profile', fn ($q) => $q->where('gender', GenderEnum::MALE->value))
                ->get()->map(fn (User $u) => [
                    'label' => $u->full_name . ' (' . $u->mobile . ')',
                    'value' => $u->id,
                ]),
            'female_parents' => User::query()->where('type', UserTypeEnum::PARENT->value)
                ->whereHas('profile', fn ($q) => $q->where('gender', GenderEnum::FEMALE->value))
                ->get()->map(fn (User $u) => [
                    'label' => $u->full_name . ' (' . $u->mobile . ')',
                    'value' => $u->id,
                ]),
            'childrens' => User::query()->where('type', UserTypeEnum::USER->value)->get()->map(fn (User $u) => [
                'label' => $u->full_name . ' (' . $u->mobile . ')',
                'value' => $u->id,
            ])->toArray(),
            'roles' => Role::all()->map(fn ($item) => ['name' => $item->name, 'id' => $item->id])->toArray(),
        ]);
    }
}
