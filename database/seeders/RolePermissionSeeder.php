<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Services\Permissions\PermissionsService;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        foreach (RoleEnum::values() as $iValue) {
            Role::updateOrCreate([
                'name' => $iValue,
            ]);
        }

        foreach (PermissionsService::showPermissionsByService(['core']) as $iValue) {
            foreach ($iValue['items'] as $item) {
                Permission::updateOrCreate(['name' => $item['value']]);
            }
        }

        // syncPermissions
        $admin_role_api = Role::where('name', RoleEnum::ADMIN->value)->first();
        $admin_role_api?->syncPermissions(['Shared.Admin']);
    }
}
