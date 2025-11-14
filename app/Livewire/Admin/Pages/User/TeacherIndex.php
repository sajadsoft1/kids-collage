<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\User;

use App\Models\User;
use App\Services\Permissions\PermissionsService;
use Livewire\Component;

class TeacherIndex extends Component
{
    public function render()
    {
        return view('livewire.admin.pages.user.teacher-index', [
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['label' => trans('general.page.index.title', ['model' => trans('user.teacher')])],
            ],
            'breadcrumbsActions' => [
                [
                    'link' => route('admin.teacher.create'),
                    'icon' => 's-plus',
                    'label' => trans(
                        'general.page.create.title',
                        ['model' => trans('user.teacher')]
                    ),
                    'access' => auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel(User::class, 'Store')),
                ],
            ],
        ]);
    }
}
