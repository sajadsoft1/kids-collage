<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User;

use App\Models\User;
use App\Services\Permissions\PermissionsService;
use Livewire\Component;

class UserIndex extends Component
{
    public function render()
    {
        return view('livewire.admin.pages.user.user-index', [
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['label' => trans('general.page.index.title', ['model' => trans('user.user')])],
            ],
            'breadcrumbsActions' => [
                [
                    'link' => route('admin.user.create'),
                    'icon' => 's-plus',
                    'label' => trans(
                        'general.page.create.title',
                        ['model' => trans('user.user')]
                    ),
                    'access' => auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Store')),
                ],
            ],
        ]);
    }
}
