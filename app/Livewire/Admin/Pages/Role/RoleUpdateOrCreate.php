<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Role;

use App\Actions\Role\StoreRoleAction;
use App\Actions\Role\UpdateRoleAction;
use App\Models\Role;
use App\Services\Permissions\PermissionsService;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Permission;

class RoleUpdateOrCreate extends Component
{
    use Toast;

    public Role $role;
    public ?int $edit_mode      = null;
    public ?string $name        = '';
    public ?string $description = '';
    public array $permissions   = [];

    public function mount(Role $role): void
    {
        $this->role        = $role->load('permissions');
        $this->name        = $role->name;
        $this->description = $role->description;
        $this->permissions = $role->permissions->pluck('id')->map(fn ($id) => (int) $id)->toArray();
        $this->edit_mode   = $role->id;
    }

    protected function rules(): array
    {
        return [
            'name'          => 'required|string',
            'description'   => 'nullable|string',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'required|exists:permissions,id',
        ];
    }

    #[On('admin-selected')]
    public function clearAllExceptAdmin(): void
    {
        $adminId           = Permission::where('name', 'Shared.Admin')->value('id');
        $this->permissions = [$adminId]; // keep only Admin
    }

    //    public function toggleAllPermissions(): void
    //    {
    //        $allPermissions = $this->getAllPossiblePermissions(); // Your method
    //        $this->permissions = count($this->permissions) === count($allPermissions)
    //            ? []
    //            : $allPermissions;
    //    }
    //
    //    public function getAllPossiblePermissions(): array
    //    {
    //        $result = [];
    //        foreach (PermissionsService::showPermissionsByService() as $arrayItem) {
    //            foreach ($arrayItem['items'] as $item) {
    //                $permissionId = Permission::where('name', $item['value'])->value('id');
    //                $result[] = $permissionId;
    //            }
    //        }
    //
    //        return $result;
    //    }

    public function submit(): void
    {
        $payload = $this->validate();
        if ($this->role->id) {
            UpdateRoleAction::run($this->role, $payload);
            $this->success(
                title: trans('general.model_has_updated_successfully', ['model' => trans('role.model')]),
                redirectTo: route('admin.role.index')
            );
        } else {
            StoreRoleAction::run($payload);
            $this->success(
                title: trans('general.model_has_stored_successfully', ['model' => trans('role.model')]),
                redirectTo: route('admin.role.index')
            );
        }
    }

    public function cancel(): void
    {
        $this->redirect(route('admin.role.index'));
    }

    public function render(): View
    {
        return view('livewire.admin.pages.role.role-update-or-create', [
            'edit_mode'          => $this->role->id,
            'breadcrumbs'        => [
                ['label' => $this->role->id ? 'آپدیت نقش' : 'ایجاد نقش'],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.role.index'), 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
