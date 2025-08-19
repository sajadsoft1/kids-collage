<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Role;

use App\Models\Role;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class RoleApp extends Component
{
    use Toast, WithPagination;

    public function edit(?int $roleId = null): void
    {
        $this->redirect(route('admin.role.edit-create', [
            'role' => $roleId,
        ]), true);
    }

    public function show($roleId): void {}

    public function render(): View
    {
        return view(
            'livewire.admin.pages.role.role-app',
            data: [
                'roles'              => Role::all(),
                'breadcrumbs'        => [
                    ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                    ['label' => trans('general.page.index.title', ['model' => trans('role.model')])],
                ],
                'breadcrumbsActions' => [
                    ['link' => route('admin.role.edit-create'), 'icon' => 's-plus', 'label' => 'ایجاد نقش جدید'],
                ],
            ]
        );
    }
}
