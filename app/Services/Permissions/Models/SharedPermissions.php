<?php

declare(strict_types=1);

namespace App\Services\Permissions\Models;

class SharedPermissions extends BasePermissions
{
    public const Admin             = 'Admin';
    public const ReceiveNewUserSms = 'ReceiveNewUserSms';
    protected string $model        = 'Shared';

    public function __construct()
    {
        $this->permissions = [
            'Admin',
            'ReceiveNewUserSms',
        ];
        $this->groupTitle        = trans('permissions.shared');
        $this->otherTranslations = [
            'Shared.Admin'             => 'Admin',
            'Shared.ReceiveNewUserSms' => 'Receive New User SMS',
        ];
    }
}
